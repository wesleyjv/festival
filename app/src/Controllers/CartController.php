<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Security\Csrf;
use App\Services\CartService;
use App\ViewModels\CartViewModel;

class CartController
{
    use HandlesControllerErrors;

    private CartService $cartService;

    public function __construct(?CartService $cartService = null)
    {
        $this->cartService = $cartService ?? new CartService();
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
            $hasHistorySlot = !empty($_POST['date']) && !empty($_POST['time']);

            if ($ticketIdRaw !== null && $ticketIdRaw !== '' && (int) $ticketIdRaw > 0) {
                $added = $this->cartService->addStandardTicket((int) $ticketIdRaw, $quantity);
                if (!$added && $wantsJson) {
                    $this->jsonCartAddResponse(false, 'That ticket could not be found.');
                }
            } elseif ($hasHistorySlot) {
                $added = $this->cartService->addHistoryTicket($_POST, $quantity);
                if (!$added) {
                    if ($wantsJson) {
                        $msg = (string) ($_SESSION['cart_error'] ?? 'Could not add ticket.');
                        $this->jsonCartAddResponse(false, $msg);
                    }
                    header('Location: /events/history/order');
                    exit;
                }
            } else {
                $added = $this->cartService->addAdHocPricedTicket($_POST, $quantity);
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

    public function remove(): void
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!Csrf::validateRequest()) {
                    header('Location: /cart');
                    exit;
                }

                $index = $_POST['item_index'] ?? null;
                if ($index !== null) {
                    $this->cartService->removeItem((int) $index);
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
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $cart = $this->cartService->getCart();

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
