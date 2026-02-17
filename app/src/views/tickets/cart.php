<?php
$total = 0;
foreach ($cart->items as $item) {
    $total += $item->price * $item->quantity;
}
?>
<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container py-5">

    <!-- Hero -->
    <div class="text-center mb-5">
        <div class="bg-light rounded-4 p-4 p-md-5 shadow-sm">
            <h1 class="fw-bold mb-2">
                <i class="bi bi-cart3 me-2"></i>Shopping Cart
            </h1>
            <p class="text-muted mb-0">
                Review your selected tickets before proceeding to checkout.
            </p>
        </div>
    </div>

    <?php if (empty($cart->items)): ?>
        <div class="text-center py-5">
            <i class="bi bi-cart-x text-muted fs-1"></i>
            <h5 class="text-muted mt-3">Your cart is empty</h5>
            <p class="text-muted">Browse our events and add tickets to get started.</p>
            <a href="/" class="btn btn-outline-dark mt-2">
                <i class="bi bi-arrow-left me-1"></i>Continue Browsing
            </a>
        </div>
    <?php else: ?>

        <!-- Info bar -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Your Items</h3>
                <p class="text-muted mb-0">
                    <?= count($cart->items) ?> item<?= count($cart->items) !== 1 ? 's' : '' ?> in your cart
                </p>
            </div>
        </div>

        <!-- Cart items -->
        <div class="row g-4 mb-4">
            <?php foreach ($cart->items as $index => $item): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 h-100 shadow-sm rounded-4">
                        <div class="card-body d-flex flex-column p-4">

                            <!-- Ticket name -->
                            <div class="mb-3">
                                <h5 class="fw-bold mb-1">
                                    <?php if ($item->ticket): ?>
                                        <?= htmlspecialchars($item->ticket->name) ?>
                                    <?php else: ?>
                                        &mdash;
                                    <?php endif; ?>
                                </h5>
                                <span class="badge bg-primary-subtle text-primary">
                                    <i class="bi bi-ticket-perforated me-1"></i>Ticket
                                </span>
                            </div>

                            <!-- Details -->
                            <div class="d-flex flex-column gap-2 mb-3 flex-grow-1">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center p-2 me-2">
                                        <i class="bi bi-currency-euro"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Price</small>
                                        <span class="fw-semibold">&euro;<?= number_format($item->price, 2) ?></span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center p-2 me-2">
                                        <i class="bi bi-stack"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Quantity</small>
                                        <span class="fw-semibold"><?= $item->quantity ?></span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center p-2 me-2">
                                        <i class="bi bi-receipt"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Subtotal</small>
                                        <span class="fw-semibold">&euro;<?= number_format($item->price * $item->quantity, 2) ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Remove -->
                            <form action="/cart/remove" method="POST">
                                <input type="hidden" name="item_index" value="<?= $index ?>">
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="bi bi-trash me-1"></i>Remove
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Total & actions -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted d-block">Total</small>
                    <h4 class="fw-bold mb-0">&euro;<?= number_format($total, 2) ?></h4>
                </div>
                <div class="d-flex gap-2">
                    <a href="/" class="btn btn-outline-dark">
                        <i class="bi bi-arrow-left me-1"></i>Continue Browsing
                    </a>
                    <a href="/checkout" class="btn btn-danger">
                        <i class="bi bi-bag-check me-1"></i>Proceed to Checkout
                    </a>
                </div>
            </div>
        </div>

    <?php endif; ?>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>