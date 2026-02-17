<?php
// Ticket.php
namespace App\Models;

class Ticket
{
    public int $id;
    public ?int $orderId = null;
    public int $eventId;
    public ?int $userId = null;
    public string $name;
    public float $price;
    public string $ticketCode;
    public ?string $qrCodePath = null;
    public bool $isScanned = false;
    public ?\DateTime $scannedAt = null;
}
