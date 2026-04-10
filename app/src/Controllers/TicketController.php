<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\TicketService;
use App\ViewModels\TicketIndexViewModel;

/**
 * TicketController – handles HTTP requests related to tickets.
 *
 * This controller is the entry point for the “/tickets” route. It reads
 * query-string parameters, delegates to the TicketService for data
 * retrieval, and renders the appropriate view template.
 */
class TicketController
{
    use HandlesControllerErrors;

    /** @var TicketService Service used for ticket business logic. */
    private TicketService $ticketService;

    /**
     * Create a new TicketController.
     *
     * Accepts an optional TicketService for dependency injection.
     * When called without arguments (as the router does) a default
     * service instance is created automatically.
     *
     * @param TicketService|null $ticketService  Service instance, or null for the default.
     */
    public function __construct(?TicketService $ticketService = null)
    {
        $this->ticketService = $ticketService ?? new TicketService();
    }

    /**
     * Render order page for the History event.
     *
     * @param array $vars Route variables (unused)
     * @return void
     */
    public function historyTickets(array $vars = []): void
    {
        try {
            require __DIR__ . '/../views/events/history/order.php';
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    public function index(): void
    {
        try {
        // Read the event_id from the query string (?event_id=...)
        $eventId = $_GET['event_id'] ?? null;

        // Redirect when no event_id is provided
        if (!$eventId) {
            header('Location: /events/history');
            exit;
        }

        // Fetch tickets for the given event via the service layer
        $tickets = $this->ticketService->getTicketsByEventId((int)$eventId);

        $viewModel = new TicketIndexViewModel($tickets);

        // Render the tickets overview page
        require __DIR__ . '/../views/tickets/index.php';
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

}