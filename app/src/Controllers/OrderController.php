<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enums\PaymentMethod;
use App\Security\Csrf;
use App\Models\ShoppingCart;
use App\Services\MailService;
use App\Services\OrderService;
use App\Services\StripeService;
use App\Services\TicketPdfService;
use App\ViewModels\CheckoutViewModel;
use App\ViewModels\OrderConfirmationViewModel;
use App\ViewModels\OrderHistoryViewModel;
use App\Services\InvoicePdfService;

class OrderController
{
    private OrderService $orderService;
    private TicketPdfService $ticketPdfService;
    private InvoicePdfService $invoicePdfService;
    private MailService $mailService;
    private StripeService $stripeService;

    public function __construct(
        OrderService $orderService,
        TicketPdfService $ticketPdfService,
        MailService $mailService,
        StripeService $stripeService,
        ?InvoicePdfService $invoicePdfService = null
    ) {
        $this->orderService = $orderService;
        $this->ticketPdfService = $ticketPdfService;
        $this->mailService = $mailService;
        $this->stripeService = $stripeService;
        $this->invoicePdfService = $invoicePdfService ?? new InvoicePdfService();
    }


    public function orders(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $orders = $this->orderService->getOrdersByUserId((int) $_SESSION['user_id']);
        $viewModel = new OrderHistoryViewModel($orders);
        require __DIR__ . '/../views/tickets/orders.php';
    }

    public function checkout(): void
    {
        // Display the checkout summary and payment selection
        if (session_status() === PHP_SESSION_NONE) session_start();

        $cart = $_SESSION['cart'] ?? new ShoppingCart();

        if (empty($cart->items)) {
            header('Location: /cart');
            exit;
        }

        $total = $cart->getTotal();
        $error = $_SESSION['checkout_error'] ?? null;
        unset($_SESSION['checkout_error']);

        $viewModel = new CheckoutViewModel(
            items: $cart->items,
            total: $total,
            error: $error,
            isLoggedIn: !empty($_SESSION['user_id']),
            paymentMethods: PaymentMethod::toViewArray(),
        );

        require __DIR__ . '/../views/tickets/checkout.php';
    }

    public function placeOrder(): void
    {
        // Initiate the Stripe checkout session
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!$this->validateCheckoutRequest()) {
            header('Location: /checkout');
            exit;
        }

        $cart = $_SESSION['cart'] ?? new ShoppingCart();
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $baseUrl = $protocol . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');

        try {
            // Create a Stripe session and redirect the user
            $session = $this->stripeService->createCheckoutSession(
                cart: $cart,
                baseUrl: $baseUrl,
                userId: (int) $_SESSION['user_id']
            );

            header('Location: ' . $session->url);
            exit;
        } catch (\Throwable $e) {
            error_log('Stripe Session Creation Error: ' . $e->getMessage());
            $_SESSION['checkout_error'] = 'Unable to start payment session. Please try again.';
            header('Location: /checkout');
            exit;
        }
    }

    private function validateCheckoutRequest(): bool
    {
        if (!Csrf::validateRequest()) {
            $_SESSION['checkout_error'] = 'Invalid session. Please try again.';
            return false;
        }

        if (empty($_SESSION['user_id'])) {
            $_SESSION['checkout_error'] = 'You must be logged in to complete your purchase.';
            return false;
        }

        $cart = $_SESSION['cart'] ?? new ShoppingCart();
        if (empty($cart->items)) {
            header('Location: /cart');
            exit;
        }

        if (!$this->stripeService->isConfigured()) {
            $_SESSION['checkout_error'] = 'Payment provider not configured. Please contact support.';
            return false;
        }

        return true;
    }

    public function completeCheckout(): void
    {
        // Handle return from Stripe and finalize the order
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (empty($_SESSION['user_id'])) {
            $_SESSION['checkout_error'] = 'You must be logged in to complete your purchase.';
            header('Location: /checkout');
            exit;
        }

        $sessionId = $_GET['session_id'] ?? null;
        // Verify the payment status server-side via Stripe API
        $session = $this->verifyStripePayment($sessionId);

        if (!$session) {
            header('Location: /checkout');
            exit;
        }

        $cart = $_SESSION['cart'] ?? new ShoppingCart();
        if (empty($cart->items)) {
            $_SESSION['checkout_error'] = 'Your cart is empty. Nothing to complete.';
            header('Location: /cart');
            exit;
        }

        try {
            // Persist order to database and clear session cart
            $order = $this->orderService->createOrderFromCart($cart, (int) $_SESSION['user_id']);
            unset($_SESSION['cart']);

            // Send tickets and invoice via email
            $this->sendOrderConfirmation($order);

            $_SESSION['last_order_number'] = $order->orderNumber;
            $_SESSION['last_order_total'] = $order->totalAmount;

            header('Location: /checkout/confirmation');
            exit;
        } catch (\Exception $e) {
            error_log('Checkout finalization error: ' . $e->getMessage());
            $_SESSION['checkout_error'] = 'Something went wrong finalizing your order. Please contact support.';
            header('Location: /checkout');
            exit;
        }
    }

    private function verifyStripePayment(?string $sessionId)
    {
        // Server-to-server verification of payment status
        if (!$sessionId) {
            $_SESSION['checkout_error'] = 'Missing payment session information.';
            return null;
        }

        if (!$this->stripeService->isConfigured()) {
            $_SESSION['checkout_error'] = 'Payment provider not configured. Please contact support.';
            return null;
        }

        try {
            $session = $this->stripeService->retrieveSession($sessionId);
            if (!isset($session->payment_status) || $session->payment_status !== 'paid') {
                $_SESSION['checkout_error'] = 'Payment not completed. Please try again.';
                return null;
            }
            return $session;
        } catch (\Throwable $e) {
            error_log('Stripe Session Retrieval Error: ' . $e->getMessage());
            $_SESSION['checkout_error'] = 'Unable to verify payment. Please contact support.';
            return null;
        }
    }

    private function sendOrderConfirmation(\App\Models\Order $order): void
    {
        // Generate PDFs and email them to the user
        $userEmail = $_SESSION['user_email'] ?? null;
        if ($userEmail === null) return;

        $orderWithItems = $this->orderService->getOrderByIdWithItems($order->id);
        if ($orderWithItems === null) return;

        $pdfBytes = $this->ticketPdfService->generatePdf($orderWithItems);
        $invoiceBytes = $this->invoicePdfService->generatePdf($orderWithItems);

        // Mail service now supports multiple attachments (Tickets + Invoice)
        $this->mailService->sendWithAttachment(
            to: $userEmail,
            subject: 'Your Festival Tickets & Invoice - ' . $order->orderNumber,
            body: "Hi,\r\n\r\nThank you for your order!\r\n\r\n"
                . "Order number: " . $order->orderNumber . "\r\n"
                . "Total: EUR " . number_format($order->totalAmount, 2) . "\r\n\r\n"
                . "Your tickets and invoice are attached as PDF files.\r\n\r\n"
                . "See you at the festival!",
            attachments: [
                ['data' => $pdfBytes, 'name' => 'tickets-' . $order->orderNumber . '.pdf'],
                ['data' => $invoiceBytes, 'name' => 'invoice-' . $order->orderNumber . '.pdf'],
            ]
        );
    }

    public function confirmation(): void
    {
        // Display the order confirmation page
        if (session_status() === PHP_SESSION_NONE) session_start();

        $orderNumber = $_SESSION['last_order_number'] ?? null;
        $orderTotal = $_SESSION['last_order_total'] ?? null;

        if (!$orderNumber) {
            header('Location: /');
            exit;
        }

        unset($_SESSION['last_order_number'], $_SESSION['last_order_total']);

        $viewModel = new OrderConfirmationViewModel(
            orderNumber: $orderNumber,
            orderTotal: $orderTotal !== null ? (float) $orderTotal : null,
            userEmail: $_SESSION['user_email'] ?? 'your email',
        );

        require __DIR__ . '/../views/tickets/confirmation.php';
    }

    public function emailTickets(array $vars): void
    {
        // Admin triggered manual re-send of tickets and invoice
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!$this->validateEmailRequest($vars, $order)) {
            header('Location: /orders');
            exit;
        }

        $userEmail = $_SESSION['user_email'];
        $pdf = $this->ticketPdfService->generatePdf($order);
        $invoice = $this->invoicePdfService->generatePdf($order);

        $this->mailService->sendWithAttachment(
            to: $userEmail,
            subject: 'Your Festival Tickets & Invoice - ' . $order->orderNumber,
            body: "Hi ,\r\n\r\nThank you for your order!\r\n\r\n"
                . "Order number: " . $order->orderNumber . "\r\n"
                . "Total: EUR " . number_format($order->totalAmount, 2) . "\r\n\r\n"
                . "Your tickets and invoice are attached as PDF files.\r\n\r\n"
                . "See you at the festival!",
            attachments: [
                ['data' => $pdf, 'name' => 'tickets-' . $order->orderNumber . '.pdf'],
                ['data' => $invoice, 'name' => 'invoice-' . $order->orderNumber . '.pdf'],
            ]
        );

        $_SESSION['email_success'] = 'Tickets and Invoice have been sent to ' . $userEmail;
        header('Location: /orders');
        exit;
    }

    private function validateEmailRequest(array $vars, ?\App\Models\Order &$order): bool
    {
        if (!Csrf::validateRequest()) {
            $_SESSION['email_error'] = 'Invalid session. Please try again.';
            return false;
        }

        if (empty($_SESSION['user_id']) || empty($_SESSION['user_email'])) {
            return false;
        }

        $orderId = (int) ($vars['id'] ?? 0);
        $order = $this->orderService->getOrderByIdWithItems($orderId);

        if (!$order || $order->userId !== (int) $_SESSION['user_id']) {
            return false;
        }

        return true;
    }
}
