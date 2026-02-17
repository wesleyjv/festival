<?php

namespace App\Repositories;

use App\DB;
use App\Models\Ticket;

/**
 * Repository class responsible for retrieving Ticket data from the database.
 */
class TicketRepository
{
    /**
     * Retrieve all tickets for a given event.
     *
     * @param int $eventId The event identifier.
     * @return Ticket[] An array of Ticket objects.
     */
    public function getByEventId(int $eventId): array
    {
        $db = DB::getConnection();
        $stmt = $db->prepare("SELECT * FROM tickets WHERE event_id = :eventId ORDER BY name ASC");
        $stmt->execute(['eventId' => $eventId]);

        $tickets = [];
        foreach ($stmt as $row) {
            $tickets[] = $this->mapRowToTicket($row);
        }

        return $tickets;
    }

    /**
     * Retrieve a single Ticket by its ID.
     *
     * @param int $id The unique identifier of the ticket.
     * @return Ticket|null The corresponding Ticket object, or null if not found.
     */
    public function getById(int $id): ?Ticket
    {
        $db = DB::getConnection();
        $stmt = $db->prepare("SELECT * FROM tickets WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) return null;

        return $this->mapRowToTicket($row);
    }

    private function mapRowToTicket(array $row): Ticket
    {
        $ticket = new Ticket();
        $ticket->id = (int)$row['id'];
        $ticket->orderId = isset($row['order_id']) ? (int)$row['order_id'] : null;
        $ticket->eventId = (int)$row['event_id'];
        $ticket->userId = isset($row['user_id']) ? (int)$row['user_id'] : null;
        $ticket->name = $row['name'];
        $ticket->price = (float)$row['price'];
        $ticket->ticketCode = $row['ticket_code'];
        $ticket->qrCodePath = $row['qr_code_path'] ?? null;
        $ticket->isScanned = (bool)$row['is_scanned'];
        $ticket->scannedAt = !empty($row['scanned_at']) ? new \DateTime($row['scanned_at']) : null;

        return $ticket;
    }
}
