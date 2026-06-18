<?php

declare(strict_types=1);

namespace App\ViewModels;

/**
 * ViewModel for the order confirmation page (GET /checkout/confirmation).
 *
 * Contains the order number, total, and the user's email so the
 * confirmation template has no reason to touch $_SESSION.
 */
final readonly class OrderConfirmationViewModel
{
    public string $orderNumber;

    public ?float $orderTotal;

    public string $userEmail;

    public bool $emailSent;

    public function __construct(string $orderNumber, ?float $orderTotal, string $userEmail, bool $emailSent = false)
    {
        $this->orderNumber = $orderNumber;
        $this->orderTotal = $orderTotal;
        $this->userEmail = $userEmail;
        $this->emailSent = $emailSent;
    }
}
