<?php

namespace App\Controllers;

use PDO;

use App\Repositories\EventRepository;
use App\Repositories\YummyEventRepository;
use App\Services\ContentService;
use App\Services\StoryEventService;

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
    private StoryEventService $storyEventService;

    /**
     * Initializes the controller with a new EventRepository instance.
     */
    public function __construct()
    {
        $this->eventRepository = new EventRepository();
        $this->storyEventService = new StoryEventService();
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

        $contentService = new ContentService();
        $historyContent = $contentService->getPageContent('history');

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
            echo '404  Artist not found';
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
            // Delegate all storytelling data retrieval to the service layer
            $data = $this->storyEventService->getStoriesOverviewData([
                'day'      => $_GET['day']      ?? null,
                'date'     => $_GET['date']     ?? null,
                'time'     => $_GET['time']     ?? null,
                'location' => $_GET['location'] ?? null,
            ]);

            $events             = $data['events'];
            $allEvents          = $data['allEvents'];
            $featuredStoryteller= $data['featuredStoryteller'];
            $locations          = $data['locations'];

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
    public function yummy()
    {
        $cuisine = $_GET['cuisine'] ?? null;

        $repository = new YummyEventRepository();
        $restaurants = $repository->getAll($cuisine);

        $contentService = new ContentService();
        $yummyContent = $contentService->getPageContent('yummy');

        require __DIR__ . '/../views/events/yummy/overview.php';
    }

}
