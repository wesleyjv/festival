<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Security\Csrf;
use App\Services\CartService;
use App\ViewModels\CartViewModel;

class CartController
{
    private CartService $cartService;

    public function __construct(?CartService $cartService = null)
    {
        $this->cartService = $cartService ?? new CartService();
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ticketId = $_POST['ticket_id'] ?? null;
            $quantity = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;
            $redirect = $_POST['redirect'] ?? null;

            if ($ticketId) {
                $this->cartService->addStandardTicket((int)$ticketId, $quantity);
            } else {
                if (!$this->cartService->addHistoryTicket($_POST, $quantity)) {
                    header('Location: /events/history/order');
                    exit;
                }
            }

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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::validateRequest()) {
                header('Location: /cart');
                exit;
            }

            $index = $_POST['item_index'] ?? null;
            if ($index !== null) {
                $this->cartService->removeItem((int)$index);
            }
        }

        header('Location: /cart');
        exit;
    }

    public function index(): void
    {
        $cart = $this->cartService->getCart();

        $viewModel = new CartViewModel(
            items: $cart->items,
            total: $cart->getTotal(),
        );

        require __DIR__ . '/../views/tickets/cart.php';
    }
}
