<?php

namespace App\Services;

use App\Models\Ticket;
use App\Repositories\TicketRepository;

/**
 * TicketService – business-logic layer for ticket operations.
 *
 * Sits between the controllers and the TicketRepository. This service
 * centralises input validation and any future business rules (e.g.
 * availability checks, access control) so that every caller benefits
 * from the same logic regardless of entry point.
 */
class TicketService
{
    private TicketRepository $ticketRepository;


    public function __construct(?TicketRepository $ticketRepository = null)
    {
        $this->ticketRepository = $ticketRepository ?? new TicketRepository();
    }

    public function getTicketsByEventId(int $eventId): array
    {
        if ($eventId <= 0) {
            return [];
        }

        return $this->ticketRepository->getByEventId($eventId);
    }

    public function getTicketById(int $id): ?Ticket
    {
        if ($id <= 0) {
            return null;
        }

        return $this->ticketRepository->getById($id);
    }
}
