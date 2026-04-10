<?php

declare(strict_types=1);

namespace App\ViewModels;

use App\Models\CartItem;

/**
 * ViewModel for the checkout page (GET /checkout).
 *
 * Bundles the order-summary line items, the pre-calculated total,
 * authentication state, and any flash error so the view never reads
 * from $_SESSION or domain objects directly. Payment method is chosen
 * on the Stripe-hosted page after this step.
 */
final readonly class CheckoutViewModel
{
    /** @var CartItem[] */
    public array $items;

    public float $total;

    public ?string $error;

    public bool $isLoggedIn;

    /**
     * @param CartItem[]  $items      Cart line items.
     * @param float       $total      Pre-calculated grand total.
     * @param string|null $error      Flash error message, or null.
     * @param bool        $isLoggedIn Whether the user is authenticated.
     */
    public function __construct(
        array $items,
        float $total,
        ?string $error,
        bool $isLoggedIn,
    ) {
        $this->items = $items;
        $this->total = $total;
        $this->error = $error;
        $this->isLoggedIn = $isLoggedIn;
    }
}
