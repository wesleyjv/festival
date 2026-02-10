<?php
// Order.php
namespace App\Models;

class Order
{
    public string $orderNumber;
    public \DateTimeInterface $date;
    public float $totalAmount = 0.0;

    public function calculateTotal(): float
    {
        // Real implementation would sum associated items.
        return $this->totalAmount;
    }

    public function createPayment(): Payment
    {
        $payment = new Payment();
        $payment->amount = $this->calculateTotal();
        return $payment;
    }
}

