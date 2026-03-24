<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container py-5">

    <h1 class="fw-bold mb-4">Checkout</h1>

    <?php if ($viewModel->error !== null): ?>
        <div class="alert alert-danger rounded-3"><?= htmlspecialchars($viewModel->error) ?></div>
    <?php endif; ?>

    <form action="/checkout" method="POST">
        <?= \App\Security\Csrf::field() ?>
        <div class="row g-4">

            <!-- Left: order summary -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 mb-3">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Order Summary</h5>

                        <?php foreach ($viewModel->items as $item): ?>
                            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                                <div>
                                    <div class="fw-semibold">
                                        <i class="bi bi-ticket-perforated text-muted me-2"></i>
                                        <?= $item->ticket ? htmlspecialchars($item->ticket->name) : '&mdash;' ?>
                                    </div>
                                    <small class="text-muted ms-4">
                                        <?= $item->quantity ?> &times; &euro;<?= number_format($item->price, 2) ?>
                                    </small>
                                </div>
                                <span class="fw-semibold">&euro;<?= number_format($item->price * $item->quantity, 2) ?></span>
                            </div>
                        <?php endforeach; ?>

                        <div class="d-flex justify-content-between align-items-center pt-3">
                            <strong>Total</strong>
                            <strong class="fs-5">&euro;<?= number_format($viewModel->total, 2) ?></strong>
                        </div>
                    </div>
                </div>

                <a href="/cart" class="btn btn-outline-secondary rounded-3">
                    <i class="bi bi-arrow-left me-1"></i>Back to Cart
                </a>
            </div>

            <!-- Right: payment -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Payment Method</h5>

                        <div class="list-group mb-3">
                            <?php foreach ($viewModel->paymentMethods as $i => $method): ?>
                                <label class="list-group-item list-group-item-action d-flex align-items-center gap-3 rounded-3 mb-2 border">
                                    <input type="radio" name="payment_method" value="<?= htmlspecialchars($method['value']) ?>"
                                           class="form-check-input" <?= $i === 0 ? 'checked required' : '' ?>>
                                    <span class="fw-semibold"><?= htmlspecialchars($method['label']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <?php if (!$viewModel->isLoggedIn): ?>
                            <div class="alert alert-warning rounded-3 mb-3">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                Please <a href="/login" class="alert-link">log in</a> to complete your purchase.
                            </div>
                        <?php endif; ?>

                        <button type="submit" class="btn btn-dark w-100 py-2 rounded-3"
                                <?= !$viewModel->isLoggedIn ? 'disabled' : '' ?>>
                            <i class="bi bi-lock me-2"></i>Pay &euro;<?= number_format($viewModel->total, 2) ?>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>