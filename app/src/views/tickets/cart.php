<?php
$total = 0;
foreach ($cart->items as $item) {
    $total += $item->price * $item->quantity;
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - Haarlem Festival</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">
            <i class="bi bi-music-note-beamed me-1"></i> Haarlem Festival
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/events/yummy"><i class="bi bi-egg-fried me-1"></i>Yummy</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/events/stories"><i class="bi bi-book me-1"></i>Stories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/events/jazz"><i class="bi bi-vinyl me-1"></i>Jazz</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/events/history"><i class="bi bi-bank me-1"></i>History</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="/cart"><i class="bi bi-cart3"></i> Cart</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-light btn-sm ms-2 my-1" href="/login">Login</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-warning btn-sm ms-2 my-1 fw-semibold" href="/register">Register</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-5">
    <h1 class="fw-bold mb-4"><i class="bi bi-cart3 me-2"></i>Shopping Cart</h1>

    <?php if (empty($cart->items)): ?>
        <div class="alert alert-info"><i class="bi bi-info-circle me-1"></i> Your cart is empty.</div>
        <a href="/" class="btn btn-primary">Continue Browsing</a>
    <?php else: ?>
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Session</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart->items as $item): ?>
                    <tr>
                        <td>
                            <?php if ($item->session): ?>
                                <?= htmlspecialchars($item->session->startTime->format('D, d M Y H:i')) ?>
                                &ndash;
                                <?= htmlspecialchars($item->session->endTime->format('H:i')) ?>
                            <?php else: ?>
                                &mdash;
                            <?php endif; ?>
                        </td>
                        <td>&euro;<?= number_format($item->price, 2) ?></td>
                        <td><?= $item->quantity ?></td>
                        <td class="text-end">&euro;<?= number_format($item->price * $item->quantity, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end fw-bold">Total</td>
                    <td class="text-end fw-bold">&euro;<?= number_format($total, 2) ?></td>
                </tr>
            </tfoot>
        </table>

        <div class="d-flex justify-content-between">
            <a href="/" class="btn btn-outline-secondary">Continue Browsing</a>
            <a href="/checkout" class="btn btn-success">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</div>

<footer class="bg-dark text-white border-top border-secondary py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4 mb-3 mb-md-0">
                <h6 class="fw-bold"><i class="bi bi-music-note-beamed me-1"></i> Haarlem Festival</h6>
                <small class="text-white-50">&copy; <?= date('Y') ?> All rights reserved.</small>
            </div>
            <div class="col-md-4 text-center mb-3 mb-md-0">
                <a href="/events/yummy" class="link-secondary text-decoration-none me-3">Yummy</a>
                <a href="/events/stories" class="link-secondary text-decoration-none me-3">Stories</a>
                <a href="/events/jazz" class="link-secondary text-decoration-none me-3">Jazz</a>
                <a href="/events/history" class="link-secondary text-decoration-none">History</a>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="#" class="link-secondary text-decoration-none me-2"><i class="bi bi-facebook"></i></a>
                <a href="#" class="link-secondary text-decoration-none me-2"><i class="bi bi-instagram"></i></a>
                <a href="#" class="link-secondary text-decoration-none"><i class="bi bi-twitter-x"></i></a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>