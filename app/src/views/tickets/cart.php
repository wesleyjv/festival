<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container py-5">

    <h1 class="fw-bold mb-4">Shopping Cart</h1>

    <?php if ($viewModel->isEmpty): ?>

        <div class="text-center py-5">
            <p class="text-muted fs-5">Your cart is empty.</p>
            <a href="/" class="btn btn-outline-secondary">Continue Browsing</a>
        </div>

    <?php else: ?>

        <div class="table-responsive mb-4">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Ticket</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($viewModel->items as $index => $item): ?>
                        <tr>
                            <td class="fw-semibold">
                                <?= $item->ticket ? htmlspecialchars($item->ticket->name) : '&mdash;' ?>
                            </td>
                            <td>&euro;<?= number_format($item->price, 2) ?></td>
                            <td><?= $item->quantity ?></td>
                            <td>&euro;<?= number_format($item->price * $item->quantity, 2) ?></td>
                            <td class="text-end">
                                <form action="/cart/remove" method="POST">
                                    <input type="hidden" name="item_index" value="<?= $index ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center border-top pt-4">
            <div>
                <span class="text-muted">Total</span>
                <h4 class="fw-bold mb-0">&euro;<?= number_format($viewModel->total, 2) ?></h4>
            </div>
            <div class="d-flex gap-2">
                <a href="/" class="btn btn-outline-secondary">Continue Browsing</a>
                <a href="/checkout" class="btn btn-dark">Proceed to Checkout</a>
            </div>
        </div>

    <?php endif; ?>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>