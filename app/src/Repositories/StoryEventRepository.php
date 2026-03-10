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
        $dayMap = [
            '2026-07-23' => 'Thursday',
            '2026-07-24' => 'Friday',
            '2026-07-25' => 'Saturday',
            '2026-07-26' => 'Sunday',
        ];
        $dayName = $dayMap[$date] ?? '';

        $db = DB::getConnection();

        $whereClauses = ["(event_date != '<last weekend of July>' AND event_date = :date)"];
        $params = [':date' => $date];

        if ($dayName !== '') {
            $whereClauses[] = "(event_date = '<last weekend of July>' AND day = :day_name)";
            $params[':day_name'] = $dayName;
        }

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
            WHERE " . implode(' OR ', $whereClauses) . "
            ORDER BY event_date, time_slot, story_event_id
        ";

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('Error fetching story_event rows by date: ' . $e->getMessage());
            return [];
        }

        $events = [];
        foreach ($rows as $row) {
            $events[] = $this->formatEvent($row);
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
            'title'       => $first['title'] ?? 'Stories in Haarlem',
            'description' => $first['category'] ?? '',
            'image'       => '/img/featured-storyteller.jpg',
            'guide_name'  => $first['location'] ?? 'Stories in Haarlem',
            'language'    => $first['language'] ?? 'NL',
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
        } elseif (!empty($row['event_date']) && $row['event_date'] === '<last weekend of July>') {
            $map = [
                'Thursday' => '2026-07-23',
                'Friday'   => '2026-07-24',
                'Saturday' => '2026-07-25',
                'Sunday'   => '2026-07-26',
            ];
            $dow = $row['day'] ?? '';
            $key = $map[$dow] ?? null;
            if ($key !== null) {
                $dateKey = $key;
                try {
                    $dt = new DateTime($key);
                    $dateDisplay = $dt->format('l, F j, Y');
                    $dayOfWeek = $dt->format('l');
                } catch (\Exception $e) {
                    $dateDisplay = $dow;
                }
            } else {
                $dateDisplay = $row['day'] ?? '';
                $dateKey = (string)$row['event_date'];
            }
        } else {
            $dateDisplay = $row['day'] ?? '';
            $dateKey = (string)($row['event_date'] ?? ($row['day'] ?? ''));
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
            'image'            => '/img/storytelling-default.jpg',
            'guide_name'       => '',
            'language'         => $row['language'] ?? 'NL',
            'session_id'       => (int)($row['id'] ?? 0),
            'date'             => $dateDisplay,
            'time'             => $timeSlot,
            'end_time'         => '',
            'price'            => $formattedPrice,
            'location_name'    => $row['location'] ?? '',
            'location_address' => '',
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

        if (!empty($row['event_date']) && $row['event_date'] === '<last weekend of July>') {
            $map = [
                'Thursday' => '2026-07-23',
                'Friday'   => '2026-07-24',
                'Saturday' => '2026-07-25',
                'Sunday'   => '2026-07-26',
            ];
            $dow = $row['day'] ?? '';
            return $map[$dow] ?? (string)$row['event_date'];
        }

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
}

