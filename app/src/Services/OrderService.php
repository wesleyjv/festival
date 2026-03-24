<?php

namespace App\Services;

use App\Enums\PaymentMethod;
use App\Models\Order;
use App\Models\ShoppingCart;
use App\Repositories\OrderRepository;

/**
 * OrderService – business-logic layer for order operations.
 *
 * Encapsulates the rules for creating, retrieving, and managing orders.
 * The service sits between the controllers and the OrderRepository so
 * that business logic (e.g. generating order numbers, validating carts)
 * is not duplicated across multiple entry points.
 */
class OrderService
{
    /** @var OrderRepository The repository used for order persistence. */
    private OrderRepository $orderRepository;

    /**
     * Create a new OrderService.
     *
     * @param OrderRepository $orderRepository The repository used for order persistence.
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Validate that the given string is a supported payment method.
     *
     * @param  string|null $method The payment method string to validate.
     * @return bool True when valid, false otherwise.
     */
    public function isValidPaymentMethod(?string $method): bool
    {
        if ($method === null) {
            return false;
        }
        return PaymentMethod::tryFrom($method) !== null;
    }

    /**
     * Create a new order from the contents of a shopping cart.
     *
     * Builds an Order model, assigns a unique order number, calculates
     * the total price from the cart items, marks the order as “paid”,
     * and delegates persistence to the repository.
     *
     * @param  ShoppingCart      $cart    The shopping cart containing the items to order.
     * @param  int               $userId  The ID of the authenticated user placing the order.
     * @return Order                      The fully persisted Order (including its new ID).
     * @throws \RuntimeException          When the cart is empty.
     */
    public function createOrderFromCart(ShoppingCart $cart, int $userId): Order
    {
        if (empty($cart->items)) {
            throw new \RuntimeException('Cannot create an order from an empty cart.');
        }

        // Build the order model from the cart contents
        $order = new Order();
        $order->userId = $userId;
        $order->orderNumber = $this->generateOrderNumber();
        $order->date = new \DateTime();
        $order->items = $cart->items;
        $order->calculateTotal();  // sums item prices × quantities
        $order->status = 'paid';

        // Persist the order and its line items to the database
        $this->orderRepository->save($order);

        return $order;
    }

    private function generateOrderNumber(): string
    {
        return 'ORD-' . strtoupper(bin2hex(random_bytes(6)));
    }

    public function getOrderById(int $id): ?Order
    {
        return $this->orderRepository->findById($id);
    }

    public function getOrderByIdWithItems(int $id): ?Order
    {
        return $this->orderRepository->findByIdWithItems($id);
    }

    public function getOrdersByUserId(int $userId): array
    {
        return $this->orderRepository->findByUserId($userId);
    }
}
