<?php
// Session.php
namespace App\Models;

class Session
{
    public int $id;
    public \DateTime $startTime;
    public \DateTime $endTime;
    public float $price;
    public int $totalCapacity;
    public int $bookedCount;

    public function checkAvailability(): int
    {
        return max(0, $this->totalCapacity - $this->bookedCount);
    }
}

