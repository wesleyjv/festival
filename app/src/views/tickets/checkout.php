<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container py-5">

    <h1 class="fw-bold mb-4">Checkout</h1>

    <?php if ($viewModel->error !== null): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($viewModel->error) ?></div>
    <?php endif; ?>

    <form action="/checkout" method="POST">
        <div class="row g-4">

            <!-- Left: order summary -->
            <div class="col-lg-7">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Order Summary</h5>

                        <?php foreach ($viewModel->items as $item): ?>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <div>
                                    <span class="fw-semibold">
                                        <?= $item->ticket ? htmlspecialchars($item->ticket->name) : '&mdash;' ?>
                                    </span>
                                    <br>
                                    <small class="text-muted">
                                        <?= $item->quantity ?> &times; &euro;<?= number_format($item->price, 2) ?>
                                    </small>
                                </div>
                                <span class="fw-semibold">
                                    &euro;<?= number_format($item->price * $item->quantity, 2) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>

                        <div class="d-flex justify-content-between pt-3">
                            <strong>Total</strong>
                            <strong>&euro;<?= number_format($viewModel->total, 2) ?></strong>
                        </div>
                    </div>
                </div>

                <a href="/cart" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to Cart
                </a>
            </div>

            <!-- Right: payment -->
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Payment Method</h5>

                        <div class="list-group mb-3">
                            <?php foreach ($viewModel->paymentMethods as $i => $method): ?>
                                <label class="list-group-item d-flex align-items-center gap-2">
                                    <input type="radio" name="payment_method" value="<?= htmlspecialchars($method['value']) ?>"
                                           class="form-check-input" <?= $i === 0 ? 'required' : '' ?>>
                                    <span><?= htmlspecialchars($method['label']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <?php if (!$viewModel->isLoggedIn): ?>
                            <div class="alert alert-warning mb-3">
                                Please <a href="/login">log in</a> to complete your purchase.
                            </div>
                        <?php endif; ?>

                        <button type="submit" class="btn btn-dark w-100 py-2">
                            Pay &euro;<?= number_format($viewModel->total, 2) ?>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
