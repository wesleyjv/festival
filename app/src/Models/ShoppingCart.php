<?php

namespace App\Models;

use App\Enums\PassType;

/**
 * ShoppingCart model – holds the items a user intends to purchase.
 *
 * The cart lives in the PHP session (`$_SESSION['cart']`) and is serialised
 * between requests. It provides methods for adding / removing individual
 * tickets and placeholder hooks for festival-pass logic.
 */
class ShoppingCart
{
    /** @var CartItem[] The list of items currently in the cart. */
    public array $items = [];

    /**
     * Add a ticket to the cart as a new line item.
     *
     * Creates a CartItem from the given Ticket, copies the ticket’s
     * price, and appends it to the items array.
     *
     * @param Ticket $ticket The ticket to add.
     * @param int    $qty    The quantity to add.
     */
    public function addItem(Ticket $ticket, int $qty): void
    {
        $item = new CartItem();
        $item->ticket = $ticket;
        $item->quantity = $qty;
        $item->price = $ticket->price;
        $this->items[] = $item;
    }

    /**
     * Add tickets, merging quantity into an existing line when the same ticket id is already in the cart.
     */
    public function addOrMergeTicket(Ticket $ticket, int $qty): void
    {
        foreach ($this->items as $item) {
            if ($item->ticket->id === $ticket->id) {
                $item->quantity += $qty;
                return;
            }
        }
        $this->addItem($ticket, $qty);
    }

    /**
     * Remove a specific CartItem from the cart by object identity.
     *
     * Searches the items array for the exact same object reference,
     * removes it, and re-indexes the array so there are no gaps.
     *
     * @param CartItem $item The item instance to remove.
     */
    public function removeItem(CartItem $item): void
    {
        foreach ($this->items as $index => $existing) {
            if ($existing === $item) {
                unset($this->items[$index]);
                $this->items = array_values($this->items);
                break;
            }
        }
    }

    /**
     * Calculate the grand total of all items in the cart.
     *
     * @return float The sum of (price × quantity) for every line item.
     */
    public function getTotal(): float
    {
        $total = 0.0;
        foreach ($this->items as $item) {
            $total += $item->price * $item->quantity;
        }
        return $total;
    }

    /**
     * Add a festival pass to the cart (placeholder).
     *
     * @param PassType $type The type of pass to add.
     */
    public function addPass(PassType $type): void
    {
        // Adding a pass could adjust cart pricing; details handled elsewhere.
    }

    /**
     * Optimise the cart when a pass covers individual tickets (placeholder).
     *
     * Would reduce the quantity of overlapping single-event tickets
     * that are already included in the purchased pass.
     */
    public function optimizeForPass(): void
    {
        // Logic described in UML: reduce quantity of overlapping tickets.
    }
}
