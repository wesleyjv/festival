<?php

declare(strict_types=1);

namespace App\ViewModels;

use App\Models\Order;

/**
 * ViewModel for the order history page (GET /orders).
 *
 * Wraps the array of Order models so the view receives a single,
 * typed data object instead of a loose variable.
 */
final readonly class OrderHistoryViewModel
{
    /** @var Order[] */
    public array $orders;

    /**
     * @param Order[] $orders The user's past orders (newest first).
     */
    public function __construct(array $orders)
    {
        $this->orders = $orders;
    }
}
