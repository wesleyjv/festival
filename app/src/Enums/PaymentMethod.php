<?php

namespace App\Enums;

/**
 * Supported payment methods.
 */
enum PaymentMethod: string
{
    case IDEAL = 'IDEAL';
    case CREDIT_CARD = 'CREDIT_CARD';
    case PAYPAL = 'PAYPAL';

    /**
     * Human-readable label for display in views.
     */
    public function label(): string
    {
        return match ($this) {
            self::IDEAL => 'iDEAL',
            self::CREDIT_CARD => 'Credit Card',
            self::PAYPAL => 'PayPal',
        };
    }
}
