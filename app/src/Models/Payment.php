<?php
// Payment.php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;

class Payment
{
    public string $transactionId;
    public \DateTimeInterface $paymentDate;
    public float $amount;
    public PaymentMethod $method;
    public PaymentStatus $status = PaymentStatus::PENDING;

    public function processTransaction(): void
    {
        // Payment gateway integration would go here.
    }
}

