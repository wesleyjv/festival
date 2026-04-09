<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\ShoppingCart;
use App\Security\Csrf;
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
        // Start session if not already active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ticketId = $_POST['ticket_id'] ?? null;
            $quantity = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;
            $redirect = $_POST['redirect'] ?? null;

            if ($ticketId) {
                // Add a standard ticket to the cart
                $this->handleStandardTicket((int)$ticketId, $quantity);
            } else {
                // Add an ad-hoc ticket (History tour) to the cart
                $this->handleAdHocTicket($_POST, $quantity);
            }

            if ($redirect === 'checkout') {
                header('Location: /checkout');
                exit;
            }
        }

        header('Location: /cart');
        exit;
    }

    private function handleStandardTicket(int $ticketId, int $quantity): void
    {
        $ticket = $this->ticketService->getTicketById($ticketId);
        if ($ticket) {
            $cart = $_SESSION['cart'] ?? new ShoppingCart();
            $cart->addItem($ticket, $quantity);
            $_SESSION['cart'] = $cart;
        }
    }

    private function handleAdHocTicket(array $data, int $quantity): void
    {
        $ticketName = $data['ticket_name'] ?? null;

        // Parse ticket name from combined POST data if necessary
        if (empty($ticketName) && isset($data['ticket'])) {
            $parts = explode('|', (string)$data['ticket'], 2);
            $ticketName = trim($parts[0] ?? '');
        }

        $adHocPrices = [
            'Admission Ticket' => 17.50,
            'Family Ticket' => 60.00
        ];

        if ($ticketName && isset($adHocPrices[$ticketName])) {
            $price = $adHocPrices[$ticketName];
            $date = $data['date'] ?? null;
            $time = $data['time'] ?? null;
            
            // Validate capacity before adding to cart (max 12 people per tour)
            if ($date && $time && !$this->validateHistoryCapacity((string)$ticketName, (string)$date, (string)$time, $quantity)) {
                return;
            }

            // Create a synthetic ticket model for the history tour
            $ticket = new \App\Models\Ticket();
            $ticket->id = 0;
            $ticket->eventId = 0;
            $ticket->name = (string)$ticketName;
            $ticket->eventDate = (string)$date;
            $ticket->eventTime = (string)$time;
            $ticket->eventLanguage = (string)($data['language'] ?? '');
            $ticket->price = $price;
            $ticket->ticketCode = '';

            $cart = $_SESSION['cart'] ?? new ShoppingCart();
            $cart->addItem($ticket, $quantity);
            $_SESSION['cart'] = $cart;
        }
    }

    private function validateHistoryCapacity(string $ticketName, string $date, string $time, int $quantity): bool
    {
        // Query repository to check sold tickets for the specific slot
        $ticketRepo = new \App\Repositories\TicketRepository();
        $sold = $ticketRepo->countSoldHistoryTickets($date, $time);
        $requested = ($ticketName === 'Family Ticket') ? ($quantity * 4) : $quantity;
        $limit = 12;

        if (($sold + $requested) > $limit) {
            $_SESSION['cart_error'] = "Sorry, this tour slot is full. Only " . ($limit - $sold) . " spots remaining.";
            header('Location: /events/history/order');
            exit;
        }

        return true;
    }

    public function remove()
    {
        // Handle item removal from the cart session
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::validateRequest()) {
                header('Location: /cart');
                exit;
            }

            $index = $_POST['item_index'] ?? null;

            if ($index !== null && isset($_SESSION['cart'])) {
                $cart = $_SESSION['cart'];
                if (isset($cart->items[(int)$index])) {
                    // Remove the item at the given index and re-index the array
                    unset($cart->items[(int)$index]);
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
        // Display the shopping cart overview page
        if (session_status() === PHP_SESSION_NONE) session_start();
        $cart = $_SESSION['cart'] ?? new ShoppingCart();

        $viewModel = new CartViewModel(
            items: $cart->items,
            total: $cart->getTotal(),
        );

        require __DIR__ . '/../views/tickets/cart.php';
    }
}
