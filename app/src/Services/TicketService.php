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
    /** @var TicketRepository The repository used for database access. */
    private TicketRepository $ticketRepository;

    /**
     * Create a new TicketService.
     *
     * Accepts an optional TicketRepository via constructor injection.
     * When no repository is provided (e.g. when the router instantiates
     * the controller with no arguments) a default instance is created
     * automatically. This keeps production wiring simple while still
     * allowing unit tests to inject a mock.
     *
     * @param TicketRepository|null $ticketRepository  Repository instance, or null to use the default.
     */
    public function __construct(?TicketRepository $ticketRepository = null)
    {
        $this->ticketRepository = $ticketRepository ?? new TicketRepository();
    }

    /**
     * Get all tickets that belong to a given event.
     *
     * Validates the event ID before hitting the database – IDs that are
     * zero or negative are rejected immediately to avoid unnecessary queries.
     *
     * @param  int      $eventId  The event’s primary key.
     * @return Ticket[]           An array of Ticket models (empty when the ID is
     *                            invalid or no tickets exist for the event).
     */
    public function getTicketsByEventId(int $eventId): array
    {
        if ($eventId <= 0) {
            return [];
        }

        return $this->ticketRepository->getByEventId($eventId);
    }

    /**
     * Get a single ticket by its primary key.
     *
     * Returns null both when the ID is invalid (<= 0) and when no
     * matching row exists in the database.
     *
     * @param  int         $id  The ticket’s primary key.
     * @return Ticket|null      The Ticket model, or null when not found / invalid.
     */
    public function getTicketById(int $id): ?Ticket
    {
        if ($id <= 0) {
            return null;
        }

        return $this->ticketRepository->getById($id);
    }
}
