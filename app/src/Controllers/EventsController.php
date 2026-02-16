<?php

namespace App\Controllers;

class EventsController
{
    public function history($vars = [])
    {
        require __DIR__ . '/../views/events/history/overview.php';
    }

    public function jazz($vars = [])
    {
        require __DIR__ . '/../views/events/jazz/overview.php';
    }

    public function stories($vars = [])
    {
        try {
            // Get database connection
            $db = \App\Models\Database::getConnection();
            
            // Initialize storytelling event model
            $storytellingModel = new \App\Models\StorytellingEvent($db);
            
            // Get filter parameters from GET request
            $dateFilter = $_GET['date'] ?? null;
            $timeFilter = $_GET['time'] ?? null;
            $locationFilter = $_GET['location'] ?? null;
            
            // Get events based on filters
            if ($dateFilter) {
                $events = $storytellingModel->getStorytellingEventsByDate($dateFilter);
            } elseif ($timeFilter) {
                $events = $storytellingModel->getStorytellingEventsByTime($timeFilter);
            } elseif ($locationFilter) {
                $events = $storytellingModel->getStorytellingEventsByLocation((int)$locationFilter);
            } else {
                $events = $storytellingModel->getStorytellingEvents();
            }
            
            // Get additional data
            $featuredStoryteller = $storytellingModel->getFeaturedStoryteller();
            $locations = $storytellingModel->getStorytellingLocations();
            
            // Pass data to view
            require __DIR__ . '/../views/events/stories/overview.php';
            
        } catch (Exception $e) {
            error_log("Error in stories controller: " . $e->getMessage());
            
            // Fallback to static view with error handling
            http_response_code(500);
            require __DIR__ . '/../views/events/stories/overview.php';
        }
    }

    public function yummy($vars = [])
    {
        require __DIR__ . '/../views/events/yummy/overview.php';
    }
}
