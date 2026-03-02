<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enums\PaymentMethod;
use App\Models\ShoppingCart;
use App\Services\MailService;
use App\Services\OrderService;
use App\Services\TicketPdfService;
use App\ViewModels\CheckoutViewModel;
use App\ViewModels\OrderConfirmationViewModel;
use App\ViewModels\OrderHistoryViewModel;

class OrderController
{
    private OrderService $orderService;
    private TicketPdfService $ticketPdfService;
    private MailService $mailService;

    public function __construct(
        OrderService $orderService,
        TicketPdfService $ticketPdfService,
        MailService $mailService,
    ) {
        $this->orderService = $orderService;
        $this->ticketPdfService = $ticketPdfService;
        $this->mailService = $mailService;
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
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (empty($_SESSION['user_id'])) {
            $_SESSION['checkout_error'] = 'You must be logged in to complete your purchase.';
            header('Location: /checkout');
            exit;
        }

        $cart = $_SESSION['cart'] ?? new ShoppingCart();

        if (empty($cart->items)) {
            header('Location: /cart');
            exit;
        }

        $paymentMethod = $_POST['payment_method'] ?? null;
        if (!$this->orderService->isValidPaymentMethod($paymentMethod)) {
            $_SESSION['checkout_error'] = 'Please select a valid payment method.';
            header('Location: /checkout');
            exit;
        }

        try {
            $order = $this->orderService->createOrderFromCart($cart, (int) $_SESSION['user_id']);
            unset($_SESSION['cart']);

            // Generate ticket PDF and email it to the user
            $userEmail = $_SESSION['user_email'] ?? null;
            if ($userEmail !== null) {
                $orderWithItems = $this->orderService->getOrderByIdWithItems($order->id);
                if ($orderWithItems !== null) {
                    $pdfBytes = $this->ticketPdfService->generatePdf($orderWithItems);
                    $this->mailService->sendWithAttachment(
                        to: $userEmail,
                        subject: 'Your Festival Tickets - ' . $order->orderNumber,
                        body: "Hi,\r\n\r\nThank you for your order!\r\n\r\n"
                            . "Order number: " . $order->orderNumber . "\r\n"
                            . "Total: EUR " . number_format($order->totalAmount, 2) . "\r\n\r\n"
                            . "Your tickets are attached as a PDF.\r\n\r\n"
                            . "See you at the festival!",
                        attachmentData: $pdfBytes,
                        attachmentName: 'tickets-' . $order->orderNumber . '.pdf',
                    );
                }
            }

            $_SESSION['last_order_number'] = $order->orderNumber;
            $_SESSION['last_order_total'] = $order->totalAmount;

            header('Location: /checkout/confirmation');
            exit;
        } catch (\Exception $e) {
            $_SESSION['checkout_error'] = 'Something went wrong placing your order. Please try again.';
            header('Location: /checkout');
            exit;
        }
    }

    public function confirmation(): void
    {
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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $orderId = (int) ($vars['id'] ?? 0);
        $order = $this->orderService->getOrderByIdWithItems($orderId);

        if (!$order || $order->userId !== (int) $_SESSION['user_id']) {
            http_response_code(404);
            echo '404 - Order Not Found';
            exit;
        }

        $userEmail = $_SESSION['user_email'] ?? null;
        if ($userEmail === null) {
            header('Location: /orders');
            exit;
        }

        $pdf = $this->ticketPdfService->generatePdf($order);

        $this->mailService->sendWithAttachment(
            to: $userEmail,
            subject: 'Your Festival Tickets - ' . $order->orderNumber,
            body: "Hi ,\r\n\r\nThank you for your order!\r\n\r\n"
                . "Order number: " . $order->orderNumber . "\r\n"
                . "Total: EUR " . number_format($order->totalAmount, 2) . "\r\n\r\n"
                . "Your tickets are attached as a PDF.\r\n\r\n"
                . "See you at the festival!",
            attachmentData: $pdf,
            attachmentName: 'tickets-' . $order->orderNumber . '.pdf',
        );

        $_SESSION['email_success'] = 'Tickets have been sent to ' . $userEmail;
        header('Location: /orders');
        exit;
    }
}
