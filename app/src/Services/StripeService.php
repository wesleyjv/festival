<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ShoppingCart;
use Stripe\StripeClient;
use Stripe\Checkout\Session;

class StripeService
{
    private ?StripeClient $stripe = null;
    private bool $configured = false;

    public function __construct()
    {
        $secret = $this->getStripeSecret();
        if ($secret) {
            $this->stripe = new StripeClient($secret);
            $this->configured = true;
        }
    }

    public function isConfigured(): bool
    {
        return $this->configured;
    }

    private function getStripeSecret(): ?string
    {
        $keys = ['STRIPE_SECRET', 'STRIPE_SECRET_KEY', 'STRIPE_API_SECRET', 'STRIPE_KEY', 'STRIPE_PRIVATE'];
        foreach ($keys as $k) {
            $v = getenv($k);
            if ($v !== false && strlen($v) > 0) {
                return $v;
            }
        }
        return null;
    }

    public function createCheckoutSession(ShoppingCart $cart, string $baseUrl, int $userId): Session
    {
        // Convert cart items into Stripe line items and create a checkout session
        if (!$this->configured || !$this->stripe) {
            throw new \RuntimeException('Stripe is not configured. Missing API secret key.');
        }

        $lineItems = [];
        foreach ($cart->items as $item) {
            $name = $item->ticket ? $item->ticket->name : 'Festival Ticket';
            // Convert price to cents (Stripe uses cents)
            $unitAmount = (int) round($item->price * 100);
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => ['name' => $name],
                    'unit_amount' => $unitAmount,
                ],
                'quantity' => $item->quantity,
            ];
        }

        return $this->stripe->checkout->sessions->create([
            'payment_method_types' => ['card', 'ideal', 'paypal'],
            'mode' => 'payment',
            'line_items' => $lineItems,
            'success_url' => $baseUrl . '/checkout/complete?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $baseUrl . '/checkout',
            'metadata' => [
                'user_id' => (string) $userId,
            ],
        ]);
    }

    public function retrieveSession(string $sessionId): Session
    {
        // Fetch the session details from Stripe's API for server-side verification
        if (!$this->configured || !$this->stripe) {
            throw new \RuntimeException('Stripe is not configured. Missing API secret key.');
        }

        return $this->stripe->checkout->sessions->retrieve($sessionId, []);
    }
}
