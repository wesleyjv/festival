<?php
namespace App\Controllers;

use App\Models\ShoppingCart;
use App\Repositories\SessionRepository;

class CartController
{
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

    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $cart = $_SESSION['cart'] ?? new ShoppingCart();
        
        require __DIR__ . '/../views/tickets/cart.php';
    }
}