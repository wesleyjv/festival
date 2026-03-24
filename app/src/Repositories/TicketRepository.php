<?php

namespace App\Repositories;

use App\DB;
use App\Models\Ticket;

/**
 * TicketRepository – data-access layer for the `tickets` table.
 *
 * This class is responsible for all direct database interactions related to
 * tickets. It uses PDO prepared statements to prevent SQL injection and
 * converts raw database rows into Ticket model objects via `mapRowToTicket()`.
 *
 * The repository is consumed by TicketService; controllers should never
 * call repository methods directly.
 */
class TicketRepository
{

    /**
     * Retrieve every ticket that belongs to a specific event.
     *
     * Executes a prepared SELECT query filtered by `event_id` and ordered
     * alphabetically by ticket name so the results can be displayed in a
     * consistent order on the front-end.
     *
     * @param  int   $eventId  The event’s primary key.
     * @return Ticket[]        An array of Ticket models (empty when none found).
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
     * For each event id, returns one ticket id (lowest id) when tickets exist.
     *
     * @param  int[]       $eventIds
     * @return array<int,int>  event_id => ticket id
     */
    public function getFirstTicketIdByEventIds(array $eventIds): array
    {
        $eventIds = array_values(array_unique(array_filter(array_map('intval', $eventIds), fn ($id) => $id > 0)));
        if ($eventIds === []) {
            return [];
        }

        $db = DB::getConnection();
        $placeholders = implode(',', array_fill(0, count($eventIds), '?'));
        $stmt = $db->prepare(
            "SELECT event_id, MIN(id) AS tid FROM tickets WHERE event_id IN ($placeholders) GROUP BY event_id"
        );
        $stmt->execute($eventIds);

        $out = [];
        foreach ($stmt as $row) {
            $out[(int) $row['event_id']] = (int) $row['tid'];
        }

        return $out;
    }

    /**
     * Insert a single saleable ticket row for an event (catalog line used by cart / orders).
     *
     * @return int New ticket id
     */
    public function insertTicketForEvent(int $eventId, string $name, float $price): int
    {
        if ($eventId <= 0) {
            throw new \InvalidArgumentException('Invalid event id for ticket.');
        }

        $db = DB::getConnection();
        $ticketCode = 'JZ-' . $eventId . '-' . strtoupper(bin2hex(random_bytes(5)));

        $stmt = $db->prepare(
            'INSERT INTO tickets (event_id, name, price, ticket_code, is_scanned)
             VALUES (:event_id, :name, :price, :ticket_code, 0)'
        );
        $stmt->execute([
            'event_id' => $eventId,
            'name' => $name,
            'price' => $price,
            'ticket_code' => $ticketCode,
        ]);

        return (int) $db->lastInsertId();
    }

    /**
     * Retrieve a single ticket by its primary key.
     *
     * @param  int         $id  The ticket’s primary key.
     * @return Ticket|null      The matching Ticket model, or null when not found.
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

    /**
     * Look up a ticket by its unique ticket_code (case-insensitive).
     */
    public function findByTicketCode(string $ticketCode): ?Ticket
    {
        $ticketCode = trim($ticketCode);
        if ($ticketCode === '') {
            return null;
        }

        $db = DB::getConnection();
        $stmt = $db->prepare('SELECT * FROM tickets WHERE UPPER(ticket_code) = UPPER(:code) LIMIT 1');
        $stmt->execute(['code' => $ticketCode]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return $this->mapRowToTicket($row);
    }

    /**
     * Set is_scanned and scanned_at when the ticket was not yet scanned.
     *
     * @return int Number of rows updated (1 on first scan, 0 if already scanned or missing)
     */
    public function markScannedIfNotYet(int $id): int
    {
        if ($id <= 0) {
            return 0;
        }

        $db = DB::getConnection();
        $stmt = $db->prepare(
            'UPDATE tickets SET is_scanned = 1, scanned_at = NOW() WHERE id = :id AND is_scanned = 0'
        );
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount();
    }

    /**
     * Convert an associative database row into a Ticket model.
     *
     * Handles type-casting (int, float, bool) and nullable columns so that
     * the rest of the application can work with strongly-typed properties.
     *
     * @param  array  $row  An associative array fetched from the `tickets` table.
     * @return Ticket       A fully populated Ticket model instance.
     */
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
