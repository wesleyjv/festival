<?php

declare(strict_types=1);

namespace App\ViewModels;

use App\Models\CartItem;

/**
 * ViewModel for the shopping cart page (GET /cart).
 *
 * Provides the view with a pre-calculated total and an isEmpty flag
 * so the template contains no business logic.
 */
final readonly class CartViewModel
{
    /** @var CartItem[] */
    public array $items;

    public float $total;

    public bool $isEmpty;

    /**
     * @param CartItem[] $items The line items currently in the cart.
     * @param float      $total The pre-calculated grand total.
     */
    public function __construct(array $items, float $total)
    {
        $this->items = $items;
        $this->total = $total;
        $this->isEmpty = empty($items);
    }
}
