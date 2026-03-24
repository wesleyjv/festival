<?php 
$mainClass = 'p-0';
require __DIR__ . '/../partials/header.php'; 
?>

<style>
    .confirm-page {
        padding: 80px 0;
        background-color: #f8f9fa;
        min-height: calc(100vh - 56px);
        display: flex;
        align-items: center;
    }

    .confirm-card {
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        padding: 50px;
        text-align: center;
        max-width: 600px;
        margin: 0 auto;
        border: none;
    }

    .success-icon-wrapper {
        width: 80px;
        height: 80px;
        background-color: #e6fffa;
        color: #28a745;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px;
        font-size: 40px;
        box-shadow: 0 4px 10px rgba(40, 167, 69, 0.1);
    }

    .confirm-title {
        font-family: var(--font-josefin);
        font-weight: 700;
        font-size: var(--text-4xl);
        color: var(--primary-dark);
        margin-bottom: 10px;
    }

    .confirm-subtitle {
        font-family: var(--font-inter);
        color: #6c757d;
        font-size: var(--text-lg);
        margin-bottom: 40px;
    }

    .order-details {
        background-color: #f8f9fa;
        border-radius: var(--radius-md);
        padding: 25px;
        margin-bottom: 40px;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .detail-item:last-child {
        margin-bottom: 0;
    }

    .detail-label {
        font-family: var(--font-inter);
        font-weight: 600;
        color: #495057;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 1px;
    }

    .detail-value {
        font-family: var(--font-inter);
        font-weight: 700;
        color: var(--primary-dark);
        font-size: 18px;
    }

    .detail-value.amount {
        color: var(--primary-orange);
    }

    .email-notification {
        background-color: #fff8f0;
        border: 1px solid #fee2e2;
        border-radius: var(--radius-sm);
        padding: 15px;
        margin-bottom: 40px;
        display: flex;
        align-items: center;
        gap: 15px;
        text-align: left;
    }

    .email-notification i {
        font-size: 24px;
        color: var(--primary-orange);
    }

    .email-notification p {
        margin: 0;
        font-size: 14px;
        color: #7c2d12;
    }

    .email-notification strong {
        color: var(--primary-dark);
    }

    .confirm-actions {
        display: flex;
        gap: 20px;
        justify-content: center;
    }

    .btn-confirm {
        padding: 14px 30px;
        border-radius: var(--radius-full);
        font-weight: 600;
        font-family: var(--font-inter);
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-primary-confirm {
        background-color: var(--primary-orange);
        color: white;
        border: none;
        box-shadow: 0 4px 15px rgba(224, 145, 69, 0.3);
    }

    .btn-primary-confirm:hover {
        background-color: #d17a35;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(224, 145, 69, 0.4);
    }

    .btn-secondary-confirm {
        background-color: white;
        color: var(--primary-dark);
        border: 2px solid #e9ecef;
    }

    .btn-secondary-confirm:hover {
        border-color: var(--primary-dark);
        background-color: #f8f9fa;
        color: var(--primary-dark);
        transform: translateY(-2px);
    }

    @media (max-width: 576px) {
        .confirm-card {
            padding: 30px 20px;
        }
        .confirm-actions {
            flex-direction: column;
        }
        .btn-confirm {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="confirm-page">
    <div class="container">
        <div class="confirm-card">
            
            <div class="success-icon-wrapper">
                <i class="bi bi-check-lg"></i>
            </div>

            <h1 class="confirm-title">Order Confirmed!</h1>
            <p class="confirm-subtitle">Thank you for your purchase. We've received your order.</p>

            <div class="order-details">
                <div class="detail-item">
                    <span class="detail-label">Order Number</span>
                    <span class="detail-value">#<?= htmlspecialchars($viewModel->orderNumber) ?></span>
                </div>
                <?php if ($viewModel->orderTotal !== null): ?>
                    <div class="detail-item">
                        <span class="detail-label">Total Amount</span>
                        <span class="detail-value amount">&euro;<?= number_format($viewModel->orderTotal, 2) ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="email-notification">
                <i class="bi bi-envelope-check"></i>
                <p>
                    A confirmation email with your tickets has been sent to<br>
                    <strong><?= htmlspecialchars($viewModel->userEmail) ?></strong>
                </p>
            </div>

            <div class="confirm-actions">
                <a href="/orders" class="btn-confirm btn-primary-confirm">
                    <i class="bi bi-ticket-detailed"></i>
                    View My Orders
                </a>
                <a href="/" class="btn-confirm btn-secondary-confirm">
                    <i class="bi bi-house"></i>
                    Back to Home
                </a>
            </div>

        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/header.php'; ?>
