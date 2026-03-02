<?php

declare(strict_types=1);

namespace App\ViewModels;

use App\Models\CartItem;

/**
 * ViewModel for the checkout page (GET /checkout).
 *
 * Bundles the order-summary line items, the pre-calculated total,
 * available payment methods, authentication state, and any flash
 * error so the view never reads from $_SESSION or domain objects
 * directly.
 */
final readonly class CheckoutViewModel
{
    /** @var CartItem[] */
    public array $items;

    public float $total;

    public ?string $error;

    public bool $isLoggedIn;

    /**
     * Available payment methods for the form.
     *
     * Each element is an associative array with 'value' and 'label' keys.
     *
     * @var array<int, array{value: string, label: string}>
     */
    public array $paymentMethods;

    /**
     * @param CartItem[]                                      $items          Cart line items.
     * @param float                                           $total          Pre-calculated grand total.
     * @param string|null                                     $error          Flash error message, or null.
     * @param bool                                            $isLoggedIn     Whether the user is authenticated.
     * @param array<int, array{value: string, label: string}> $paymentMethods Available payment options.
     */
    public function __construct(
        array $items,
        float $total,
        ?string $error,
        bool $isLoggedIn,
        array $paymentMethods,
    ) {
        $this->items = $items;
        $this->total = $total;
        $this->error = $error;
        $this->isLoggedIn = $isLoggedIn;
        $this->paymentMethods = $paymentMethods;
    }
}
