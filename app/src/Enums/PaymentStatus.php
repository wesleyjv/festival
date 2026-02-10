<?php
// PaymentStatus.php

namespace App\Enums;

/**
 * Status of a payment transaction.
 */
enum PaymentStatus: string
{
    case PENDING = 'PENDING';
    case SUCCESS = 'SUCCESS';
    case FAILED = 'FAILED';
    case CANCELLED = 'CANCELLED';
}

