<?php

namespace App\Repositories;

use App\DB;
use DateTime;

/** Repository for storytelling events backed by the story_event table. */
class StoryEventRepository
{
    /** Sentinel event_date for events not pinned to a concrete calendar date. */
    private const UNSCHEDULED_DATE = '<last weekend of July>';

    /** Time-of-day buckets keyed by filter value: [startHour, endHour] inclusive. */
    private const TIME_RANGES = [
        'morning'   => [6, 11],
        'afternoon' => [12, 17],
        'evening'   => [18, 23],
    ];

    private const SELECT_COLUMNS = '
        story_event_id AS id,
        event_date,
        day,
        time_slot,
        location,
        age_group,
        title,
        language,
        price,
        category
    ';

    /** @var array<int,array<string,mixed>>|null In-request row cache. */
    private ?array $cachedRows = null;

    private ?\PDO $db = null;

    /** One lazily-acquired PDO handle, reused by every query in this instance. */
    private function db(): \PDO
    {
        return $this->db ??= DB::getConnection();
    }

    /**
     * All story_event rows, cached for the instance. Every read path filters this
     * single result set in PHP, so a request issues at most one query.
     *
     * @return array<int,array<string,mixed>>
     */
    private function fetchRows(): array
    {
        if ($this->cachedRows !== null) {
            return $this->cachedRows;
        }

        $db = $this->db();

        $sql = 'SELECT ' . self::SELECT_COLUMNS
            . ' FROM story_event ORDER BY event_date, time_slot, story_event_id';

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $this->cachedRows = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\PDOException $e) {
            error_log('Error fetching story_event rows: ' . $e->getMessage());
            $this->cachedRows = [];
        }

        return $this->cachedRows;
    }

    /** @return array<int,array<string,mixed>> Raw rows for admin CRUD screens. */
    public function getAllForAdmin(): array
    {
        return $this->fetchRows();
    }

    public function getEvents(): array
    {
        return $this->getFilteredEvents();
    }

    /**
     * Events matching every supplied filter (AND); null filters are ignored.
     * Day and location match case-insensitively; timeOfDay is morning|afternoon|evening.
     *
     * @return array<int,array<string,mixed>>
     */
    public function getFilteredEvents(
        ?string $day = null,
        ?string $date = null,
        ?string $timeOfDay = null,
        ?string $location = null
    ): array {
        $events = [];

        foreach ($this->fetchRows() as $row) {
            if ($day !== null && strtolower((string) ($row['day'] ?? '')) !== strtolower($day)) {
                continue;
            }
            if ($date !== null && $this->dateKey($row) !== $date) {
                continue;
            }
            if ($timeOfDay !== null && !$this->matchesTimeOfDay($row, $timeOfDay)) {
                continue;
            }
            if ($location !== null
                && strtolower(trim((string) ($row['location'] ?? ''))) !== strtolower(trim($location))) {
                continue;
            }

            $events[] = $this->formatEvent($row);
        }

        return $events;
    }

    /**
     * Single event by id with the detail-only language/category fields, or null.
     *
     * @return array<string,mixed>|null
     */
    public function getEventById(int $id): ?array
    {
        foreach ($this->fetchRows() as $row) {
            if ((int) ($row['id'] ?? 0) === $id) {
                $event = $this->formatEvent($row);
                $event['language'] = $row['language'] ?? '';
                $event['category'] = $row['category'] ?? '';

                return $event;
            }
        }

        return null;
    }

    /** @return array<int,array{id:string,name:string}> Distinct locations for filter buttons. */
    public function getLocations(): array
    {
        $names = [];
        foreach ($this->fetchRows() as $row) {
            $location = trim((string) ($row['location'] ?? ''));
            if ($location !== '') {
                $names[$location] = true;
            }
        }

        $names = array_keys($names);
        sort($names);

        return array_map(static fn (string $name): array => ['id' => $name, 'name' => $name], $names);
    }

    public function getFeatured(): ?array
    {
        $rows = $this->fetchRows();
        if ($rows === []) {
            return null;
        }

        // The table has no storyteller/image columns, so only the title is real; the
        // rest are null so the view falls back to its curated CMS defaults.
        return [
            'title'       => $rows[0]['title'] ?? '',
            'description' => null,
            'image'       => null,
            'guide_name'  => null,
        ];
    }

    /** Maps a row to the array consumed by the view + ticket mapping (only fields actually read). */
    private function formatEvent(array $row): array
    {
        [$dateDisplay, , $dayOfWeek] = $this->parseDate($row);

        $rawPrice = $row['price'] ?? '';
        $formattedPrice = is_numeric($rawPrice) ? number_format((float) $rawPrice, 2) : $rawPrice;

        return [
            'id'            => (int) ($row['id'] ?? 0),
            'title'         => $row['title'] ?? '',
            'description'   => $row['category'] ?? '',
            'age_group'     => $row['age_group'] ?? '',
            'date'          => $dateDisplay,
            'time'          => $row['time_slot'] ?? '',
            'price'         => $formattedPrice,
            'location_name' => $row['location'] ?? '',
            'day_of_week'   => $dayOfWeek,
        ];
    }

    /**
     * @return array{0:string,1:string,2:string} [display, dateKey, dayOfWeek].
     *         Unscheduled dates keep their raw stored values instead of a real date.
     */
    private function parseDate(array $row): array
    {
        $rawDate = (string) ($row['event_date'] ?? '');
        $day     = (string) ($row['day'] ?? '');

        if ($rawDate !== '' && $rawDate !== self::UNSCHEDULED_DATE) {
            try {
                $dt = new DateTime($rawDate);
                return [$dt->format('l, F j, Y'), $dt->format('Y-m-d'), $dt->format('l')];
            } catch (\Exception $e) {
                // Not a parseable date — fall through to raw values.
            }
        }

        $display = $day !== '' ? $day : $rawDate;
        $key     = $rawDate !== '' ? $rawDate : $day;

        return [$display, $key, $day];
    }

    private function dateKey(array $row): string
    {
        return $this->parseDate($row)[1];
    }

    private function matchesTimeOfDay(array $row, string $timeOfDay): bool
    {
        $range = self::TIME_RANGES[$timeOfDay] ?? null;
        if ($range === null) {
            return false;
        }

        $hour = $this->startHour((string) ($row['time_slot'] ?? ''));

        return $hour !== null && $hour >= $range[0] && $hour <= $range[1];
    }

    /** Starting hour (0–23) from a time_slot like "16:00-17:00". */
    private function startHour(string $timeSlot): ?int
    {
        if (preg_match('/^(\d{1,2}):\d{2}/', $timeSlot, $m)) {
            return (int) $m[1];
        }

        return null;
    }

    /** @param array<string,mixed> $data */
    public function create(array $data): int
    {
        $db = $this->db();

        $sql = "
            INSERT INTO story_event (event_date, day, time_slot, location, age_group, title, language, price, category)
            VALUES (:event_date, :day, :time_slot, :location, :age_group, :title, :language, :price, :category)
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':event_date' => $data['event_date'] ?? null,
            ':day'        => $data['day'] ?? null,
            ':time_slot'  => $data['time_slot'] ?? null,
            ':location'   => $data['location'] ?? null,
            ':age_group'  => $data['age_group'] ?? null,
            ':title'      => $data['title'] ?? null,
            ':language'   => $data['language'] ?? null,
            ':price'      => $data['price'] ?? null,
            ':category'   => $data['category'] ?? null,
        ]);

        return (int)$db->lastInsertId();
    }

    /** @param array<string,mixed> $data */
    public function update(int $id, array $data): void
    {
        $db = $this->db();

        $sql = "
            UPDATE story_event
            SET event_date = :event_date,
                day        = :day,
                time_slot  = :time_slot,
                location   = :location,
                age_group  = :age_group,
                title      = :title,
                language   = :language,
                price      = :price,
                category   = :category
            WHERE story_event_id = :id
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':event_date' => $data['event_date'] ?? null,
            ':day'        => $data['day'] ?? null,
            ':time_slot'  => $data['time_slot'] ?? null,
            ':location'   => $data['location'] ?? null,
            ':age_group'  => $data['age_group'] ?? null,
            ':title'      => $data['title'] ?? null,
            ':language'   => $data['language'] ?? null,
            ':price'      => $data['price'] ?? null,
            ':category'   => $data['category'] ?? null,
            ':id'         => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $db = $this->db();
        $stmt = $db->prepare('DELETE FROM story_event WHERE story_event_id = :id');
        $stmt->execute([':id' => $id]);
    }
}
