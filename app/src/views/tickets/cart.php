<?php
?>
<!DOCTYPE html>
<html>
<head><title>Your Cart</title></head>
<body>
    <h1>Shopping Cart</h1>
    <?php if (empty($cart->items)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($cart->items as $item): ?>
                <li>
                    Session: <?= $item->session->startTime->format('Y-m-d H:i') ?> 
                    | Price: €<?= number_format($item->session->price, 2) ?>
                    | Qty: <?= $item->quantity ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="/checkout">Proceed to Checkout</a>
    <?php endif; ?>
</body>
</html>