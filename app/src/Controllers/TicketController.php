<?php

namespace App\Controllers;

use App\Services\TicketService;

class TicketController
{
    private TicketService $ticketService;

    public function __construct()
    {
        $this->ticketService = new TicketService();
    }

    public function index()
    {
        $eventId = $_GET['event_id'] ?? null;
        if (!$eventId) {
            header('Location: /events/history');
            exit;
        }

        $tickets = $this->ticketService->getTicketsByEventId((int)$eventId);

        require __DIR__ . '/../views/tickets/index.php';
    }
}