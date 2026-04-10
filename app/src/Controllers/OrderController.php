<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Security\Csrf;
use App\Models\ShoppingCart;
use App\Services\OrderService;
use App\ViewModels\CheckoutViewModel;
use App\ViewModels\OrderConfirmationViewModel;
use App\ViewModels\OrderHistoryViewModel;
use Exception;

class OrderController
{
    use HandlesControllerErrors;

    // Service responsible for order, payment, and document operations.
    private OrderService $orderService;

    public function __construct(OrderService $orderService) {
        $this->orderService = $orderService;
    }

    public function orders(): void
    {
        try {
        // Ensure session exists and require login to view order history.
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $orders = $this->orderService->getOrdersByUserId((int) $_SESSION['user_id']);
        $viewModel = new OrderHistoryViewModel($orders);
        require __DIR__ . '/../views/tickets/orders.php';
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    public function checkout(): void
    {
        try {
        // Load current cart and render checkout page.
        if (session_status() === PHP_SESSION_NONE) session_start();

        $cart = $_SESSION['cart'] ?? new ShoppingCart();
        if (empty($cart->items)) {
            header('Location: /cart');
            exit;
        }

        $viewModel = new CheckoutViewModel(
            items: $cart->items,
            total: $cart->getTotal(),
            error: $_SESSION['checkout_error'] ?? null,
            isLoggedIn: !empty($_SESSION['user_id']),
        );
        unset($_SESSION['checkout_error']);

        require __DIR__ . '/../views/tickets/checkout.php';
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    public function placeOrder(): void
    {
        try {
        // Validate request and start Stripe checkout flow.
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!$this->validateRequest()) {
            header('Location: /checkout');
            exit;
        }

        $cart = $_SESSION['cart'] ?? new ShoppingCart();
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $baseUrl = $protocol . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');

        try {
            // Create Stripe session and redirect customer to hosted payment page.
            $url = $this->orderService->initiateStripeSession($cart, $baseUrl, (int) $_SESSION['user_id']);
            header('Location: ' . $url);
            exit;
        } catch (Exception $e) {
            $_SESSION['checkout_error'] = $e->getMessage();
            header('Location: /checkout');
            exit;
        }
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    public function completeCheckout(): void
    {
        try {
        // Verify Stripe result, create order, clear cart, and send documents.
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (empty($_SESSION['user_id'])) {
            $_SESSION['checkout_error'] = 'You must be logged in to complete your purchase.';
            header('Location: /checkout');
            exit;
        }

        $cart = $_SESSION['cart'] ?? new ShoppingCart();
        $sessionId = $_GET['session_id'] ?? null;

        try {
            $order = $this->orderService->finalizeStripeOrder($sessionId, $cart, (int) $_SESSION['user_id']);
            unset($_SESSION['cart']);

            $this->orderService->sendOrderDocuments($order->id, (string) $_SESSION['user_email']);

            $_SESSION['last_order_number'] = $order->orderNumber;
            $_SESSION['last_order_total'] = $order->totalAmount;

            header('Location: /checkout/confirmation');
            exit;
        } catch (Exception $e) {
            $_SESSION['checkout_error'] = $e->getMessage();
            header('Location: /checkout');
            exit;
        }
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    public function confirmation(): void
    {
        try {
        // Render confirmation page using last completed order details.
        if (session_status() === PHP_SESSION_NONE) session_start();

        $orderNumber = $_SESSION['last_order_number'] ?? null;
        if (!$orderNumber) {
            header('Location: /');
            exit;
        }

        $viewModel = new OrderConfirmationViewModel(
            orderNumber: $orderNumber,
            orderTotal: (float) ($_SESSION['last_order_total'] ?? 0),
            userEmail: $_SESSION['user_email'] ?? 'your email',
        );
        unset($_SESSION['last_order_number'], $_SESSION['last_order_total']);

        require __DIR__ . '/../views/tickets/confirmation.php';
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    public function emailTickets(array $vars): void
    {
        try {
        // Re-send ticket and invoice for a user-owned order.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!Csrf::validateRequest() || empty($_SESSION['user_id']) || empty($_SESSION['user_email'])) {
            header('Location: /orders');
            exit;
        }

        $orderId = (int) ($vars['id'] ?? 0);
        $order = $this->orderService->getOrderByIdWithItems($orderId);

        if ($order && $order->userId === (int) $_SESSION['user_id']) {
            $this->orderService->sendOrderDocuments($order->id, (string) $_SESSION['user_email']);
            $_SESSION['email_success'] = 'Tickets and Invoice have been sent.';
        }

        header('Location: /orders');
        exit;
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    private function validateRequest(): bool
    {
        // Shared request guards for checkout actions.
        if (!Csrf::validateRequest()) {
            $_SESSION['checkout_error'] = 'Invalid session.';
            return false;
        }
        if (empty($_SESSION['user_id'])) {
            $_SESSION['checkout_error'] = 'You must be logged in.';
            return false;
        }
        return true;
    }
}
