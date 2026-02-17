<?php
namespace App\Models;

class CartItem
{
    public int $quantity;
    public float $price;
    public ?Ticket $ticket = null;

    public function __unserialize(array $data): void
    {
        $this->quantity = $data['quantity'] ?? 0;
        $this->price = $data['price'] ?? 0.0;
        $this->ticket = $data['ticket'] ?? null;
    }
}