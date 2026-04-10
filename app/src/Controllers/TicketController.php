<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\TicketService;
use App\ViewModels\TicketIndexViewModel;

class TicketController
{
    use HandlesControllerErrors;

    private TicketService $ticketService;

    public function __construct(?TicketService $ticketService = null)
    {
        $this->ticketService = $ticketService ?? new TicketService();
    }

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