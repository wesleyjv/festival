<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container py-5">

    <h1 class="fw-bold mb-4">My Orders</h1>

    <?php if (!empty($_SESSION['email_success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['email_success']) ?></div>
        <?php unset($_SESSION['email_success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['email_error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['email_error']) ?></div>
        <?php unset($_SESSION['email_error']); ?>
    <?php endif; ?>

    <?php if (empty($viewModel->orders)): ?>

        <div class="text-center py-5">
            <p class="text-muted fs-5">You haven't placed any orders yet.</p>
            <a href="/" class="btn btn-outline-secondary">Browse Events</a>
        </div>

    <?php else: ?>

        <div class="mb-3">
            <input 
                type="text" 
                id="orderFilter" 
                class="form-control" 
                placeholder="Search by order number, status, or date..."
            >
        </div>
        <div class="table-responsive">
            <table class="table align-middle" id="ordersTable">
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
                                    <?= \App\Security\Csrf::field() ?>
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

<script>

document.getElementById("orderFilter").addEventListener("keyup", function () {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll("#ordersTable tbody tr");

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
});

</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
