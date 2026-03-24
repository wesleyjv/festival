<?php require __DIR__ . '/../partials/header.php'; ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap');

    :root {
        --bg-dark: #1f1813;
        --card-bg: #f8f5eb;
        --text-dark: #2a1f17;
        --text-muted: #827566;
        --primary-brown: #36291e;
        --border-color: #d8cbbc;
        --gold-accent: #c39a3b;
        --btn-gold: #bfa374;
    }

    body {
        background-color: var(--bg-dark);
        font-family: 'Montserrat', sans-serif;
        color: #fff;
    }

    .confirm-wrapper {
        background-color: var(--bg-dark);
        background-image: radial-gradient(circle at bottom left, rgba(195, 154, 59, 0.05) 0%, transparent 40%),
                          radial-gradient(circle at bottom right, rgba(195, 154, 59, 0.05) 0%, transparent 40%);
        min-height: 100vh;
        padding: 60px 0 100px;
        display: flex;
        align-items: center;
    }

    /* --- Card --- */
    .confirm-card {
        background-color: var(--card-bg);
        border-radius: 16px;
        padding: 50px 45px;
        color: var(--text-dark);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
        text-align: center;
        max-width: 520px;
        margin: 0 auto;
    }

    /* --- Success icon --- */
    .success-icon {
        width: 72px;
        height: 72px;
        background: linear-gradient(135deg, rgba(195,154,59,0.15), rgba(195,154,59,0.05));
        border: 2px solid rgba(195, 154, 59, 0.4);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
    }
    .success-icon svg {
        width: 32px;
        height: 32px;
        fill: none;
        stroke: var(--gold-accent);
        stroke-width: 2.5;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    /* --- Heading --- */
    .confirm-title {
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin-bottom: 10px;
        color: var(--text-dark);
    }
    .confirm-subtitle {
        font-size: 0.95rem;
        color: var(--text-muted);
        font-style: italic;
        margin-bottom: 35px;
    }

    /* --- Detail rows --- */
    .detail-row {
        background-color: #fff;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 16px 22px;
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .detail-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: var(--text-muted);
    }
    .detail-val {
        font-size: 1rem;
        font-weight: 800;
        color: var(--text-dark);
    }
    .detail-val.gold { color: var(--gold-accent); }

    /* --- Divider --- */
    .confirm-divider {
        border: none;
        border-top: 1px solid var(--border-color);
        margin: 25px 0;
    }

    /* --- Email note --- */
    .email-note {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-bottom: 30px;
        line-height: 1.6;
    }
    .email-note strong {
        color: var(--text-dark);
        font-weight: 700;
    }

    /* --- Buttons --- */
    .confirm-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
    }
    .btn-home {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: transparent;
        border: 1px solid var(--border-color);
        color: var(--text-muted);
        border-radius: 6px;
        padding: 12px 22px;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-decoration: none;
        transition: border-color 0.2s, color 0.2s;
    }
    .btn-home:hover {
        border-color: var(--text-dark);
        color: var(--text-dark);
    }
    .btn-orders {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background-color: var(--btn-gold);
        border: none;
        color: #fff;
        border-radius: 6px;
        padding: 12px 24px;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-decoration: none;
        transition: background-color 0.3s;
        box-shadow: 0 4px 15px rgba(191, 163, 116, 0.3);
    }
    .btn-orders:hover {
        background-color: #a88d60;
        color: #fff;
    }
    .btn-home svg, .btn-orders svg {
        width: 13px;
        height: 13px;
        fill: none;
        stroke: currentColor;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }
</style>

<div class="confirm-wrapper">
    <div class="container">

        <div class="confirm-card">

            <div class="success-icon">
                <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            </div>

            <h1 class="confirm-title">Order Confirmed</h1>
            <p class="confirm-subtitle">Thank you for your purchase. Your tickets are being prepared.</p>

            <div class="detail-row">
                <span class="detail-label">Order Number</span>
                <span class="detail-val"><?= htmlspecialchars($viewModel->orderNumber) ?></span>
            </div>

            <?php if ($viewModel->orderTotal !== null): ?>
                <div class="detail-row">
                    <span class="detail-label">Total Paid</span>
                    <span class="detail-val gold">&euro;<?= number_format($viewModel->orderTotal, 2) ?></span>
                </div>
            <?php endif; ?>

            <hr class="confirm-divider">

            <p class="email-note">
                A confirmation email will be sent to<br>
                <strong><?= htmlspecialchars($viewModel->userEmail) ?></strong>
            </p>

            <div class="confirm-actions">
                <a href="/" class="btn-home">
                    <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                    Home
                </a>
                <a href="/orders" class="btn-orders">
                    ✦ &nbsp;My Orders
                </a>
            </div>

        </div>

    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>