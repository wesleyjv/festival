<?php
namespace App\Controllers;

use App\Models\ShoppingCart;
use App\Repositories\SessionRepository;

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
     * Processes POST requests to add a session to the cart. If the session_id
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
            $sessionId = $_POST['session_id'] ?? null;
            
            if ($sessionId) {
                $repo = new SessionRepository();
                $session = $repo->getById((int)$sessionId);

                if ($session) {
                    $cart = $_SESSION['cart'] ?? new ShoppingCart();
                    $cart->addItem($session, 1);
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