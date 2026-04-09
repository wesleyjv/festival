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

    /**
     * Normalise raw input from QR (e.g. "TICKET-JZ-1-ABC") or manual entry for lookup.
     */
    public static function normalizeTicketCode(string $raw): string
    {
        $s = trim($raw);
        if ($s === '') {
            return '';
        }
        if (preg_match('/^TICKET-/i', $s)) {
            $s = substr($s, strlen('TICKET-'));
        }

        return strtoupper(trim($s));
    }

    /**
     * Validate and record entry: first scan marks the ticket; repeat scan returns a warning.
     *
     * @return array{status:string,message:string,ticket:?\App\Models\Ticket}
     */
    public function scanTicketByCode(string $rawInput): array
    {
        $code = self::normalizeTicketCode($rawInput);
        if ($code === '') {
            return [
                'status' => 'invalid',
                'message' => 'Please enter or scan a ticket code.',
                'ticket' => null,
            ];
        }

        $ticket = $this->ticketRepository->findByTicketCode($code);
        if ($ticket === null) {
            return [
                'status' => 'not_found',
                'message' => 'No ticket found for this code.',
                'ticket' => null,
            ];
        }

        $updated = $this->ticketRepository->markScannedIfNotYet($ticket->id);
        if ($updated === 1) {
            $ticket->isScanned = true;
            $ticket->scannedAt = new \DateTime();

            return [
                'status' => 'success',
                'message' => 'Entry allowed. Ticket marked as scanned.',
                'ticket' => $ticket,
            ];
        }

        return [
            'status' => 'warning',
            'message' => 'Warning: This ticket has already been scanned.',
            'ticket' => $ticket,
        ];
    }
}
