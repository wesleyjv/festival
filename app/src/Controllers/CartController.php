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
     * Always redirects to the cart page.
     *
     * @return void Redirects to /cart.
     */
    public function add()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::validateRequest()) {
                header('Location: /cart');
                exit;
            }

            $ticketId = $_POST['ticket_id'] ?? null;

            if ($ticketId) {
                $qty = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;
                $qty = max(1, min(99, $qty));

                // Look up the ticket through the service (includes ID validation)
                $ticket = $this->ticketService->getTicketById((int)$ticketId);

                if ($ticket) {
                    // Retrieve existing cart from session or create a new one
                    $cart = $_SESSION['cart'] ?? new ShoppingCart();
                    $cart->addOrMergeTicket($ticket, $qty);
                    $_SESSION['cart'] = $cart;
                }
            }
        }

        header('Location: /cart');
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