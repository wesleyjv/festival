<?php
// ShoppingCart.php
namespace App\Models;

use App\Enums\PassType;

class ShoppingCart
{
    /** @var CartItem[] */
    public array $items = [];

    public function addItem(Session $session, int $qty): void
    {
        // Domain rules for adding items live in a service; keep this simple.
        $item = new CartItem();
        $item->quantity = $qty;
        $item->price = 0.0;
        $this->items[] = $item;
    }

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

    public function addPass(PassType $type): void
    {
        // Adding a pass could adjust cart pricing; details handled elsewhere.
    }

    public function optimizeForPass(): void
    {
        // Logic described in UML: reduce quantity of overlapping tickets.
    }
}
