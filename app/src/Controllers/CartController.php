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
            $this->ensureSession();
            $wantsJson = $this->isAjaxRequest();

            $this->validateRequest($wantsJson);

            $ticketIdRaw = $_POST['ticket_id'] ?? null;
            $quantity = isset($_POST['quantity']) ? max(1, (int) $_POST['quantity']) : 1;
            $hasHistorySlot = !empty($_POST['date']) && !empty($_POST['time']);

            $added = false;
            if ($ticketIdRaw !== null && $ticketIdRaw !== '' && (int) $ticketIdRaw > 0) {
                $added = $this->addStandardTicket((int) $ticketIdRaw, $quantity, $wantsJson);
            } elseif ($hasHistorySlot) {
                $added = $this->addHistoryTicket($quantity, $wantsJson);
            } else {
                $added = $this->cartService->addAdHocPricedTicket($_POST, $quantity);
            }

            $this->finalizeResponse($added, $wantsJson);
        } catch (\Throwable $e) {
            $this->handleException($e);
        }
    }

    private function ensureSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function isAjaxRequest(): bool
    {
        return isset($_POST['ajax']) && (string) $_POST['ajax'] === '1';
    }

    private function validateRequest(bool $wantsJson): void
    {
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
    }

    private function addStandardTicket(int $ticketId, int $quantity, bool $wantsJson): bool
    {
        $added = $this->cartService->addStandardTicket($ticketId, $quantity);
        if (!$added && $wantsJson) {
            $this->jsonCartAddResponse(false, 'That ticket could not be found.');
        }
        return $added;
    }

    private function addHistoryTicket(int $quantity, bool $wantsJson): bool
    {
        $added = $this->cartService->addHistoryTicket($_POST, $quantity);
        if (!$added) {
            if ($wantsJson) {
                $msg = (string) ($_SESSION['cart_error'] ?? 'Could not add ticket.');
                $this->jsonCartAddResponse(false, $msg);
            }
            header('Location: /events/history/order');
            exit;
        }
        return $added;
    }

    private function finalizeResponse(bool $added, bool $wantsJson): void
    {
        if ($wantsJson) {
            if ($added) {
                $this->jsonCartAddResponse(true, 'Added to your cart.');
            }
            $this->jsonCartAddResponse(false, 'Could not add ticket.');
        }

        $redirect = $_POST['redirect'] ?? null;
        if ($added && $redirect === 'checkout') {
            header('Location: /checkout');
            exit;
        }

        header('Location: /cart');
        exit;
    }

    private function handleException(\Throwable $e): void
    {
        $this->logControllerThrowable($e);
        if ($this->isAjaxRequest()) {
            $this->respondWithServerError(true, ['ok' => false, 'message' => 'Something went wrong.']);
        }
        $this->respondWithServerError();
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
