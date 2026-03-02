<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;

/**
 * Payment model – represents a payment transaction linked to an order.
 *
 * Created by `Order::createPayment()` with the order total pre-filled.
 * The caller sets the payment method and invokes `processTransaction()`
 * to communicate with the payment gateway.
 */
class Payment
{
    /** @var string Unique transaction identifier returned by the payment gateway. */
    public string $transactionId;

    /** @var \DateTimeInterface The date and time the payment was processed. */
    public \DateTimeInterface $paymentDate;

    /** @var float The payment amount in euros. */
    public float $amount;

    /** @var PaymentMethod The method used to pay (e.g. iDEAL, credit card, PayPal). */
    public PaymentMethod $method;

    /** @var PaymentStatus Current status of the payment; defaults to PENDING. */
    public PaymentStatus $status = PaymentStatus::PENDING;

    /**
     * Process the payment through the configured payment gateway.
     *
     * This is a placeholder – a real implementation would call an
     * external API (e.g. Mollie, Stripe) and update `$status` and
     * `$transactionId` based on the response.
     */
    public function processTransaction(): void
    {
        // Payment gateway integration would go here.
    }
}

