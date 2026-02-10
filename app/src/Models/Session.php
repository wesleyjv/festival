<?php
// Session.php
namespace App\Models;

class Session
{
    public int $id;
    public \DateTime $startTime;
    public \DateTime $endTime;
    public int $totalCapacity;
    public int $bookedCount;

    public function checkAvailability(): int
    {
        // Simple availability calculation based on UML fields.
        return max(0, $this->totalCapacity - $this->bookedCount);
    }
}

