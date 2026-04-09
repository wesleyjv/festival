<?php

namespace App\Repositories;

use App\DB;
use DateTime;

/**
 * Repository for storytelling events backed by the story_event table.
 */
class StoryEventRepository
{
    /** @var array<int,array<string,mixed>>|null In-request row cache to avoid repeated identical queries. */
    private ?array $cachedRows = null;

    /**
     * Fetch all rows from story_event, caching the result for the lifetime of this instance.
     *
     * @return array<int,array<string,mixed>>
     */
    private function fetchRows(): array
    {
        if ($this->cachedRows !== null) {
            return $this->cachedRows;
        }

        $db = DB::getConnection();

        $sql = "
            SELECT
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
            FROM story_event
            ORDER BY event_date, time_slot, story_event_id
        ";

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

    /**
     * Return raw story_event rows for admin CRUD screens.
     *
     * @return array<int,array<string,mixed>>
     */
    public function getAllForAdmin(): array
    {
        return $this->fetchRows();
    }

    /**
     * Get all storytelling events.
     */
    public function getEvents(): array
    {
        $events = [];
        foreach ($this->fetchRows() as $row) {
            $events[] = $this->formatEvent($row);
        }

        return $events;
    }

    /**
     * Get storytelling events filtered by date (Y-m-d).
     */
    public function getEventsByDate(string $date): array
    {
        $events = [];
        foreach ($this->fetchRows() as $row) {
            if ($this->getDateKeyFromRow($row) === $date) {
                $events[] = $this->formatEvent($row);
            }
        }

        return $events;
    }

    /**
     * Get storytelling events filtered by day of week / day label.
     *
     * This compares the case-insensitive value of the "day" column
     * from the story_event table with the requested day, so there is
     * no hardcoded mapping to calendar dates.
     */
    public function getEventsByDay(string $day): array
    {
        $events = [];
        $needle = strtolower($day);

        foreach ($this->fetchRows() as $row) {
            $rowDay = strtolower($row['day'] ?? '');
            if ($rowDay === $needle) {
                $events[] = $this->formatEvent($row);
            }
        }

        return $events;
    }

    /**
     * Get storytelling events filtered by time of day.
     * morning: 06–11, afternoon: 12–17, evening: 18–23.
     */
    public function getEventsByTime(string $timeOfDay): array
    {
        $ranges = [
            'morning'   => [6,  11],
            'afternoon' => [12, 17],
            'evening'   => [18, 23],
        ];

        if (!isset($ranges[$timeOfDay])) {
            return [];
        }

        [$hourStart, $hourEnd] = $ranges[$timeOfDay];

        $db = DB::getConnection();

        $sql = "
            SELECT
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
            FROM story_event
            WHERE CAST(SUBSTRING_INDEX(time_slot, ':', 1) AS UNSIGNED) BETWEEN :hour_start AND :hour_end
            ORDER BY event_date, time_slot, story_event_id
        ";

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([':hour_start' => $hourStart, ':hour_end' => $hourEnd]);
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('Error fetching story_event rows by time: ' . $e->getMessage());
            return [];
        }

        $events = [];
        foreach ($rows as $row) {
            $events[] = $this->formatEvent($row);
        }
        return $events;
    }

    /**
     * Get storytelling events filtered by location name.
     */
    public function getEventsByLocation(string $locationName): array
    {
        $db = DB::getConnection();

        $sql = "
            SELECT
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
            FROM story_event
            WHERE location = :location
            ORDER BY event_date, time_slot, story_event_id
        ";

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([':location' => $locationName]);
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('Error fetching story_event rows by location: ' . $e->getMessage());
            return [];
        }

        $events = [];
        foreach ($rows as $row) {
            $events[] = $this->formatEvent($row);
        }
        return $events;
    }

    /**
     * Get distinct locations for filter buttons.
     */
    public function getLocations(): array
    {
        $db = DB::getConnection();

        try {
            $stmt = $db->query(
                "SELECT DISTINCT location FROM story_event WHERE location IS NOT NULL AND location != '' ORDER BY location"
            );
            $names = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        } catch (\PDOException $e) {
            error_log('Error fetching story_event locations: ' . $e->getMessage());
            $names = [];
        }

        $byName = [];
        foreach ($names as $name) {
            $byName[] = ['id' => $name, 'name' => $name];
        }

        return $byName;
    }

    /**
     * Use the first storytelling event as the "featured" storyteller block.
     */
    public function getFeatured(): ?array
    {
        $db = DB::getConnection();

        $sql = "
            SELECT
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
            FROM story_event
            ORDER BY event_date, time_slot, story_event_id
            LIMIT 1
        ";

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $first = $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('Error fetching featured story_event: ' . $e->getMessage());
            return null;
        }

        if (!$first) {
            return null;
        }

        return [
            'id'          => (int)($first['id'] ?? 0),
            'title'       => $first['title'] ?? '',
            'description' => $first['category'] ?? '',
            'image'       => $first['image'] ?? '',
            'guide_name'  => $first['location'] ?? '',
            'language'    => $first['language'] ?? '',
        ];
    }

    /**
     * Map a story_event row to the array used by the storytelling view.
     */
    private function formatEvent(array $row): array
    {
        $dateDisplay = '';
        $dateKey = '';
        $dayOfWeek = $row['day'] ?? '';

        if (!empty($row['event_date']) && $row['event_date'] !== '<last weekend of July>') {
            try {
                $dt = new DateTime($row['event_date']);
                $dateDisplay = $dt->format('l, F j, Y');
                $dateKey = $dt->format('Y-m-d');
                $dayOfWeek = $dt->format('l');
            } catch (\Exception $e) {
                $dateDisplay = $row['day'] ?? '';
                $dateKey = (string)($row['event_date'] ?? ($row['day'] ?? ''));
            }
        } else {
            // When event_date is not a concrete calendar date (e.g. "<last weekend of July>"),
            // just use the raw values from the row as-is so there is no hardcoded mapping
            // to specific festival years here.
            $dateDisplay = $row['day'] ?? (string)($row['event_date'] ?? '');
            $dateKey     = (string)($row['event_date'] ?? ($row['day'] ?? ''));
        }

        $timeSlot = $row['time_slot'] ?? '';

        $rawPrice = $row['price'] ?? '';
        if (is_numeric($rawPrice)) {
            $formattedPrice = number_format((float)$rawPrice, 2);
        } else {
            $formattedPrice = $rawPrice;
        }

        return [
            'id'               => (int)($row['id'] ?? 0),
            'title'            => $row['title'] ?? '',
            'description'      => $row['category'] ?? '',
            'image'            => $row['image'] ?? '',
            'guide_name'       => $row['guide_name'] ?? '',
            'language'         => $row['language'] ?? '',
            'session_id'       => (int)($row['id'] ?? 0),
            'date'             => $dateDisplay,
            'time'             => $timeSlot,
            'end_time'         => '',
            'price'            => $formattedPrice,
            'location_name'    => $row['location'] ?? '',
            'location_address' => $row['location_address'] ?? '',
            'day_of_week'      => $dayOfWeek,
            'date_key'         => $dateKey,
        ];
    }

    /**
     * Normalized Y-m-d key for a story_event row.
     */
    private function getDateKeyFromRow(array $row): string
    {
        if (!empty($row['event_date']) && $row['event_date'] !== '<last weekend of July>') {
            try {
                $dt = new DateTime($row['event_date']);
                return $dt->format('Y-m-d');
            } catch (\Exception $e) {
                return (string)$row['event_date'];
            }
        }

        // For non-concrete values like "<last weekend of July>", just return
        // whatever is stored without mapping to hardcoded calendar dates.
        return (string)($row['event_date'] ?? '');
    }

    /**
     * Extract starting hour (0–23) from a time_slot like "16:00-17:00".
     */
    private function getStartHourFromRow(array $row): ?int
    {
        $slot = $row['time_slot'] ?? '';
        if (preg_match('/^(\d{1,2}):\d{2}/', $slot, $m)) {
            return (int)$m[1];
        }
        return null;
    }

    /**
     * Create a new story_event row.
     *
     * @param array<string,mixed> $data
     */
    public function create(array $data): int
    {
        $db = DB::getConnection();

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

    /**
     * Update an existing story_event row.
     *
     * @param int $id story_event_id
     * @param array<string,mixed> $data
     */
    public function update(int $id, array $data): void
    {
        $db = DB::getConnection();

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

    /**
     * Delete a story_event row.
     */
    public function delete(int $id): void
    {
        $db = DB::getConnection();
        $stmt = $db->prepare('DELETE FROM story_event WHERE story_event_id = :id');
        $stmt->execute([':id' => $id]);
    }
}

