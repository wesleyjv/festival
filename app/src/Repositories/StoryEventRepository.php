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

