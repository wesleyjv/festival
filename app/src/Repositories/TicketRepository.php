<?php

namespace App\Repositories;

use App\DB;
use App\Models\Ticket;

class TicketRepository
{


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

    public function getById(int $id): ?Ticket
    {
        $db = DB::getConnection();
        $stmt = $db->prepare("SELECT * FROM tickets WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) return null;

        return $this->mapRowToTicket($row);
    }

    public function getFirstTicketIdByEventIds(array $eventIds): array
    {
        if (empty($eventIds)) {
            return [];
        }

        $db = DB::getConnection();
        // Simple strategy: Group by event_id and pick the MIN(id)
        $placeholders = implode(',', array_fill(0, count($eventIds), '?'));
        $stmt = $db->prepare("SELECT event_id, MIN(id) as ticket_id FROM tickets WHERE event_id IN ($placeholders) GROUP BY event_id");
        $stmt->execute(array_values($eventIds));

        $results = [];
        foreach ($stmt as $row) {
            $results[(int)$row['event_id']] = (int)$row['ticket_id'];
        }

        return $results;
    }

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

    public function insertTicketForEvent(int $eventId, string $name, float $price): int
    {
        $db = DB::getConnection();
        $stmt = $db->prepare(
            "INSERT INTO tickets (event_id, name, price, ticket_code, is_scanned)
             VALUES (:event_id, :name, :price, :ticket_code, 0)"
        );
        $ticketCode = 'TMP-' . strtoupper(bin2hex(random_bytes(4)));
        $stmt->execute([
            'event_id' => $eventId,
            'name' => $name,
            'price' => $price,
            'ticket_code' => $ticketCode
        ]);

        return (int)$db->lastInsertId();
    }

    public function countSoldHistoryTickets(string $date, string $time): int
    {
        // Calculate the total number of spots taken in a specific tour slot
        $db = DB::getConnection();
        // Admission tickets count as 1, Family tickets count as 4 (as they cover up to 4 persons)
        // Only count tickets from paid or pending orders.
        $stmt = $db->prepare(
            "SELECT t.name, oi.quantity 
             FROM tickets t
             JOIN order_items oi ON t.id = oi.session_id
             JOIN orders o ON t.order_id = o.id
             WHERE t.event_date = :date 
               AND t.event_time = :time
               AND o.status IN ('paid', 'pending', 'confirmed')"
        );
        $stmt->execute(['date' => $date, 'time' => $time]);
        
        $total = 0;
        while ($row = $stmt->fetch()) {
            $multiplier = (str_contains((string)$row['name'], 'Family')) ? 4 : 1;
            $total += ($row['quantity'] * $multiplier);
        }
        
        return $total;
    }

    private function mapRowToTicket(array $row): Ticket
    {
        $ticket = new Ticket();
        $ticket->id = (int)$row['id'];
        $ticket->orderId = isset($row['order_id']) ? (int)$row['order_id'] : null;
        $ticket->eventId = (int)$row['event_id'];
        $ticket->userId = isset($row['user_id']) ? (int)$row['user_id'] : null;
        $ticket->name = $row['name'];
        $ticket->eventDate = $row['event_date'] ?? null;
        $ticket->eventTime = $row['event_time'] ?? null;
        $ticket->eventLanguage = $row['event_language'] ?? null;
        $ticket->price = (float)$row['price'];
        $ticket->ticketCode = $row['ticket_code'];
        $ticket->qrCodePath = $row['qr_code_path'] ?? null;
        $ticket->isScanned = (bool)$row['is_scanned'];
        $ticket->scannedAt = !empty($row['scanned_at']) ? new \DateTime($row['scanned_at']) : null;

        return $ticket;
    }
}
