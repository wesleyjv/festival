<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container py-5">

    <h1 class="fw-bold mb-4">Shopping Cart</h1>

    <?php if ($viewModel->isEmpty): ?>

        <div class="text-center py-5">
            <i class="bi bi-cart text-muted" style="font-size:3rem"></i>
            <p class="text-muted fs-5 mt-3 mb-4">Your cart is empty.</p>
            <a href="/" class="btn btn-outline-secondary">Continue Browsing</a>
        </div>

    <?php else: ?>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3">Ticket</th>
                            <th class="py-3">Price</th>
                            <th class="py-3">Qty</th>
                            <th class="py-3">Subtotal</th>
                            <th class="pe-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($viewModel->items as $index => $item): ?>
                            <tr>
                                <td class="ps-4 fw-semibold">
                                    <i class="bi bi-ticket-perforated text-muted me-2"></i>
                                    <?= $item->ticket ? htmlspecialchars($item->ticket->name) : '&mdash;' ?>
                                </td>
                                <td class="text-muted">&euro;<?= number_format($item->price, 2) ?></td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3">
                                        &times;<?= $item->quantity ?>
                                    </span>
                                </td>
                                <td class="fw-semibold">&euro;<?= number_format($item->price * $item->quantity, 2) ?></td>
                                <td class="text-end pe-4">
                                    <form action="/cart/remove" method="POST">
                                        <input type="hidden" name="item_index" value="<?= $index ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-3">
                                            <i class="bi bi-trash me-1"></i>Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <div class="bg-light rounded-4 px-4 py-3">
                <span class="text-muted small">Total</span>
                <h4 class="fw-bold mb-0">&euro;<?= number_format($viewModel->total, 2) ?></h4>
            </div>
            <div class="d-flex gap-2">
                <a href="/" class="btn btn-outline-secondary rounded-3">Continue Browsing</a>
                <a href="/checkout" class="btn btn-dark rounded-3">
                    <i class="bi bi-lock me-1"></i>Proceed to Checkout
                </a>
            </div>
        </div>

    <?php endif; ?>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>