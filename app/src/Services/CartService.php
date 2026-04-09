<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ShoppingCart;
use App\Models\Ticket;
use App\Repositories\TicketRepository;

class CartService
{
    private TicketRepository $ticketRepo;
    private TicketService $ticketService;

    public function __construct(?TicketRepository $ticketRepo = null, ?TicketService $ticketService = null)
    {
        $this->ticketRepo = $ticketRepo ?? new TicketRepository();
        $this->ticketService = $ticketService ?? new TicketService();
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function getCart(): ShoppingCart
    {
        return $_SESSION['cart'] ?? new ShoppingCart();
    }

    private function saveCart(ShoppingCart $cart): void
    {
        $_SESSION['cart'] = $cart;
    }

    public function addStandardTicket(int $ticketId, int $quantity): bool
    {
        $ticket = $this->ticketService->getTicketById($ticketId);
        if (!$ticket) {
            return false;
        }

        $cart = $this->getCart();
        $cart->addItem($ticket, $quantity);
        $this->saveCart($cart);
        return true;
    }

    public function addHistoryTicket(array $data, int $quantity): bool
    {
        $ticketName = $data['ticket_name'] ?? null;
        if (empty($ticketName) && isset($data['ticket'])) {
            $parts = explode('|', (string)$data['ticket'], 2);
            $ticketName = trim($parts[0] ?? '');
        }

        $adHocPrices = [
            'Admission Ticket' => 17.50,
            'Family Ticket' => 60.00
        ];

        if (!$ticketName || !isset($adHocPrices[$ticketName])) {
            return false;
        }

        $date = $data['date'] ?? null;
        $time = $data['time'] ?? null;

        if (!$date || !$time || !$this->validateHistoryCapacity((string)$ticketName, (string)$date, (string)$time, $quantity)) {
            return false;
        }

        $ticket = new Ticket();
        $ticket->id = 0;
        $ticket->eventId = 0;
        $ticket->name = (string)$ticketName;
        $ticket->eventDate = (string)$date;
        $ticket->eventTime = (string)$time;
        $ticket->eventLanguage = (string)($data['language'] ?? '');
        $ticket->price = $adHocPrices[$ticketName];
        $ticket->ticketCode = '';

        $cart = $this->getCart();
        $cart->addItem($ticket, $quantity);
        $this->saveCart($cart);
        return true;
    }

    public function removeItem(int $index): void
    {
        $cart = $this->getCart();
        if (isset($cart->items[$index])) {
            unset($cart->items[$index]);
            $cart->items = array_values($cart->items);
            $this->saveCart($cart);
        }
    }

    private function validateHistoryCapacity(string $ticketName, string $date, string $time, int $quantity): bool
    {
        $sold = $this->ticketRepo->countSoldHistoryTickets($date, $time);
        $requested = ($ticketName === 'Family Ticket') ? ($quantity * 4) : $quantity;
        $limit = 12;

        if (($sold + $requested) > $limit) {
            $_SESSION['cart_error'] = "Sorry, this tour slot is full. Only " . ($limit - $sold) . " spots remaining.";
            return false;
        }

        return true;
    }
}
