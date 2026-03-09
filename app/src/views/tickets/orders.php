<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container py-5">

    <h1 class="fw-bold mb-4">My Orders</h1>

    <?php if (!empty($_SESSION['email_success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['email_success']) ?></div>
        <?php unset($_SESSION['email_success']); ?>
    <?php endif; ?>

    <?php if (empty($viewModel->orders)): ?>

        <div class="text-center py-5">
            <p class="text-muted fs-5">You haven't placed any orders yet.</p>
            <a href="/" class="btn btn-outline-secondary">Browse Events</a>
        </div>

    <?php else: ?>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Order Number</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($viewModel->orders as $order): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($order->orderNumber) ?></td>
                            <td><?= $order->date->format('d M Y, H:i') ?></td>
                            <td>&euro;<?= number_format($order->totalAmount, 2) ?></td>
                            <td>
                                <?php
                                    $badge = match ($order->status) {
                                        'paid'      => 'bg-success',
                                        'pending'   => 'bg-warning text-dark',
                                        'cancelled' => 'bg-danger',
                                        default     => 'bg-secondary',
                                    };
                                ?>
                                <span class="badge <?= $badge ?>"><?= ucfirst($order->status) ?></span>
                            </td>
                            <td>
                                <form action="/orders/<?= $order->id ?>/email" method="POST">
                                    <button type="submit" class="btn btn-dark">
                                        <i class="bi bi-envelope me-1"></i>Email Tickets
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
