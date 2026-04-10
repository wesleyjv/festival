<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\ShoppingCart;
use App\Security\Csrf;
use App\Services\TicketService;
use App\ViewModels\CartViewModel;

class CartController
{
    use HandlesControllerErrors;

    private TicketService $ticketService;


    public function __construct(?TicketService $ticketService = null)
    {
        $this->ticketService = $ticketService ?? new TicketService();
    }


    public function add(): void
    {
        try {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $wantsJson = isset($_POST['ajax']) && (string) $_POST['ajax'] === '1';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($wantsJson) {
                $this->jsonCartAddResponse(false, 'Invalid request.');
            }
            header('Location: /cart');
            exit;
        }

        if (!Csrf::validateRequest()) {
            if ($wantsJson) {
                $this->jsonCartAddResponse(false, 'Invalid session. Please refresh the page and try again.');
            }
            header('Location: /cart');
            exit;
        }

        $ticketIdRaw = $_POST['ticket_id'] ?? null;
        $quantity = isset($_POST['quantity']) ? max(1, (int) $_POST['quantity']) : 1;
        $redirect = $_POST['redirect'] ?? null;

        $added = false;

        if ($ticketIdRaw !== null && $ticketIdRaw !== '' && (int) $ticketIdRaw > 0) {
            $ticket = $this->ticketService->getTicketById((int) $ticketIdRaw);
            if ($ticket !== null) {
                $cart = $_SESSION['cart'] ?? new ShoppingCart();
                $cart->addItem($ticket, $quantity);
                $_SESSION['cart'] = $cart;
                $added = true;
            } elseif ($wantsJson) {
                $this->jsonCartAddResponse(false, 'That ticket could not be found.');
            }
        } else {
            // Fallback: ad-hoc ticket by name (standalone event pages). Prices are server-side only.
            $ticketName = $_POST['ticket_name'] ?? null;

            if (empty($ticketName) && isset($_POST['ticket'])) {
                $parts = explode('|', (string) $_POST['ticket'], 2);
                $ticketName = trim($parts[0] ?? '');
            }

            $adHocPrices = [
                'Admission Ticket' => 17.50,
                'Family Ticket' => 60.00,
            ];

            if (is_string($ticketName) && $ticketName !== '' && isset($adHocPrices[$ticketName])) {
                $price = $adHocPrices[$ticketName];
                $ticket = new \App\Models\Ticket();
                $ticket->id = 0;
                $ticket->eventId = 0;
                $ticket->name = $ticketName;
                $ticket->price = $price;
                $ticket->ticketCode = '';

                $cart = $_SESSION['cart'] ?? new ShoppingCart();
                $cart->addItem($ticket, $quantity);
                $_SESSION['cart'] = $cart;
                $added = true;
            }
        }

        if ($wantsJson) {
            if ($added) {
                $this->jsonCartAddResponse(true, 'Added to your cart.');
            }
            $this->jsonCartAddResponse(false, 'Could not add ticket.');
        }

        if ($added && $redirect === 'checkout') {
            header('Location: /checkout');
            exit;
        }

        header('Location: /cart');
        exit;
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $wantsJson = isset($_POST['ajax']) && (string) $_POST['ajax'] === '1';
            if ($wantsJson) {
                $this->respondWithServerError(true, ['ok' => false, 'message' => 'Something went wrong.']);
            }
            $this->respondWithServerError();
        }
    }

    /**
     * JSON body for AJAX add-to-cart (see footer.js-cart-add-form handler).
     */
    private function jsonCartAddResponse(bool $ok, string $message): void
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => $ok, 'message' => $message], JSON_THROW_ON_ERROR);
        exit;
    }


    public function remove()
    {
        try {
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
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    public function index(): void
    {
        try {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $cart = $_SESSION['cart'] ?? new ShoppingCart();

        $viewModel = new CartViewModel(
            items: $cart->items,
            total: $cart->getTotal(),
        );

        require __DIR__ . '/../views/tickets/cart.php';
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }
}