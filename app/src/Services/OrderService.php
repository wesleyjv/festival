<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ShoppingCart;
use App\Repositories\OrderRepository;
use Exception;
use Throwable;

class OrderService
{
    private OrderRepository $orderRepository;
    private StripeService $stripeService;
    private MailService $mailService;
    private TicketPdfService $ticketPdfService;
    private InvoicePdfService $invoicePdfService;

    public function __construct(
        OrderRepository $orderRepository,
        StripeService $stripeService,
        MailService $mailService,
        TicketPdfService $ticketPdfService,
        InvoicePdfService $invoicePdfService
    ) {
        $this->orderRepository = $orderRepository;
        $this->stripeService = $stripeService;
        $this->mailService = $mailService;
        $this->ticketPdfService = $ticketPdfService;
        $this->invoicePdfService = $invoicePdfService;
    }

    public function initiateStripeSession(ShoppingCart $cart, string $baseUrl, int $userId): string
    {
        if (!$this->stripeService->isConfigured()) {
            throw new Exception('Payment provider not configured.');
        }

        try {
            $session = $this->stripeService->createCheckoutSession($cart, $baseUrl, $userId);
            return $session->url;
        } catch (Throwable $e) {
            error_log('Stripe Session Creation Error: ' . $e->getMessage());
            throw new Exception('Unable to start payment session.');
        }
    }

    public function finalizeStripeOrder(?string $sessionId, ShoppingCart $cart, int $userId): Order
    {
        if (!$sessionId) {
            throw new Exception('Missing payment session information.');
        }

        try {
            $session = $this->stripeService->retrieveSession($sessionId);
            if (!isset($session->payment_status) || $session->payment_status !== 'paid') {
                throw new Exception('Payment not completed.');
            }
        } catch (Throwable $e) {
            error_log('Stripe Session Retrieval Error: ' . $e->getMessage());
            throw new Exception('Unable to verify payment.');
        }

        return $this->createOrderFromCart($cart, $userId);
    }

    public function createOrderFromCart(ShoppingCart $cart, int $userId): Order
    {
        if (empty($cart->items)) {
            throw new Exception('Cannot create an order from an empty cart.');
        }

        $order = new Order();
        $order->userId = $userId;
        $order->orderNumber = $this->generateOrderNumber();
        $order->date = new \DateTime();
        $order->items = $cart->items;
        $order->calculateTotal();
        $order->status = 'paid';

        $this->orderRepository->save($order);
        return $order;
    }

    public function sendOrderDocuments(int $orderId, string $email): bool
    {
        $order = $this->orderRepository->findByIdWithItems($orderId);
        if (!$order) {
            return false;
        }

        $recipient = filter_var(trim($email), FILTER_VALIDATE_EMAIL)
            ? trim($email)
            : (filter_var((string) ($order->userEmail ?? ''), FILTER_VALIDATE_EMAIL) ?: null);

        if ($recipient === null) {
            error_log('OrderService mail error: no valid recipient email for order #' . $orderId);
            return false;
        }

        $pdfBytes = $this->ticketPdfService->generatePdf($order);
        $invoiceBytes = $this->invoicePdfService->generatePdf($order);

        return $this->mailService->sendWithAttachment(
            to: $recipient,
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

    private function generateOrderNumber(): string
    {
        return 'ORD-' . strtoupper(bin2hex(random_bytes(6)));
    }

    public function getOrderByIdWithItems(int $id): ?Order
    {
        return $this->orderRepository->findByIdWithItems($id);
    }

    public function getOrdersByUserId(int $userId): array
    {
        return $this->orderRepository->findByUserId($userId);
    }

    public function getAllOrders(): array
    {
        return $this->orderRepository->findAll();
    }

    public function getTotalOrdersCount(): int
    {
        return $this->orderRepository->countAll();
    }
}
