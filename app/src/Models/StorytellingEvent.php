<?php

namespace App\Models;

use PDO;
use PDOException;

class StorytellingEvent {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Get storytelling events with their sessions and locations
     */
    public function getStorytellingEvents(): array {
        // First, let's try a simpler query to see what's available
        try {
            // Check if we have any events at all
            $checkQuery = "SELECT COUNT(*) as count FROM events WHERE type IN ('story', 'storytelling')";
            $stmt = $this->db->prepare($checkQuery);
            $stmt->execute();
            $result = $stmt->fetch();
            error_log("Events with story-related types: " . $result['count']);
            
            if ($result['count'] == 0) {
                error_log("No story events found. Checking all events...");
                $allEventsQuery = "SELECT id, name, type FROM events LIMIT 10";
                $stmt = $this->db->prepare($allEventsQuery);
                $stmt->execute();
                $allEvents = $stmt->fetchAll();
                foreach ($allEvents as $event) {
                    error_log("Event found: ID={$event['id']}, Name={$event['name']}, Type={$event['type']}");
                }
            }
        } catch (Exception $e) {
            error_log("Error checking events: " . $e->getMessage());
        }
        
        // Now try the main query with both possible type values
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
            
            // Debug: Log what we found
            error_log("Found " . count($events) . " storytelling events");
            
            return $events;
        } catch (PDOException $e) {
            error_log("Error fetching storytelling events: " . $e->getMessage());
            error_log("Query was: " . $query);
            return [];
        }
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
            
            return $events;
        } catch (PDOException $e) {
            error_log("Error fetching storytelling events by date: " . $e->getMessage());
            return [];
        }
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
            
            return $events;
        } catch (PDOException $e) {
            error_log("Error fetching storytelling events by time: " . $e->getMessage());
            return [];
        }
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
            
            return $events;
        } catch (PDOException $e) {
            error_log("Error fetching storytelling events by location: " . $e->getMessage());
            return [];
        }
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
            
            return $locations;
        } catch (PDOException $e) {
            error_log("Error fetching storytelling locations: " . $e->getMessage());
            return [];
        }
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
            
            return null;
        } catch (PDOException $e) {
            error_log("Error fetching featured storyteller: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Format event data for display
     */
    private function formatEventData(array $event): array {
        $startTime = new \DateTime($event['start_time']);
        $endTime = new \DateTime($event['end_time']);
        
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
}
