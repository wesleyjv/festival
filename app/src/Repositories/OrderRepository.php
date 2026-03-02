<?php

namespace App\Repositories;

use App\DB;
use App\Models\Order;
use App\Models\CartItem;
use App\Models\Ticket;
use PDO;

/**
 * OrderRepository – data-access layer for the `orders` and `order_items` tables.
 *
 * Handles persisting new orders (including their line items within a
 * database transaction), retrieving orders by ID or user, and updating
 * order status. Raw database rows are converted into Order models via
 * `mapRowToOrder()`.
 *
 * Tables:
 *   orders      (id, user_id, order_number, order_date, status, total_amount)
 *   order_items (id, order_id, session_id, quantity, unit_price, vat_rate)
 */
class OrderRepository
{
    /** @var PDO The active database connection. */
    private PDO $db;

    /**
     * Create a new OrderRepository.
     *
     * Obtains a PDO connection from the central DB helper.
     */
    public function __construct()
    {
        $this->db = DB::getConnection();
    }

    /**
     * Persist a new order and all its line items inside a database transaction.
     *
     * 1. Inserts a row into `orders` with the order header data.
     * 2. Iterates over the order’s items and inserts each one into `order_items`.
     * 3. Commits the transaction on success; rolls back on any failure.
     *
     * The Order model’s `id` property is set to the auto-generated primary
     * key after the insert.
     *
     * @param  Order $order  The order to persist (items must already be set).
     * @return int           The newly generated order ID.
     * @throws \Exception    Re-thrown after a rollback when the insert fails.
     */
    public function save(Order $order): int
    {
        $this->db->beginTransaction();

        try {
            // Insert the order header row
            $stmt = $this->db->prepare(
                'INSERT INTO orders (user_id, order_number, order_date, status, total_amount)
                 VALUES (:user_id, :order_number, :order_date, :status, :total_amount)'
            );
            $stmt->execute([
                'user_id'      => $order->userId,
                'order_number' => $order->orderNumber,
                'order_date'   => $order->date->format('Y-m-d H:i:s'),
                'status'       => $order->status,
                'total_amount' => $order->totalAmount,
            ]);

            // Capture the auto-incremented ID for the order
            $orderId = (int) $this->db->lastInsertId();
            $order->id = $orderId;

            
            $itemStmt = $this->db->prepare(
                'INSERT INTO order_items (order_id, session_id, quantity, unit_price, vat_rate)
                 VALUES (:order_id, :session_id, :quantity, :unit_price, :vat_rate)'
            );

            foreach ($order->items as $item) {
                $itemStmt->execute([
                    'order_id'   => $orderId,
                    'session_id' => $item->ticket ? $item->ticket->id : null,
                    'quantity'   => $item->quantity,
                    'unit_price' => $item->price,
                    'vat_rate'   => 21.00,  
                ]);
            }

            $this->db->commit();
            return $orderId;
        } catch (\Exception $e) {
            // Roll back the entire transaction so the database stays consistent
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Find an order by its primary key.
     *
     * @param  int        $id  The order’s primary key.
     * @return Order|null      The Order model, or null when not found.
     */
    public function findById(int $id): ?Order
    {
        $stmt = $this->db->prepare('SELECT * FROM orders WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return $this->mapRowToOrder($row);
    }

    /**
     * Find all orders belonging to a given user, newest first.
     *
     * @param  int     $userId  The user’s primary key.
     * @return Order[]          An array of Order models (may be empty).
     */
    public function findByUserId(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM orders WHERE user_id = :user_id ORDER BY order_date DESC');
        $stmt->execute(['user_id' => $userId]);

        $orders = [];
        foreach ($stmt as $row) {
            $orders[] = $this->mapRowToOrder($row);
        }
        return $orders;
    }

    /**
     * Update the status column of an existing order.
     *
     * @param int    $orderId  The order’s primary key.
     * @param string $status   The new status value (e.g. “paid”, “cancelled”).
     */
    public function updateStatus(int $orderId, string $status): void
    {
        $stmt = $this->db->prepare('UPDATE orders SET status = :status WHERE id = :id');
        $stmt->execute(['status' => $status, 'id' => $orderId]);
    }

    /**
     * Find an order by its primary key and eagerly load its line items with tickets.
     *
     * Joins `order_items` with `tickets` (via session_id) so that each
     * CartItem is fully hydrated with its associated Ticket model.
     *
     * @param  int        $id  The order's primary key.
     * @return Order|null      The Order model with items populated, or null when not found.
     */
    public function findByIdWithItems(int $id): ?Order
    {
        $stmt = $this->db->prepare('SELECT * FROM orders WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        $order = $this->mapRowToOrder($row);

        // Load line items joined with their tickets
        $itemStmt = $this->db->prepare(
            'SELECT oi.*, t.id AS ticket_id, t.event_id, t.name AS ticket_name,
                    t.price AS ticket_price, t.ticket_code, t.qr_code_path,
                    t.is_scanned, t.scanned_at
             FROM order_items oi
             LEFT JOIN tickets t ON t.id = oi.session_id
             WHERE oi.order_id = :order_id'
        );
        $itemStmt->execute(['order_id' => $id]);

        $items = [];
        foreach ($itemStmt as $itemRow) {
            $cartItem = new CartItem();
            $cartItem->quantity = (int) $itemRow['quantity'];
            $cartItem->price = (float) $itemRow['unit_price'];

            if (!empty($itemRow['ticket_id'])) {
                $ticket = new Ticket();
                $ticket->id = (int) $itemRow['ticket_id'];
                $ticket->eventId = (int) $itemRow['event_id'];
                $ticket->name = $itemRow['ticket_name'];
                $ticket->price = (float) $itemRow['ticket_price'];
                $ticket->ticketCode = $itemRow['ticket_code'];
                $ticket->qrCodePath = $itemRow['qr_code_path'] ?? null;
                $ticket->isScanned = (bool) $itemRow['is_scanned'];
                $ticket->scannedAt = !empty($itemRow['scanned_at'])
                    ? new \DateTime($itemRow['scanned_at'])
                    : null;
                $cartItem->ticket = $ticket;
            }

            $items[] = $cartItem;
        }

        $order->items = $items;

        return $order;
    }

    /**
     * Convert an associative database row into an Order model.
     *
     * Note: line items (order_items) are NOT loaded here – this maps
     * only the `orders` table columns.
     *
     * @param  array $row  An associative array fetched from the `orders` table.
     * @return Order       A populated Order model instance.
     */
    private function mapRowToOrder(array $row): Order
    {
        $order = new Order();
        $order->id = (int) $row['id'];
        $order->userId = isset($row['user_id']) ? (int) $row['user_id'] : null;
        $order->orderNumber = $row['order_number'];
        $order->totalAmount = (float) $row['total_amount'];
        $order->status = $row['status'];
        $order->date = new \DateTime($row['order_date']);
        return $order;
    }
}
