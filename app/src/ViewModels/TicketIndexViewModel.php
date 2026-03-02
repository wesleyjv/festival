<?php

declare(strict_types=1);

namespace App\ViewModels;

use App\Models\Ticket;

/**
 * ViewModel for the ticket listing page (GET /tickets).
 *
 * Encapsulates the data required by the tickets/index view so the
 * template never accesses domain models or session state directly.
 */
final readonly class TicketIndexViewModel
{
    /** @var Ticket[] */
    public array $tickets;

    public int $ticketCount;

    /**
     * @param Ticket[] $tickets The tickets available for the event.
     */
    public function __construct(array $tickets)
    {
        $this->tickets = $tickets;
        $this->ticketCount = count($tickets);
    }
}
