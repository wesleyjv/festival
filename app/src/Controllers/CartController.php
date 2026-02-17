<?php
namespace App\Controllers;

use App\Models\ShoppingCart;
use App\Repositories\TicketRepository;

/**
 * CartController
 * 
 * Handles shopping cart operations including adding items to the cart
 * and displaying the cart contents.
 * 
 * @package App\Controllers
 */
class CartController
{
    /**
     * Add an item to the shopping cart
     * 
     * Processes POST requests to add a ticket to the cart. If the ticket_id
     * is provided and valid, creates or retrieves the shopping cart from the
     * session and adds the item with a quantity of 1. Redirects to the cart
     * page after processing.
     * 
     * @return void Redirects to /cart
     */
    public function add()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ticketId = $_POST['ticket_id'] ?? null;

            if ($ticketId) {
                $repo = new TicketRepository();
                $ticket = $repo->getById((int)$ticketId);

                if ($ticket) {
                    $cart = $_SESSION['cart'] ?? new ShoppingCart();
                    $cart->addItem($ticket, 1);
                    $_SESSION['cart'] = $cart;
                }
            }
        }

        header('Location: /cart');
        exit;
    }

    /**
     * Remove an item from the shopping cart
     * 
     * Processes POST requests to remove a ticket from the cart by its index.
     * Redirects to the cart page after processing.
     * 
     * @return void Redirects to /cart
     */
    public function remove()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $index = $_POST['item_index'] ?? null;

            if ($index !== null && isset($_SESSION['cart'])) {
                $cart = $_SESSION['cart'];
                if (isset($cart->items[(int)$index])) {
                    unset($cart->items[(int)$index]);
                    $cart->items = array_values($cart->items);
                    $_SESSION['cart'] = $cart;
                }
            }
        }

        header('Location: /cart');
        exit;
    }

    /**
     * Display the shopping cart
     * 
     * Retrieves the shopping cart from the session (or creates a new empty cart)
     * and renders the cart view template.
     * 
     * @return void Renders the cart view
     */
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $cart = $_SESSION['cart'] ?? new ShoppingCart();
        
        require __DIR__ . '/../views/tickets/cart.php';
    }
}