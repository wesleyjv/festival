<?php

namespace App\Controllers;

use PDO;

use App\Repositories\EventRepository;

/**
 * Controller responsible for handling event-related page requests.
 *
 * Manages the display of overview pages for each event category:
 * History, Jazz, Stories, and Yummy.
 */
class EventsController
{
    /**
     * @var EventRepository Repository used to retrieve event data.
     */
    private EventRepository $eventRepository;

    /**
     * Initializes the controller with a new EventRepository instance.
     */
    public function __construct()
    {
        $this->eventRepository = new EventRepository();
    }

    /**
     * Displays the history events overview page.
     *
     * Retrieves all history events from the repository and passes them
     * to the history overview view.
     *
     * @return void
     */
    public function history()
    {
        $events = $this->eventRepository->getHistoryEvents();

        require __DIR__ . '/../views/events/history/overview.php';
    }

    /**
     * Displays the jazz events overview page.
     *
     * @param array $vars Optional route parameters passed to the view.
     * @return void
     */
    public function jazz($vars = [])
    {
        require __DIR__ . '/../views/events/jazz/overview.php';
    }

    /**
     * Displays the stories events overview page.
     *
     * @param array $vars Optional route parameters passed to the view.
     * @return void
     */
    public function stories($vars = [])
    {
        try {
            // Load environment variables from .env file
            $envFile = __DIR__ . '/../../.env';
            if (file_exists($envFile)) {
                $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    if (strpos($line, '#') === 0) continue; // Skip comments
                    if (strpos($line, '=') === false) continue; // Skip invalid lines
                    
                    list($key, $value) = explode('=', $line, 2);
                    $key = trim($key);
                    $value = trim($value);
                    $_ENV[$key] = $value;
                }
            }
            
            // Create database connection directly
            $host = $_ENV['DB_HOST'] ?? '';
            $port = $_ENV['DB_PORT'] ?? '';
            $dbname = $_ENV['DB_DATABASE'] ?? '';
            $username = $_ENV['DB_USERNAME'] ?? '';
            $password = $_ENV['DB_PASSWORD'] ?? '';
            
            $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
            $db = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            
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

    /**
     * Displays the yummy events overview page.
     *
     * @param array $vars Optional route parameters passed to the view.
     * @return void
     */
    public function yummy($vars = [])
    {
        require __DIR__ . '/../views/events/yummy/overview.php';
    }
}
