<?php
$total = 0;
foreach ($cart->items as $item) {
    $total += $item->price * $item->quantity;
}
?>
<?php require __DIR__ . '/../partials/header.php'; ?>

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

<?php require __DIR__ . '/../partials/footer.php'; ?>