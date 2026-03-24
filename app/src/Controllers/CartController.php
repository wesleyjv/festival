<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\ShoppingCart;
use App\Security\Csrf;
use App\Services\TicketService;
use App\ViewModels\CartViewModel;

/**
 * CartController – handles shopping-cart HTTP requests.
 *
 * Manages adding tickets to the cart, removing items by index, and
 * displaying the cart contents. Cart state is persisted in the PHP
 * session (`$_SESSION['cart']`) as a serialised ShoppingCart object.
 *
 * All ticket look-ups go through TicketService so that validation
 * and any future business rules are applied consistently.
 */
class CartController
{
    /** @var TicketService Service used to look up ticket details. */
    private TicketService $ticketService;

    /**
     * Create a new CartController.
     *
     * Accepts an optional TicketService for dependency injection.
     * When called without arguments (as the router does) a default
     * service instance is created automatically.
     *
     * @param TicketService|null $ticketService  Service instance, or null for the default.
     */
    public function __construct(?TicketService $ticketService = null)
    {
        $this->ticketService = $ticketService ?? new TicketService();
    }

    /**
     * POST /cart/add – Add a ticket to the shopping cart.
     *
     * Reads `ticket_id` and optional `quantity` (1–99, default 1). Merges
     * quantity into an existing line when the same ticket is already in the cart.
     * Returns JSON when the client sends Accept: application/json, ajax=1, or
     * X-Requested-With: XMLHttpRequest; otherwise redirects to /cart.
     */
    public function add()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $wantsJson = $this->wantsJsonCartAddResponse();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($wantsJson) {
                $this->jsonCartAddResponse(false, 'Invalid request.');
            }
            header('Location: /cart');
            exit;
        }

        if (!Csrf::validateRequest()) {
            if ($wantsJson) {
                $this->jsonCartAddResponse(false, 'Your session expired or the form was invalid. Refresh the page and try again.');
            }
            header('Location: /cart');
            exit;
        }

        $ticketId = $_POST['ticket_id'] ?? null;
        if (!$ticketId) {
            if ($wantsJson) {
                $this->jsonCartAddResponse(false, 'No ticket was selected.');
            }
            header('Location: /cart');
            exit;
        }

        $qty = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;
        $qty = max(1, min(99, $qty));

        $ticket = $this->ticketService->getTicketById((int) $ticketId);
        if (!$ticket) {
            if ($wantsJson) {
                $this->jsonCartAddResponse(false, 'That ticket could not be found.');
            }
            header('Location: /cart');
            exit;
        }

        $cart = $_SESSION['cart'] ?? new ShoppingCart();
        $cart->addOrMergeTicket($ticket, $qty);
        $_SESSION['cart'] = $cart;

        if ($wantsJson) {
            $label = $ticket->name;
            $msg = $qty === 1
                ? sprintf('“%s” was added to your cart.', $label)
                : sprintf('%d × “%s” were added to your cart.', $qty, $label);
            $this->jsonCartAddResponse(true, $msg, [
                'ticketName' => $label,
                'quantity' => $qty,
            ]);
        }

        header('Location: /cart');
        exit;
    }

    private function wantsJsonCartAddResponse(): bool
    {
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        if (is_string($accept) && stripos($accept, 'application/json') !== false) {
            return true;
        }
        if (($_POST['ajax'] ?? '') === '1') {
            return true;
        }
        $xhr = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
        return is_string($xhr) && strtolower($xhr) === 'xmlhttprequest';
    }

    /**
     * @param array<string,mixed> $extra
     */
    private function jsonCartAddResponse(bool $ok, string $message, array $extra = []): void
    {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array_merge([
            'ok' => $ok,
            'message' => $message,
        ], $extra), JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * POST /cart/remove – Remove an item from the shopping cart by its index.
     *
     * Reads `item_index` from the POST body, removes the matching entry
     * from the cart’s items array, and re-indexes so there are no gaps.
     * Always redirects to the cart page.
     *
     * @return void Redirects to /cart.
     */
    public function remove()
    {
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
    }

    /**
     * GET /cart – Display the shopping cart contents.
     *
     * Retrieves the cart from the session (or creates an empty one) and
     * renders the cart view template.
     *
     * @return void Renders the cart view.
     */
    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $cart = $_SESSION['cart'] ?? new ShoppingCart();

        $viewModel = new CartViewModel(
            items: $cart->items,
            total: $cart->getTotal(),
        );

        require __DIR__ . '/../views/tickets/cart.php';
    }
}