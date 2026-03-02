<?php
namespace App\Models;

/**
 * CartItem model – a single line item inside a ShoppingCart or Order.
 *
 * Each CartItem wraps a Ticket together with the quantity the user
 * wants to buy and the unit price at the time the item was added.
 * The class implements `__unserialize()` so that cart data stored
 * in the PHP session can be safely restored between requests.
 */
class CartItem
{
    /** @var int Number of tickets of this type in the cart. */
    public int $quantity;

    /** @var float Unit price (in euros) copied from the Ticket at add-time. */
    public float $price;

    /** @var Ticket|null The ticket associated with this line item; null if not yet set. */
    public ?Ticket $ticket = null;

    /**
     * Restore a CartItem from serialised session data.
     *
     * PHP calls this method automatically when `$_SESSION['cart']` is
     * deserialised at the start of a request. Default values protect
     * against missing keys in older session payloads.
     *
     * @param array $data The associative array produced by serialisation.
     */
    public function __unserialize(array $data): void
    {
        $this->quantity = $data['quantity'] ?? 0;
        $this->price = $data['price'] ?? 0.0;
        $this->ticket = $data['ticket'] ?? null;
    }
}