<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\ShoppingCart;
use App\Services\TicketService;
use App\ViewModels\CartViewModel;

class CartController
{
    private TicketService $ticketService;


    public function __construct(?TicketService $ticketService = null)
    {
        $this->ticketService = $ticketService ?? new TicketService();
    }


    public function add()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ticketId = $_POST['ticket_id'] ?? null;
            $quantity = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;
            $redirect = $_POST['redirect'] ?? null;

            if ($ticketId) {
                // Look up the ticket through the service (includes ID validation)
                $ticket = $this->ticketService->getTicketById((int)$ticketId);

                if ($ticket) {
                    // Retrieve existing cart from session or create a new one
                    $cart = $_SESSION['cart'] ?? new ShoppingCart();
                    $cart->addItem($ticket, $quantity);
                    $_SESSION['cart'] = $cart;
                }
            } else {
                // Fallback: allow adding an ad-hoc ticket by name (used by standalone event ordering pages).
                $ticketName = $_POST['ticket_name'] ?? null;

                // Support combined 'ticket' field (value: "Name|Price") used by the history order form
                // We ignore the price sent by the client for security.
                if (empty($ticketName) && isset($_POST['ticket'])) {
                    $parts = explode('|', (string)$_POST['ticket'], 2);
                    $ticketName = trim($parts[0] ?? '');
                }

                // Map of allowed ad-hoc tickets and their authoritative prices
                $adHocPrices = [
                    'Admission Ticket' => 17.50,
                    'Family Ticket' => 60.00
                ];

                if ($ticketName && isset($adHocPrices[$ticketName])) {
                    $price = $adHocPrices[$ticketName];
                    $ticket = new \App\Models\Ticket();
                    $ticket->id = 0; // synthetic ID for cart-only items
                    $ticket->eventId = 0;
                    $ticket->name = (string)$ticketName;
                    $ticket->price = $price;
                    $ticket->ticketCode = '';

                    $cart = $_SESSION['cart'] ?? new ShoppingCart();
                    $cart->addItem($ticket, $quantity);
                    $_SESSION['cart'] = $cart;
                }
            }

            // If the form set redirect=checkout, send straight to checkout
            if ($redirect === 'checkout') {
                header('Location: /checkout');
                exit;
            }
        }

        header('Location: /cart');
        exit;
    }


    public function remove()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $index = $_POST['item_index'] ?? null;

            if ($index !== null && isset($_SESSION['cart'])) {
                $cart = $_SESSION['cart'];
                if (isset($cart->items[(int)$index])) {
                    // Remove the item at the given index
                    unset($cart->items[(int)$index]);
                    // Re-index the array so keys are sequential (0, 1, 2, …)
                    $cart->items = array_values($cart->items);
                    $_SESSION['cart'] = $cart;
                }
            }
        }

        header('Location: /cart');
        exit;
    }

    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $cart = $_SESSION['cart'] ?? new ShoppingCart();

        $viewModel = new CartViewModel(
            items: $cart->items,
            total: $cart->getTotal(),
        );

        require __DIR__ . '/../views/tickets/cart.php';
    }
}