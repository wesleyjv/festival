<?php

namespace App\Repositories;

use App\DB;
use DateTime;

/**
 * Repository for storytelling events backed by the story_event table.
 */
class StoryEventRepository
{
    /**
     * Fetch all rows from story_event.
     *
     * @return array<int,array<string,mixed>>
     */
    private function fetchRows(): array
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
        ";

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return $rows ?: [];
        } catch (\PDOException $e) {
            error_log('Error fetching story_event rows: ' . $e->getMessage());
            return [];
        }
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
        $events = [];
        foreach ($this->fetchRows() as $row) {
            $hour = $this->getStartHourFromRow($row);
            if ($hour === null) {
                continue;
            }

            $match = false;
            if ($timeOfDay === 'morning') {
                $match = ($hour >= 6 && $hour <= 11);
            } elseif ($timeOfDay === 'afternoon') {
                $match = ($hour >= 12 && $hour <= 17);
            } elseif ($timeOfDay === 'evening') {
                $match = ($hour >= 18 && $hour <= 23);
            }

            if ($match) {
                $events[] = $this->formatEvent($row);
            }
        }

        return $events;
    }

    /**
     * Get storytelling events filtered by location name.
     */
    public function getEventsByLocation(string $locationName): array
    {
        $events = [];
        foreach ($this->fetchRows() as $row) {
            if (strcasecmp($row['location'] ?? '', $locationName) === 0) {
                $events[] = $this->formatEvent($row);
            }
        }

        return $events;
    }

    /**
     * Get distinct locations for filter buttons.
     */
    public function getLocations(): array
    {
        $rows = $this->fetchRows();
        $byName = [];

        foreach ($rows as $row) {
            $name = $row['location'] ?? '';
            if ($name === '') {
                continue;
            }
            if (!isset($byName[$name])) {
                $byName[$name] = [
                    'id'   => $name,
                    'name' => $name,
                ];
            }
        }

        ksort($byName, SORT_NATURAL | SORT_FLAG_CASE);

        return array_values($byName);
    }

    /**
     * Use the first storytelling event as the "featured" storyteller block.
     */
    public function getFeatured(): ?array
    {
        $rows = $this->fetchRows();
        if (empty($rows)) {
            return null;
        }

        $first = $rows[0];

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

