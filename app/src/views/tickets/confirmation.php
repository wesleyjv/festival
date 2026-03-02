<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">

            <div class="card p-5">
                <h2 class="fw-bold text-success mb-3">
                    <i class="bi bi-check-circle me-2"></i>Order Confirmed
                </h2>
                <p class="text-muted">Thank you for your purchase. Your tickets are being prepared.</p>

                <div class="bg-light rounded p-3 mb-3">
                    <small class="text-muted d-block">Order Number</small>
                    <strong class="fs-5"><?= htmlspecialchars($viewModel->orderNumber) ?></strong>
                </div>

                <?php if ($viewModel->orderTotal !== null): ?>
                    <div class="bg-light rounded p-3 mb-3">
                        <small class="text-muted d-block">Total Paid</small>
                        <strong class="fs-5">&euro;<?= number_format($viewModel->orderTotal, 2) ?></strong>
                    </div>
                <?php endif; ?>

                <p class="text-muted small">
                    A confirmation email will be sent to
                    <strong><?= htmlspecialchars($viewModel->userEmail) ?></strong>.
                </p>

                <div class="mt-3">
                    <a href="/" class="btn btn-outline-secondary me-2">Home</a>
                    <a href="/orders" class="btn btn-dark">My Orders</a>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
