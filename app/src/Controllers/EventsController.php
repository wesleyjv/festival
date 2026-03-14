<?php

namespace App\Controllers;

use PDO;
use Throwable;

use App\Repositories\EventRepository;
use App\Repositories\StoryEventRepository;
use App\Repositories\YummyEventRepository;
use App\Services\ContentService;
use App\Services\Interfaces\IYummyService;
use App\Services\YummyService;
use App\ViewModels\YummyOverviewViewModel;

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
    private StoryEventRepository $storyEventRepository;
    private IYummyService $yummyService;

    /**
     * Initializes the controller with a new EventRepository instance.
     */
    public function __construct()
    {
        $this->eventRepository = new EventRepository();
        $this->storyEventRepository = new StoryEventRepository();
        $this->yummyService = new YummyService(
            new YummyEventRepository(),
            new ContentService()
        );
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

        // Render the history overview page. Content is embedded directly in the view.
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
        if (isset($_GET['day']) && $_GET['day'] === 'all') {
            header('Location: /events/jazz');
            exit;
        }

        $dayFilter = $_GET['day'] ?? 'all';

        $jazzArtists = $this->eventRepository->getJazzEvents($dayFilter !== 'all' ? $dayFilter : null);

        $contentService = new ContentService();
        $jazzContent = $contentService->getPageContent('jazz');

        require __DIR__ . '/../views/events/jazz/overview.php';
    }

    /**
     * Displays the detail page for a single jazz artist.
     *
     * @param array $vars Route parameters; expects 'id' (int).
     * @return void
     */
    public function jazzDetail($vars = [])
    {
        $id = (int) ($vars['id'] ?? 0);
        $artist = $this->eventRepository->getJazzEventById($id);

        if ($artist === null) {
            http_response_code(404);
            $message = 'Artist not found.';
            require __DIR__ . '/../views/errors/404.php';
            return;
        }

        require __DIR__ . '/../views/events/jazz/detail.php';
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
            // Get filter parameters from GET request
            $dateFilter = $_GET['date'] ?? null;
            $timeFilter = $_GET['time'] ?? null;
            $locationFilter = $_GET['location'] ?? null;

            // Get events based on filters via repository
            if ($dateFilter) {
                $events = $this->storyEventRepository->getEventsByDate($dateFilter);
            } elseif ($timeFilter) {
                $events = $this->storyEventRepository->getEventsByTime($timeFilter);
            } elseif ($locationFilter) {
                $events = $this->storyEventRepository->getEventsByLocation($locationFilter);
            } else {
                $events = $this->storyEventRepository->getEvents();
            }

            // Get additional data via repository
            $featuredStoryteller = $this->storyEventRepository->getFeatured();
            $locations = $this->storyEventRepository->getLocations();

            $contentService = new ContentService();
            $storiesContent = $contentService->getPageContent('stories');

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
     * @return void
     */
    public function yummy(): void
    {
        try {
            $cuisine = $_GET['cuisine'] ?? null;
            $viewModel = $this->yummyService->getOverviewViewModel($cuisine);

            require __DIR__ . '/../views/events/yummy/overview.php';
        } catch (Throwable $e) {
            error_log('Error in yummy controller: ' . $e->getMessage());

            http_response_code(500);
            $message = 'Unable to load the Yummy page.';
            require __DIR__ . '/../views/errors/500.php';
        }
    }

    public function yummyDetail(array $vars = []): void
    {
        try {
            $slug = (string) ($vars['slug'] ?? '');
            $restaurant = $this->yummyService->getRestaurantBySlug($slug);

            if ($restaurant === null) {
                http_response_code(404);
                $message = 'Restaurant not found.';
                require __DIR__ . '/../views/errors/404.php';
                return;
            }

            require __DIR__ . '/../views/events/yummy/detail.php';
        } catch (Throwable $e) {
            error_log('Error in yummyDetail controller: ' . $e->getMessage());

            http_response_code(500);
            $message = 'Unable to load the restaurant page.';
            require __DIR__ . '/../views/errors/500.php';
        }
    }

}
