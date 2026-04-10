<?php

namespace App\Models;

/**
 * Ticket model – represents a single ticket row from the `tickets` table.
 *
 * Each ticket belongs to an event and can optionally be linked to an order
 * and a user once it has been purchased. A unique ticket code and optional
 * QR-code image path are generated for entry validation.
 */
class Ticket
{
    /** @var int Primary key of the ticket. */
    public int $id;

    /** @var int|null Foreign key to the `orders` table; null when the ticket has not been purchased yet. */
    public ?int $orderId = null;

    /** @var int Foreign key to the `events` table this ticket belongs to. */
    public int $eventId;

    /** @var int|null Foreign key to the `users` table; set when a user purchases the ticket. */
    public ?int $userId = null;

    /** @var string Display name / type of the ticket (e.g. "Early Bird", "VIP"). */
    public string $name;

    public ?string $eventDate = null;
    public ?string $eventTime = null;
    public ?string $eventLanguage = null;

    /** @var float Price of the ticket in euros. */
    public float $price;

    /** @var string Unique alphanumeric code used for ticket verification at the door. */
    public string $ticketCode;

    /** @var string|null File-system path to the generated QR-code image, or null if not yet generated. */
    public ?string $qrCodePath = null;

    /** @var bool Whether this ticket has already been scanned at the event entrance. */
    public bool $isScanned = false;

    /** @var \DateTime|null Timestamp of when the ticket was scanned; null if not yet scanned. */
    public ?\DateTime $scannedAt = null;
}
