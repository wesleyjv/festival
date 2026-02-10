<?php
// Ticket.php
namespace App\Models;

class Ticket
{
    public string $ticketCode;
    public string $qrCode;
    public bool $isScanned = false;
}
