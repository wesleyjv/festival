<?php

namespace App\Services;

use App\Models\JazzEvent;
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
     * @param  int[]       $eventIds
     * @return array<int,int>  event_id => first ticket id for that event
     */
    public function getFirstTicketIdByEventIds(array $eventIds): array
    {
        return $this->ticketRepository->getFirstTicketIdByEventIds($eventIds);
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

    /**
     * Ensure every jazz event has at least one ticket row so cart / checkout work.
     *
     * @param JazzEvent[] $jazzEvents
     */
    public function syncMissingJazzTickets(array $jazzEvents): void
    {
        foreach ($jazzEvents as $ev) {
            if (!$ev instanceof JazzEvent || $ev->eventId <= 0) {
                continue;
            }
            $existing = $this->ticketRepository->getByEventId($ev->eventId);
            if ($existing !== []) {
                continue;
            }
            $price = $ev->price !== null ? (float) $ev->price : 0.0;
            $name = 'Admission — ' . $ev->artist;
            $this->ticketRepository->insertTicketForEvent($ev->eventId, $name, $price);
        }
    }

    /**
     * Ensure every story event has at least one ticket row so cart / checkout work.
     *
     * @param array<int,array<string,mixed>> $storyEvents
     */
    public function syncMissingStoryTickets(array $storyEvents): void
    {
        foreach ($storyEvents as $event) {
            $eventId = (int) ($event['id'] ?? 0);
            if ($eventId <= 0) {
                continue;
            }

            $existing = $this->ticketRepository->getByEventId($eventId);
            if ($existing !== []) {
                continue;
            }

            $rawPrice = $event['price'] ?? 0;
            if (is_numeric($rawPrice)) {
                $price = (float) $rawPrice;
            } else {
                $normalized = str_replace(',', '.', (string) $rawPrice);
                $price = is_numeric($normalized) ? (float) $normalized : 0.0;
            }

            $title = trim((string) ($event['title'] ?? 'Story Session'));
            if ($title === '') {
                $title = 'Story Session';
            }
            $name = 'Storytelling — ' . $title;

            $this->ticketRepository->insertTicketForEvent($eventId, $name, $price);
        }
    }

    /**
     * Resolve a Storytelling ticket per story event.
     *
     * If an event id already has tickets from another module (id collisions across
     * event tables), we pick/create a story-specific ticket by name prefix.
     *
     * @param array<int,array<string,mixed>> $storyEvents
     * @return array<int,int> event_id => story ticket id
     */
    public function getStoryTicketIdMap(array $storyEvents): array
    {
        $map = [];

        foreach ($storyEvents as $event) {
            $eventId = (int) ($event['id'] ?? 0);
            if ($eventId <= 0) {
                continue;
            }

            $title = trim((string) ($event['title'] ?? 'Story Session'));
            if ($title === '') {
                $title = 'Story Session';
            }

            $existing = $this->ticketRepository->getByEventId($eventId);
            $storyTicket = null;
            foreach ($existing as $ticket) {
                if (str_starts_with((string) $ticket->name, 'Storytelling — ')) {
                    $storyTicket = $ticket;
                    break;
                }
            }

            if ($storyTicket === null) {
                $rawPrice = $event['price'] ?? 0;
                if (is_numeric($rawPrice)) {
                    $price = (float) $rawPrice;
                } else {
                    $normalized = str_replace(',', '.', (string) $rawPrice);
                    $price = is_numeric($normalized) ? (float) $normalized : 0.0;
                }

                $ticketId = $this->ticketRepository->insertTicketForEvent(
                    $eventId,
                    'Storytelling — ' . $title,
                    $price
                );
                $map[$eventId] = $ticketId;
                continue;
            }

            $map[$eventId] = (int) $storyTicket->id;
        }

        return $map;
    }
}
