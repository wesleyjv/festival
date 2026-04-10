<?php

namespace App\Controllers;

use Throwable;

use App\Repositories\EventRepository;
use App\Repositories\StoryEventRepository;
use App\Repositories\YummyEventRepository;
use App\Security\Csrf;
use App\Services\ContentService;
use App\Services\TicketService;
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
    private EventRepository $eventRepository;
    private StoryEventRepository $storyEventRepository;
    private IYummyService $yummyService;
    private ContentService $contentService;

    public function __construct()
    {
        $this->eventRepository = new EventRepository();
        $this->storyEventRepository = new StoryEventRepository();
        $this->contentService = new ContentService();
        $this->yummyService = new YummyService(
            new YummyEventRepository(),
            $this->contentService
        );
    }

    public function history()
    {
        $events = $this->eventRepository->getHistoryEvents();
        require __DIR__ . '/../views/events/history/overview.php';
    }

    public function jazz($vars = [])
    {
        $validDays = ['all', 'thursday', 'friday', 'saturday', 'sunday'];
        $dayFilter = $_GET['day'] ?? 'thursday';
        if (!in_array($dayFilter, $validDays, true)) {
            $dayFilter = 'thursday';
        }

        $jazzArtists  = $this->eventRepository->getJazzEvents(null, 'artist');
        $jazzSchedule = $this->eventRepository->getJazzEvents(null, 'start_time');

        $ticketService = new TicketService();
        $ticketService->syncMissingJazzTickets($jazzArtists);
        $scheduleIds   = array_map(static fn ($e) => $e->eventId, $jazzSchedule);
        $jazzTicketIds = $ticketService->getFirstTicketIdByEventIds($scheduleIds);

        $contentService = new ContentService();
        $jazzContent    = $contentService->getPageContent('jazz');

        require __DIR__ . '/../views/events/jazz/overview.php';
    }

    public function jazzDetail($vars = [])
    {
        $id     = (int) ($vars['id'] ?? 0);
        $artist = $this->eventRepository->getJazzEventById($id);

        if ($artist === null) {
            http_response_code(404);
            $message = 'Artist not found.';
            require __DIR__ . '/../views/errors/404.php';
            return;
        }

        $ticketService   = new TicketService();
        $ticketService->syncMissingJazzTickets([$artist]);
        $jazzCartTickets = $ticketService->getTicketsByEventId($artist->eventId);
        $jazzCartTicket  = $jazzCartTickets[0] ?? null;

        require __DIR__ . '/../views/events/jazz/detail.php';
    }

    public function stories($vars = [])
    {
        try {
            $dayFilter      = $_GET['day']      ?? null;
            $timeFilter     = $_GET['time']     ?? null;
            $locationFilter = $_GET['location'] ?? null;

            if ($dayFilter) {
                $events = $this->storyEventRepository->getEventsByDay($dayFilter);
            } elseif ($timeFilter) {
                $events = $this->storyEventRepository->getEventsByTime($timeFilter);
            } elseif ($locationFilter) {
                $events = $this->storyEventRepository->getEventsByLocation($locationFilter);
            } else {
                $events = $this->storyEventRepository->getEvents();
            }

            $ticketService      = new TicketService();
            $storyTicketIds     = $ticketService->getStoryTicketIdMap($events);
            $featuredStoryteller = $this->storyEventRepository->getFeatured();

            $contentService  = new ContentService();
            $storiesContent  = $contentService->getPageContent('stories');

            require __DIR__ . '/../views/events/stories/overview.php';
        } catch (Throwable $e) {
            error_log('Error in stories controller: ' . $e->getMessage());
            http_response_code(500);
            require __DIR__ . '/../views/events/stories/overview.php';
        }
    }

    /** Passes the optional cuisine filter from the query string to the service and renders the overview. */
    public function displayYummyOverviewPage(): void
    {
        try {
            $cuisine   = $_GET['cuisine'] ?? null;
            $viewModel = $this->yummyService->getOverviewViewModel($cuisine);

            require __DIR__ . '/../views/events/yummy/overview.php';
        } catch (Throwable $e) {
            error_log('Error in displayYummyOverviewPage: ' . $e->getMessage());
            http_response_code(500);
            $message = 'Unable to load the Yummy page.';
            require __DIR__ . '/../views/errors/500.php';
        }
    }

    /** Looks up the restaurant by URL slug; renders 404 when not found or inactive. */
    public function displayRestaurantDetailPage(array $vars = []): void
    {
        try {
            $slug      = (string) ($vars['slug'] ?? '');
            $viewModel = $this->yummyService->getRestaurantDetailViewModel($slug);

            if ($viewModel === null) {
                http_response_code(404);
                $message = 'Restaurant not found.';
                require __DIR__ . '/../views/errors/404.php';
                return;
            }

            require __DIR__ . '/../views/events/yummy/detail.php';
        } catch (Throwable $e) {
            error_log('Error in displayRestaurantDetailPage: ' . $e->getMessage());
            http_response_code(500);
            $message = 'Unable to load the restaurant page.';
            require __DIR__ . '/../views/errors/500.php';
        }
    }

    /**
     * Reads reservation parameters from the query string, validates them via the
     * service, and renders the reservation overview page.
     */
    public function displayReservationOverviewPage(): void
    {
        try {
            if (empty($_GET['restaurant_id']) || empty($_GET['festival_date']) || empty($_GET['session_number'])) {
                header('Location: /events/yummy');
                exit;
            }

            $viewModel = $this->yummyService->buildReservationOverviewViewModel($_GET);

            require __DIR__ . '/../views/events/yummy/reservation-overview.php';
        } catch (Throwable $e) {
            error_log('Error in displayReservationOverviewPage: ' . $e->getMessage());
            http_response_code(500);
            $message = 'Unable to load the reservation overview.';
            require __DIR__ . '/../views/errors/500.php';
        }
    }

    /**
     * Validates CSRF, saves the reservation via the service, stores a summary in
     * session, and redirects to the success page.
     */
    public function confirmReservation(): void
    {
        try {
            if (!Csrf::validateRequest()) {
                header('Location: /events/yummy');
                exit;
            }

            // Build the view model first — this validates all params before touching the DB.
            $viewModel = $this->yummyService->buildReservationOverviewViewModel($_POST);

            $params             = $_POST;
            $params['user_id']  = $_SESSION['user_id'] ?? null;
            $reservationId      = $this->yummyService->saveReservation($params);

            $_SESSION['yummy_reservation_success'] = [
                'reservation_id'        => $reservationId,
                'restaurant_name'       => $viewModel->restaurant->restaurantName,
                'restaurant_slug'       => $viewModel->restaurant->slug,
                'restaurant_image_path' => $viewModel->restaurant->restaurantImagePath,
                'festival_date'         => $viewModel->festivalDate,
                'session_start'         => $viewModel->sessionStartTime,
                'session_end'           => $viewModel->sessionEndTime,
                'adults'                => $viewModel->adults,
                'children'              => $viewModel->children,
                'reservation_fee_cents' => $viewModel->reservationFeeCents,
            ];

            header('Location: /events/yummy/reservation/success');
            exit;
        } catch (Throwable $e) {
            error_log('Error in confirmReservation: ' . $e->getMessage());
            http_response_code(500);
            $message = 'Unable to complete the reservation. Please try again.';
            require __DIR__ . '/../views/errors/500.php';
        }
    }

    /**
     * Reads the reservation summary stored in session after a successful confirmation
     * and renders the success page. Redirects to /events/yummy when no session data exists.
     */
    public function displayReservationSuccessPage(): void
    {
        try {
            $successData = $_SESSION['yummy_reservation_success'] ?? null;

            if ($successData === null) {
                header('Location: /events/yummy');
                exit;
            }

            unset($_SESSION['yummy_reservation_success']);

            require __DIR__ . '/../views/events/yummy/reservation-success.php';
        } catch (Throwable $e) {
            error_log('Error in displayReservationSuccessPage: ' . $e->getMessage());
            header('Location: /events/yummy');
            exit;
        }
    }
}
