<?php

namespace App\Services;

use App\Models\Ticket;
use App\Repositories\TicketRepository;

/**
 * Service class responsible for ticket-related business logic.
 */
class TicketService
{
    private TicketRepository $ticketRepository;

    public function __construct()
    {
        $this->ticketRepository = new TicketRepository();
    }

    /**
     * Get all tickets for a given event.
     *
     * @param int $eventId The event identifier.
     * @return Ticket[] An array of Ticket objects.
     */
    public function getTicketsByEventId(int $eventId): array
    {
        return $this->ticketRepository->getByEventId($eventId);
    }

    /**
     * Get a single ticket by its ID.
     *
     * @param int $id The unique identifier of the ticket.
     * @return Ticket|null The corresponding Ticket object, or null if not found.
     */
    public function getTicketById(int $id): ?Ticket
    {
        return $this->ticketRepository->getById($id);
    }
}
