<?php

namespace App\Models;

/**
 * Order model – represents a customer order.
 *
 * An order is created from a ShoppingCart when the user completes checkout.
 * It holds the order header (number, date, status, total) and an array of
 * CartItem line items. The model also provides helper methods for
 * calculating the total and creating a Payment.
 */
class Order
{
    /** @var int|null Auto-generated primary key; null until the order is persisted. */
    public ?int $id = null;

    /** @var int|null Foreign key to the `users` table. */
    public ?int $userId = null;

    /** @var string Unique, human-readable order number (e.g. "ORD-A3F1B2C9D4E5"). */
    public string $orderNumber;

    /** @var \DateTimeInterface The date and time the order was placed. */
    public \DateTimeInterface $date;

    /** @var float Grand total of the order in euros. */
    public float $totalAmount = 0.0;

    /** @var string Current order status (e.g. "pending", "paid", "cancelled"). */
    public string $status = 'pending';

    /** @var CartItem[] The line items that make up this order. */
    public array $items = [];

    /**
     * Recalculate the order total from its line items.
     *
     * Iterates over every CartItem, multiplying unit price by quantity,
     * and stores the sum in `$this->totalAmount`.
     *
     * @return float The newly calculated total.
     */
    public function calculateTotal(): float
    {
        $total = 0.0;
        foreach ($this->items as $item) {
            $total += $item->price * $item->quantity;
        }
        $this->totalAmount = $total;
        return $this->totalAmount;
    }

    /**
     * Create a Payment object pre-filled with this order’s total.
     *
     * The caller is responsible for setting the payment method and
     * processing the transaction.
     *
     * @return Payment A new Payment with the amount set.
     */
    public function createPayment(): Payment
    {
        $payment = new Payment();
        $payment->amount = $this->calculateTotal();
        return $payment;
    }
}

