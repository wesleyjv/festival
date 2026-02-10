<?php
// PaymentMethod.php

namespace App\Enums;

/**
 * Supported payment methods.
 */
enum PaymentMethod: string
{
    case IDEAL = 'IDEAL';
    case CREDIT_CARD = 'CREDIT_CARD';
    case PAYPAL = 'PAYPAL';
}
