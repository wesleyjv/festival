<?php

namespace App\Models;

use PDO;
use PDOException;
use DateTime;

class StorytellingEvent {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Get storytelling events with their sessions and locations
     */
    public function getStorytellingEvents(): array {
        $query = "
            SELECT 
                e.id,
                e.name,
                e.description,
                e.image,
                se.guide_name,
                se.language,
                s.id as session_id,
                s.start_time,
                s.end_time,
                s.capacity,
                s.price,
                l.name as location_name,
                l.address as location_address
            FROM events e
            LEFT JOIN story_events se ON e.id = se.event_id
            LEFT JOIN sessions s ON e.id = s.event_id
            LEFT JOIN locations l ON s.location_id = l.id
            WHERE e.type IN ('story', 'storytelling')
            ORDER BY s.start_time ASC
        ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $events = [];
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $events[] = $this->formatEventData($row);
            }
            
            if (!empty($events)) {
                return $events;
            }
        } catch (PDOException $e) {
            error_log("Error fetching storytelling events: " . $e->getMessage());
        }

        // Fallback to static schedule if database query fails or returns no rows
        return $this->mapFallbackEvents($this->getFallbackStoryEventsRaw());
    }

    /**
     * Get storytelling events filtered by date
     */
    public function getStorytellingEventsByDate(string $date): array {
        $query = "
            SELECT 
                e.id,
                e.name,
                e.description,
                e.image,
                se.guide_name,
                se.language,
                s.id as session_id,
                s.start_time,
                s.end_time,
                s.capacity,
                s.price,
                l.name as location_name,
                l.address as location_address
            FROM events e
            LEFT JOIN story_events se ON e.id = se.event_id
            LEFT JOIN sessions s ON e.id = s.event_id
            LEFT JOIN locations l ON s.location_id = l.id
            WHERE e.type IN ('story', 'storytelling') 
            AND DATE(s.start_time) = :date
            ORDER BY s.start_time ASC
        ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':date', $date);
            $stmt->execute();
            $events = [];
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $events[] = $this->formatEventData($row);
            }
            
            if (!empty($events)) {
                return $events;
            }
        } catch (PDOException $e) {
            error_log("Error fetching storytelling events by date: " . $e->getMessage());
        }

        // Fallback: filter static schedule by date
        $filtered = [];
        foreach ($this->getFallbackStoryEventsRaw() as $row) {
            $start = new DateTime($row['start_time']);
            if ($start->format('Y-m-d') === $date) {
                $filtered[] = $row;
            }
        }

        return $this->mapFallbackEvents($filtered);
    }

    /**
     * Get storytelling events filtered by time of day
     */
    public function getStorytellingEventsByTime(string $timeOfDay): array {
        $timeConditions = [
            'morning' => "HOUR(s.start_time) BETWEEN 6 AND 11",
            'afternoon' => "HOUR(s.start_time) BETWEEN 12 AND 17",
            'evening' => "HOUR(s.start_time) BETWEEN 18 AND 23"
        ];

        $timeCondition = $timeConditions[$timeOfDay] ?? $timeConditions['morning'];

        $query = "
            SELECT 
                e.id,
                e.name,
                e.description,
                e.image,
                se.guide_name,
                se.language,
                s.id as session_id,
                s.start_time,
                s.end_time,
                s.capacity,
                s.price,
                l.name as location_name,
                l.address as location_address
            FROM events e
            LEFT JOIN story_events se ON e.id = se.event_id
            LEFT JOIN sessions s ON e.id = s.event_id
            LEFT JOIN locations l ON s.location_id = l.id
            WHERE e.type IN ('story', 'storytelling') 
            AND $timeCondition
            ORDER BY s.start_time ASC
        ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $events = [];
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $events[] = $this->formatEventData($row);
            }
            
            if (!empty($events)) {
                return $events;
            }
        } catch (PDOException $e) {
            error_log("Error fetching storytelling events by time: " . $e->getMessage());
        }

        // Fallback: filter static schedule by time of day
        $filtered = [];
        foreach ($this->getFallbackStoryEventsRaw() as $row) {
            $hour = (int)(new DateTime($row['start_time']))->format('H');
            $isMatch = false;
            if ($timeOfDay === 'morning') {
                $isMatch = ($hour >= 6 && $hour <= 11);
            } elseif ($timeOfDay === 'afternoon') {
                $isMatch = ($hour >= 12 && $hour <= 17);
            } elseif ($timeOfDay === 'evening') {
                $isMatch = ($hour >= 18 && $hour <= 23);
            }

            if ($isMatch) {
                $filtered[] = $row;
            }
        }

        return $this->mapFallbackEvents($filtered);
    }

    /**
     * Get storytelling events filtered by location
     */
    public function getStorytellingEventsByLocation(int $locationId): array {
        $query = "
            SELECT 
                e.id,
                e.name,
                e.description,
                e.image,
                se.guide_name,
                se.language,
                s.id as session_id,
                s.start_time,
                s.end_time,
                s.capacity,
                s.price,
                l.name as location_name,
                l.address as location_address
            FROM events e
            LEFT JOIN story_events se ON e.id = se.event_id
            LEFT JOIN sessions s ON e.id = s.event_id
            LEFT JOIN locations l ON s.location_id = l.id
            WHERE e.type IN ('story', 'storytelling') 
            AND s.location_id = :location_id
            ORDER BY s.start_time ASC
        ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':location_id', $locationId, PDO::PARAM_INT);
            $stmt->execute();
            $events = [];
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $events[] = $this->formatEventData($row);
            }
            
            if (!empty($events)) {
                return $events;
            }
        } catch (PDOException $e) {
            error_log("Error fetching storytelling events by location: " . $e->getMessage());
        }

        // Fallback: filter static schedule by location
        $filtered = [];
        foreach ($this->getFallbackStoryEventsRaw() as $row) {
            if ((int)$row['location_id'] === $locationId) {
                $filtered[] = $row;
            }
        }

        return $this->mapFallbackEvents($filtered);
    }

    /**
     * Get all locations for storytelling events
     */
    public function getStorytellingLocations(): array {
        $query = "
            SELECT DISTINCT 
                l.id,
                l.name,
                l.address
            FROM locations l
            INNER JOIN sessions s ON l.id = s.location_id
            INNER JOIN events e ON s.event_id = e.id
            WHERE e.type IN ('story', 'storytelling')
            ORDER BY l.name
        ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $locations = [];
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $locations[] = $row;
            }
            
            if (!empty($locations)) {
                return $locations;
            }
        } catch (PDOException $e) {
            error_log("Error fetching storytelling locations: " . $e->getMessage());
        }

        // Fallback locations derived from static schedule
        $byId = [];
        foreach ($this->getFallbackStoryEventsRaw() as $row) {
            $id = (int)$row['location_id'];
            if (!isset($byId[$id])) {
                $byId[$id] = [
                    'id' => $id,
                    'name' => $row['location_name'],
                    'address' => $row['location_address'],
                ];
            }
        }

        return array_values($byId);
    }

    /**
     * Get featured storyteller (first storytelling event as featured)
     */
    public function getFeaturedStoryteller(): ?array {
        $query = "
            SELECT 
                e.id,
                e.name,
                e.description,
                e.image,
                se.guide_name,
                se.language
            FROM events e
            LEFT JOIN story_events se ON e.id = se.event_id
            WHERE e.type IN ('story', 'storytelling')
            LIMIT 1
        ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $featured = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($featured) {
                return $this->formatFeaturedData($featured);
            }
            
        } catch (PDOException $e) {
            error_log("Error fetching featured storyteller: " . $e->getMessage());
        }

        // Fallback: use the first static storytelling event as featured
        $raw = $this->getFallbackStoryEventsRaw();
        if (empty($raw)) {
            return null;
        }

        $first = $raw[0];

        return [
            'id' => $first['id'],
            'title' => $first['name'],
            'description' => $first['description'],
            'image' => $first['image'] ?? '/img/featured-storyteller.jpg',
            'guide_name' => $first['guide_name'] ?? 'Master Storyteller',
            'language' => $first['language'] ?? 'English',
        ];
    }

    /**
     * Format event data for display
     */
    private function formatEventData(array $event): array {
        $startTime = new DateTime($event['start_time']);
        $endTime = new DateTime($event['end_time']);
        
        return [
            'id' => $event['id'],
            'title' => $event['name'],
            'description' => $event['description'],
            'image' => $event['image'] ?? '/img/storytelling-default.jpg',
            'guide_name' => $event['guide_name'] ?? 'Master Storyteller',
            'language' => $event['language'] ?? 'English',
            'session_id' => $event['session_id'],
            'date' => $startTime->format('l, F j, Y'),
            'time' => $startTime->format('g:i A'),
            'end_time' => $endTime->format('g:i A'),
            'price' => number_format($event['price'], 2),
            'location_name' => $event['location_name'],
            'location_address' => $event['location_address'],
            'day_of_week' => $startTime->format('l'),
            'date_key' => $startTime->format('Y-m-d')
        ];
    }

    /**
     * Format featured storyteller data
     */
    private function formatFeaturedData(array $featured): array {
        return [
            'id' => $featured['id'],
            'title' => $featured['name'],
            'description' => $featured['description'],
            'image' => $featured['image'] ?? '/img/featured-storyteller.jpg',
            'guide_name' => $featured['guide_name'] ?? 'Master Storyteller',
            'language' => $featured['language'] ?? 'English'
        ];
    }

    /**
     * Static fallback schedule for storytelling events (last weekend of July).
     *
     * This is used when the database is unavailable or empty so that
     * the Storytelling page and its filters still work.
     */
    private function getFallbackStoryEventsRaw(): array
    {
        // Location IDs are arbitrary but stable for filter URLs
        $locations = [
            'VERHALENHUIS' => ['id' => 1, 'name' => 'Verhalenhuis Haarlem', 'address' => 'Verhalenhuis Haarlem, Haarlem'],
            'DE_SCHUUR' => ['id' => 2, 'name' => 'De Schuur', 'address' => 'De Schuur, Haarlem'],
            'KWEEKCAFE' => ['id' => 3, 'name' => 'Kweekcafé', 'address' => 'Kweekcafé, Haarlem'],
            'CORRIE_TEN_BOOM' => ['id' => 4, 'name' => 'Corrie ten Boom Huis', 'address' => 'Corrie ten Boom Huis, Haarlem'],
            'THEATER_ELSWOUT' => ['id' => 5, 'name' => 'Theater Elswout', 'address' => 'Theater Elswout, Haarlem'],
        ];

        return [
            // Thursday
            [
                'id' => 1001,
                'name' => 'Winnie de Pooh',
                'description' => 'Warm and playful stories for the whole family inspired by Winnie de Pooh.',
                'image' => '/img/storytelling-family-1.jpg',
                'guide_name' => 'Stories in Haarlem',
                'language' => 'Dutch',
                'session_id' => 1001,
                'start_time' => '2026-07-23 16:00:00',
                'end_time' => '2026-07-23 17:00:00',
                'capacity' => 80,
                'price' => 6.00,
                'location_name' => $locations['VERHALENHUIS']['name'],
                'location_address' => $locations['VERHALENHUIS']['address'],
                'location_id' => $locations['VERHALENHUIS']['id'],
            ],
            [
                'id' => 1002,
                'name' => 'Omdenken Podcast Live',
                'description' => 'Recording a live podcast with audience participation about stories with impact.',
                'image' => '/img/storytelling-podcast.jpg',
                'guide_name' => 'Stories in Haarlem',
                'language' => 'Dutch',
                'session_id' => 1002,
                'start_time' => '2026-07-23 19:00:00',
                'end_time' => '2026-07-23 20:15:00',
                'capacity' => 150,
                'price' => 12.50,
                'location_name' => $locations['DE_SCHUUR']['name'],
                'location_address' => $locations['DE_SCHUUR']['address'],
                'location_id' => $locations['DE_SCHUUR']['id'],
            ],
            [
                'id' => 1003,
                'name' => 'Stories with Impact',
                'description' => 'Powerful true stories that stay with you long after the evening ends.',
                'image' => '/img/storytelling-impact.jpg',
                'guide_name' => 'Stories in Haarlem',
                'language' => 'English',
                'session_id' => 1003,
                'start_time' => '2026-07-23 20:30:00',
                'end_time' => '2026-07-23 21:45:00',
                'capacity' => 120,
                'price' => 0.00, // pay as you like
                'location_name' => $locations['KWEEKCAFE']['name'],
                'location_address' => $locations['KWEEKCAFE']['address'],
                'location_id' => $locations['KWEEKCAFE']['id'],
            ],

            // Friday
            [
                'id' => 1004,
                'name' => 'Corrie ten Boom Stories',
                'description' => 'Stories about courage and hope in the Corrie ten Boom house.',
                'image' => '/img/storytelling-history.jpg',
                'guide_name' => 'House Guides',
                'language' => 'Dutch',
                'session_id' => 1004,
                'start_time' => '2026-07-24 16:00:00',
                'end_time' => '2026-07-24 17:00:00',
                'capacity' => 40,
                'price' => 0.00, // pay as you like
                'location_name' => $locations['CORRIE_TEN_BOOM']['name'],
                'location_address' => $locations['CORRIE_TEN_BOOM']['address'],
                'location_id' => $locations['CORRIE_TEN_BOOM']['id'],
            ],
            [
                'id' => 1005,
                'name' => 'Winners of the Story Competition',
                'description' => 'Award-winning storytellers share the best new stories from Haarlem.',
                'image' => '/img/storytelling-competition.jpg',
                'guide_name' => 'Stories in Haarlem',
                'language' => 'Dutch',
                'session_id' => 1005,
                'start_time' => '2026-07-24 19:00:00',
                'end_time' => '2026-07-24 20:30:00',
                'capacity' => 100,
                'price' => 12.50,
                'location_name' => $locations['VERHALENHUIS']['name'],
                'location_address' => $locations['VERHALENHUIS']['address'],
                'location_id' => $locations['VERHALENHUIS']['id'],
            ],
            [
                'id' => 1006,
                'name' => 'Flip Thinking Podcast',
                'description' => 'Another live podcast evening full of surprising perspectives.',
                'image' => '/img/storytelling-podcast-2.jpg',
                'guide_name' => 'Stories in Haarlem',
                'language' => 'Dutch',
                'session_id' => 1006,
                'start_time' => '2026-07-24 20:30:00',
                'end_time' => '2026-07-24 21:45:00',
                'capacity' => 150,
                'price' => 12.50,
                'location_name' => $locations['DE_SCHUUR']['name'],
                'location_address' => $locations['DE_SCHUUR']['address'],
                'location_id' => $locations['DE_SCHUUR']['id'],
            ],

            // Saturday
            [
                'id' => 1007,
                'name' => 'Mister Anansi',
                'description' => 'Classic Anansi stories for children and their families.',
                'image' => '/img/storytelling-anansi.jpg',
                'guide_name' => 'Mister Anansi',
                'language' => 'Dutch',
                'session_id' => 1007,
                'start_time' => '2026-07-25 10:00:00',
                'end_time' => '2026-07-25 11:00:00',
                'capacity' => 90,
                'price' => 10.00,
                'location_name' => $locations['THEATER_ELSWOUT']['name'],
                'location_address' => $locations['THEATER_ELSWOUT']['address'],
                'location_id' => $locations['THEATER_ELSWOUT']['id'],
            ],
            [
                'id' => 1008,
                'name' => 'Mister Anansi Special',
                'description' => 'Extra-long Anansi show with music and audience participation.',
                'image' => '/img/storytelling-anansi-2.jpg',
                'guide_name' => 'Mister Anansi',
                'language' => 'Dutch',
                'session_id' => 1008,
                'start_time' => '2026-07-25 15:00:00',
                'end_time' => '2026-07-25 16:00:00',
                'capacity' => 90,
                'price' => 10.00,
                'location_name' => $locations['THEATER_ELSWOUT']['name'],
                'location_address' => $locations['THEATER_ELSWOUT']['address'],
                'location_id' => $locations['THEATER_ELSWOUT']['id'],
            ],
            [
                'id' => 1009,
                'name' => 'Recording Podcast with Audience',
                'description' => 'An intimate podcast taping with stories that matter.',
                'image' => '/img/storytelling-podcast-3.jpg',
                'guide_name' => 'Stories in Haarlem',
                'language' => 'Dutch',
                'session_id' => 1009,
                'start_time' => '2026-07-25 14:00:00',
                'end_time' => '2026-07-25 15:15:00',
                'capacity' => 140,
                'price' => 12.50,
                'location_name' => $locations['DE_SCHUUR']['name'],
                'location_address' => $locations['DE_SCHUUR']['address'],
                'location_id' => $locations['DE_SCHUUR']['id'],
            ],
            [
                'id' => 1010,
                'name' => 'The Ten Boom Family Story',
                'description' => 'Moving stories about the Ten Boom family and their resistance work.',
                'image' => '/img/storytelling-history-2.jpg',
                'guide_name' => 'House Guides',
                'language' => 'Dutch',
                'session_id' => 1010,
                'start_time' => '2026-07-25 13:00:00',
                'end_time' => '2026-07-25 14:30:00',
                'capacity' => 40,
                'price' => 0.00, // pay as you like
                'location_name' => $locations['CORRIE_TEN_BOOM']['name'],
                'location_address' => $locations['CORRIE_TEN_BOOM']['address'],
                'location_id' => $locations['CORRIE_TEN_BOOM']['id'],
            ],

            // Sunday
            [
                'id' => 1011,
                'name' => 'Mister Anansi (Sunday)',
                'description' => 'Sunday morning Anansi stories for all ages.',
                'image' => '/img/storytelling-anansi.jpg',
                'guide_name' => 'Mister Anansi',
                'language' => 'English',
                'session_id' => 1011,
                'start_time' => '2026-07-26 10:00:00',
                'end_time' => '2026-07-26 11:00:00',
                'capacity' => 90,
                'price' => 10.00,
                'location_name' => $locations['THEATER_ELSWOUT']['name'],
                'location_address' => $locations['THEATER_ELSWOUT']['address'],
                'location_id' => $locations['THEATER_ELSWOUT']['id'],
            ],
            [
                'id' => 1012,
                'name' => 'Mister Anansi Family Show',
                'description' => 'Final Anansi performance of the weekend for the whole family.',
                'image' => '/img/storytelling-anansi-3.jpg',
                'guide_name' => 'Mister Anansi',
                'language' => 'English',
                'session_id' => 1012,
                'start_time' => '2026-07-26 15:00:00',
                'end_time' => '2026-07-26 16:00:00',
                'capacity' => 90,
                'price' => 10.00,
                'location_name' => $locations['THEATER_ELSWOUT']['name'],
                'location_address' => $locations['THEATER_ELSWOUT']['address'],
                'location_id' => $locations['THEATER_ELSWOUT']['id'],
            ],
            [
                'id' => 1013,
                'name' => 'The Ten Boom Family (Sunday)',
                'description' => 'Another chance to hear the Ten Boom family story.',
                'image' => '/img/storytelling-history-3.jpg',
                'guide_name' => 'House Guides',
                'language' => 'English',
                'session_id' => 1013,
                'start_time' => '2026-07-26 13:30:00',
                'end_time' => '2026-07-26 14:30:00',
                'capacity' => 40,
                'price' => 0.00, // pay as you like
                'location_name' => $locations['CORRIE_TEN_BOOM']['name'],
                'location_address' => $locations['CORRIE_TEN_BOOM']['address'],
                'location_id' => $locations['CORRIE_TEN_BOOM']['id'],
            ],
            [
                'id' => 1014,
                'name' => 'Best Of Stories in Haarlem',
                'description' => 'A best-of selection closing the Stories in Haarlem weekend.',
                'image' => '/img/storytelling-bestof.jpg',
                'guide_name' => 'Stories in Haarlem',
                'language' => 'English',
                'session_id' => 1014,
                'start_time' => '2026-07-26 16:00:00',
                'end_time' => '2026-07-26 17:30:00',
                'capacity' => 120,
                'price' => 12.50,
                'location_name' => $locations['VERHALENHUIS']['name'],
                'location_address' => $locations['VERHALENHUIS']['address'],
                'location_id' => $locations['VERHALENHUIS']['id'],
            ],
        ];
    }

    /**
     * Helper to map raw fallback rows into the same structure as DB results.
     *
     * @param array $rows
     * @return array
     */
    private function mapFallbackEvents(array $rows): array
    {
        $events = [];

        foreach ($rows as $row) {
            $formatted = $this->formatEventData($row);
            // Preserve synthetic location ID so location filter buttons work
            $formatted['location_id'] = (int)$row['location_id'];
            $events[] = $formatted;
        }

        return $events;
    }
}
