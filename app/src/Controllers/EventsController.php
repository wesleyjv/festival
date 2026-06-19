<?php

namespace App\Controllers;

use Throwable;

use App\Models\ShoppingCart;
use App\Repositories\EventRepository;
use App\Repositories\YummyEventRepository;
use App\Security\Csrf;
use App\Services\ContentService;
use App\Services\StoryEventService;
use App\Services\TicketService;
use App\Services\Interfaces\IStoryEventService;
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

    private EventRepository $eventRepository;
    private IStoryEventService $storyEventService;
    private IYummyService $yummyService;
    private ContentService $contentService;
    private TicketService $ticketService;

    public function __construct()
    {
        $this->eventRepository   = new EventRepository();
        $this->contentService    = new ContentService();
        $this->ticketService     = new TicketService();
        $this->storyEventService = new StoryEventService(
            contentService: $this->contentService
        );
        $this->yummyService      = new YummyService(
            new YummyEventRepository(),
            $this->contentService,
            new TicketService()
        );
    }

    public function history()
    {
        try {
            $events = $this->eventRepository->getHistoryEvents();
            $historyContent = $this->contentService->getPageContent('history');
            require __DIR__ . '/../views/events/history/overview.php';
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    public function jazz($vars = [])
    {
        try {
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
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    public function stories(array $vars = []): void
    {
        try {
            $filters = [
                'day'      => $_GET['day']      ?? null,
                'date'     => $_GET['date']     ?? null,
                'time'     => $_GET['time']     ?? null,
                'location' => $_GET['location'] ?? null,
            ];

            $viewModel      = $this->storyEventService->getStoriesOverviewViewModel($filters);
            $events         = $viewModel->events;

            $storyTicketIds = $this->ticketService->getStoryTicketIdMap($events);

            require __DIR__ . '/../views/events/stories/overview.php';
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    /** Renders the detail page for a single story event; 404 when the id is unknown. */
    public function storyDetail(array $vars = []): void
    {
        try {
            $id    = (int) ($vars['id'] ?? 0);
            $event = $this->storyEventService->getEventDetail($id);

            if ($event === null) {
                http_response_code(404);
                require __DIR__ . '/../views/errors/404.php';
                return;
            }

            $storyTicketIds = $this->ticketService->getStoryTicketIdMap([$event]);
            $ticketId       = $storyTicketIds[$event['id']] ?? null;
            $storiesContent = $this->contentService->getPageContent('stories');

            require __DIR__ . '/../views/events/stories/detail.php';
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
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
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
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
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
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
        } catch (\InvalidArgumentException $e) {
            $this->redirectToRestaurantWithError($_GET['restaurant_id'] ?? null, $e->getMessage());
        } catch (Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    /**
     * Validates CSRF, saves the reservation, creates a template ticket,
     * adds it to the session cart, and redirects to /cart.
     */
    public function confirmReservation(): void
    {
        try {
            if (!Csrf::validateRequest()) {
                header('Location: /events/yummy');
                exit;
            }

            $params            = $_POST;
            $params['user_id'] = $_SESSION['user_id'] ?? null;

            $ticket = $this->yummyService->createAndCartReservation($params);

            $cart = $_SESSION['cart'] ?? new ShoppingCart();
            $cart->addItem($ticket, 1);
            $_SESSION['cart'] = $cart;

            header('Location: /cart');
            exit;
        } catch (\InvalidArgumentException $e) {
            $this->redirectToRestaurantWithError($_POST['restaurant_id'] ?? null, $e->getMessage());
        } catch (Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
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
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    /**
     * GET /api/yummy/availability — returns remaining seats for a restaurant/date/session
     * as JSON, using the same 90% capacity rule enforced by the reservation flow.
     */
    public function apiAvailability(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $restaurantId  = isset($_GET['restaurant_id'])  ? (int) $_GET['restaurant_id']  : 0;
            $sessionNumber = isset($_GET['session_number']) ? (int) $_GET['session_number'] : 0;
            $festivalDate  = $_GET['festival_date'] ?? '';

            $remaining = $this->yummyService->getRemainingSeats($restaurantId, $festivalDate, $sessionNumber);

            echo json_encode([
                'remaining' => $remaining,
                'available' => $remaining > 0,
            ]);
        } catch (\InvalidArgumentException $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        } catch (Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError(true);
        }
    }

    /**
     * Redirects back to the restaurant detail page's reservation section with a
     * friendly error message, used when reservation validation fails (e.g. the
     * session is fully booked).
     */
    private function redirectToRestaurantWithError($restaurantId, string $message): void
    {
        $slug = $restaurantId !== null ? $this->yummyService->getRestaurantSlugById((int) $restaurantId) : null;

        if ($slug === null) {
            header('Location: /events/yummy');
            exit;
        }

        header('Location: /events/yummy/restaurant/' . rawurlencode($slug) . '?error=' . rawurlencode($message) . '#reserve');
        exit;
    }
}
