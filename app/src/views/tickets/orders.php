<?php 
$mainClass = 'p-0';
require __DIR__ . '/../partials/header.php'; 
?>

<style>
    :root {
        --order-card-bg: #ffffff;
        --order-status-paid: #28a745;
        --order-status-pending: #ffc107;
        --order-status-cancelled: #dc3545;
    }

    .orders-page {
        padding: 60px 0;
        background-color: #f8f9fa;
        min-height: calc(100vh - 56px);
    }

    .orders-header {
        margin-bottom: 40px;
    }

    .orders-title {
        font-family: var(--font-josefin);
        font-weight: 700;
        font-size: var(--text-4xl);
        color: var(--primary-dark);
        margin-bottom: 8px;
    }

    .orders-subtitle {
        font-family: var(--font-inter);
        font-weight: 400;
        color: #6c757d;
        font-size: var(--text-lg);
    }

    .filter-container {
        position: relative;
        margin-bottom: 30px;
        max-width: 500px;
    }

    .filter-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
    }

    .order-filter-input {
        padding-left: 45px;
        height: 50px;
        border-radius: var(--radius-md);
        border: 1px solid #e9ecef;
        box-shadow: var(--shadow-sm);
        font-family: var(--font-inter);
        transition: all 0.3s ease;
    }

    .order-filter-input:focus {
        box-shadow: var(--shadow-md);
        border-color: var(--primary-orange);
        outline: none;
    }

    .orders-card {
        background: var(--order-card-bg);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        border: none;
        overflow: hidden;
        margin-bottom: 30px;
    }

    .table-orders {
        margin-bottom: 0;
    }

    .table-orders thead th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
        padding: 15px 20px;
        border-bottom: 1px solid #edf2f7;
    }

    .table-orders tbody td {
        padding: 20px;
        vertical-align: middle;
        border-bottom: 1px solid #edf2f7;
        color: #2d3748;
    }

    .order-number {
        font-weight: 700;
        color: var(--primary-dark);
    }

    .order-date {
        color: #718096;
        font-size: 14px;
    }

    .order-total {
        font-weight: 600;
        font-size: 16px;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
    }

    .status-paid { background-color: #e6fffa; color: #234e52; border: 1px solid #b2f5ea; }
    .status-pending { background-color: #fffaf0; color: #744210; border: 1px solid #fbd38d; }
    .status-cancelled { background-color: #fff5f5; color: #742a2a; border: 1px solid #feb2b2; }

    .btn-email-tickets {
        background-color: var(--primary-dark);
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-email-tickets:hover {
        background-color: var(--primary-orange);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
    }

    .empty-state-icon {
        font-size: 64px;
        color: #dee2e6;
        margin-bottom: 20px;
    }

    .btn-browse {
        background-color: var(--primary-orange);
        color: white;
        border: none;
        padding: 14px 34px;
        border-radius: var(--radius-full);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 24px;
        display: inline-block;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-browse:hover {
        background-color: var(--primary-dark);
        color: white;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    @media (max-width: 768px) {
        .orders-page {
            padding: 40px 0;
        }
        .orders-title {
            font-size: var(--text-3xl);
        }
    }
</style>

<div class="orders-page">
    <div class="container">
        
        <div class="orders-header">
            <h1 class="orders-title">My Orders</h1>
            <p class="orders-subtitle">Manage your festival tickets and order history</p>
        </div>

        <?php if (!empty($_SESSION['email_success'])): ?>
            <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: var(--radius-md); background-color: #e6fffa; color: #234e52;">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?= htmlspecialchars($_SESSION['email_success']) ?>
            </div>
            <?php unset($_SESSION['email_success']); ?>
        <?php endif; ?>

        <?php if (empty($viewModel->orders)): ?>

            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-ticket-perforated"></i>
                </div>
                <h3 class="fw-bold" style="color: var(--primary-dark);">No orders found</h3>
                <p class="text-muted fs-5">You haven't placed any orders for the Haarlem Festival yet.</p>
                <a href="/" class="btn btn-browse">Browse Events</a>
            </div>

        <?php else: ?>

            <div class="filter-container">
                <i class="bi bi-search filter-icon"></i>
                <input 
                    type="text" 
                    id="orderFilter" 
                    class="form-control order-filter-input" 
                    placeholder="Search by order number, status, or date..."
                >
            </div>

            <div class="orders-card">
                <div class="table-responsive">
                    <table class="table table-orders" id="ordersTable">
                        <thead>
                            <tr>
                                <th>Order Number</th>
                                <th>Date & Time</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($viewModel->orders as $order): ?>
                                <tr>
                                    <td>
                                        <span class="order-number">#<?= htmlspecialchars($order->orderNumber) ?></span>
                                    </td>
                                    <td>
                                        <div class="order-date fw-medium">
                                            <i class="bi bi-calendar3 me-2"></i>
                                            <?= $order->date->format('d M Y') ?>
                                        </div>
                                        <div class="text-muted small mt-1">
                                            <i class="bi bi-clock me-2"></i>
                                            <?= $order->date->format('H:i') ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="order-total">&euro;<?= number_format($order->totalAmount, 2) ?></span>
                                    </td>
                                    <td>
                                        <?php
                                            $statusClass = match ($order->status) {
                                                'paid'      => 'status-paid',
                                                'pending'   => 'status-pending',
                                                'cancelled' => 'status-cancelled',
                                                default     => 'bg-secondary text-white',
                                            };
                                        ?>
                                        <span class="status-badge <?= $statusClass ?>">
                                            <?= ucfirst($order->status) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <form action="/orders/<?= $order->id ?>/email" method="POST" class="d-inline">
                                            <button type="submit" class="btn btn-email-tickets">
                                                <i class="bi bi-envelope-at"></i>
                                                <span>Email Tickets</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php endif; ?>

    </div>
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

<?php require __DIR__ . '/../partials/header.php'; ?>
