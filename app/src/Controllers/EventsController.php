<?php

namespace App\Controllers;

use PDO;
use Throwable;

use App\Repositories\EventRepository;
use App\Repositories\YummyEventRepository;
use App\Services\ContentService;
use App\Services\StoryEventService;
use App\Services\TicketService;
use App\Services\Interfaces\IYummyService;
use App\Services\YummyService;

/**
 * Controller responsible for handling event-related page requests.
 *
 * Manages the display of overview pages for each event category:
 * History, Jazz, Stories, and Yummy.
 */
class EventsController
{
    use HandlesControllerErrors;

    /**
     * @var EventRepository Repository used to retrieve event data.
     */
    private EventRepository $eventRepository;
    private StoryEventService $storyEventService;
    private IYummyService $yummyService;

    /**
     * Initializes the controller with a new EventRepository instance.
     */
    public function __construct()
    {
        $this->eventRepository = new EventRepository();
        $this->storyEventService = new StoryEventService();
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
        try {
            $events = $this->eventRepository->getHistoryEvents();

            // Render the history overview page. Content is embedded directly in the view.
            require __DIR__ . '/../views/events/history/overview.php';
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }


    /**
     * Displays the jazz events overview page.
     *
     * @param array $vars Optional route parameters passed to the view.
     * @return void
     */
    public function jazz($vars = [])
    {
        try {
        $validDays = ['all', 'thursday', 'friday', 'saturday', 'sunday'];
        $dayFilter = $_GET['day'] ?? 'thursday';
        if (!in_array($dayFilter, $validDays, true)) {
            $dayFilter = 'thursday';
        }

        // Full lineup in the page; day filter is applied in the browser (no reload).
        $jazzArtists = $this->eventRepository->getJazzEvents(null, 'artist');
        $jazzSchedule = $this->eventRepository->getJazzEvents(null, 'start_time');

        $ticketService = new TicketService();
        $ticketService->syncMissingJazzTickets($jazzArtists);
        $scheduleIds = array_map(static fn ($e) => $e->eventId, $jazzSchedule);
        $jazzTicketIds = $ticketService->getFirstTicketIdByEventIds($scheduleIds);

        $contentService = new ContentService();
        $jazzContent = $contentService->getPageContent('jazz');

        $cmsJazzArtist = [];
        $attachHomepageImage = function (array $events) use (&$cmsJazzArtist, $contentService): void {
            foreach ($events as $ev) {
                $id = $ev->eventId;
                if (!isset($cmsJazzArtist[$id])) {
                    $cmsJazzArtist[$id] = $contentService->getPageContent('jazz_' . $id);
                }
                $cmsHp = trim($cmsJazzArtist[$id]['homepage_image'] ?? '');
                if ($cmsHp !== '') {
                    $ev->homepageImage = $cmsHp;
                }
            }
        };
        $attachHomepageImage($jazzArtists);
        $attachHomepageImage($jazzSchedule);

        require __DIR__ . '/../views/events/jazz/overview.php';
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }


    public function jazzDetail($vars = [])
    {
        try {
        $id = (int) ($vars['id'] ?? 0);
        $artist = $this->eventRepository->getJazzEventById($id);

        if ($artist === null) {
            http_response_code(404);
            $message = 'Artist not found.';
            require __DIR__ . '/../views/errors/404.php';
            return;
        }

        $ticketService = new TicketService();
        $ticketService->syncMissingJazzTickets([$artist]);
        $jazzCartTickets = $ticketService->getTicketsByEventId($artist->eventId);
        $jazzCartTicket = $jazzCartTickets[0] ?? null;

        require __DIR__ . '/../views/events/jazz/detail.php';
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
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
            $filters = [
                'day' => $_GET['day'] ?? null,
                'date' => $_GET['date'] ?? null,
                'time' => $_GET['time'] ?? null,
                'location' => $_GET['location'] ?? null,
            ];

            $viewModel = $this->storyEventService->getStoriesOverviewViewModel($filters);
            $events = $viewModel->events;

            $ticketService = new TicketService();
            $storyTicketIds = $ticketService->getStoryTicketIdMap($events);

            // Pass data to view
            require __DIR__ . '/../views/events/stories/overview.php';

        } catch (Throwable $e) {
            error_log('Error in stories controller: ' . $e->getMessage());

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
