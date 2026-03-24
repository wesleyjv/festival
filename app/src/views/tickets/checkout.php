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

    .checkout-wrapper {
        background-color: var(--bg-dark);
        background-image: radial-gradient(circle at bottom left, rgba(195, 154, 59, 0.05) 0%, transparent 40%),
                          radial-gradient(circle at bottom right, rgba(195, 154, 59, 0.05) 0%, transparent 40%);
        min-height: 100vh;
        padding: 60px 0 100px;
    }

    /* --- Header --- */
    .checkout-header {
        text-align: center;
        margin-bottom: 50px;
    }
    .checkout-title {
        font-size: 2.8rem;
        font-weight: 800;
        margin-bottom: 8px;
        letter-spacing: -0.5px;
    }
    .checkout-subtitle {
        font-size: 1.1rem;
        font-style: italic;
        color: #d1c8bd;
        margin-bottom: 8px;
    }
    .checkout-meta {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--text-muted);
        font-weight: 600;
    }

    /* --- Layout --- */
    .checkout-container {
        display: flex;
        gap: 30px;
        align-items: flex-start;
    }
    @media (max-width: 991px) {
        .checkout-container { flex-direction: column; }
    }
    .order-col {
        flex: 1.5;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .payment-col {
        flex: 1;
        position: sticky;
        top: 30px;
    }

    /* --- Panel (shared card style) --- */
    .panel {
        background-color: var(--card-bg);
        border-radius: 12px;
        padding: 25px 30px;
        color: var(--text-dark);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }
    .panel-title {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 15px;
        color: var(--text-dark);
    }

    /* --- Alert --- */
    .alert-custom {
        background-color: rgba(195, 154, 59, 0.12);
        border: 1px solid rgba(195, 154, 59, 0.3);
        border-radius: 8px;
        padding: 14px 18px;
        font-size: 0.85rem;
        color: #e8d9b8;
        margin-bottom: 20px;
    }

    /* --- Order items --- */
    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 0;
        border-bottom: 1px solid var(--border-color);
    }
    .order-item:last-of-type {
        border-bottom: none;
    }
    .item-name {
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--text-dark);
        margin-bottom: 4px;
    }
    .item-meta {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 500;
    }
    .item-total {
        font-weight: 700;
        font-size: 1rem;
        color: var(--text-dark);
    }

    /* --- Total row --- */
    .order-total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 20px;
        margin-top: 4px;
    }
    .order-total-label {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-dark);
    }
    .order-total-val {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--gold-accent);
    }

    /* --- Back link --- */
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: transparent;
        border: 1px solid rgba(255,255,255,0.2);
        color: #d1c8bd;
        border-radius: 6px;
        padding: 10px 18px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-decoration: none;
        transition: border-color 0.2s, color 0.2s;
    }
    .btn-back:hover {
        border-color: var(--gold-accent);
        color: #fff;
    }
    .btn-back svg {
        width: 14px;
        height: 14px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    /* --- Payment card --- */
    .payment-card {
        background-color: var(--card-bg);
        border-radius: 12px;
        padding: 35px;
        color: var(--text-dark);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }
    .payment-title {
        font-size: 1rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        text-align: center;
        margin-bottom: 25px;
    }

    /* --- Payment method options --- */
    .method-option {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background-color: #fff;
        margin-bottom: 10px;
        cursor: pointer;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .method-option:hover {
        border-color: var(--gold-accent);
    }
    .method-option input[type="radio"] {
        accent-color: var(--primary-brown);
        width: 16px;
        height: 16px;
        cursor: pointer;
    }
    .method-option input[type="radio"]:checked ~ .method-label {
        color: var(--primary-brown);
    }
    .method-option:has(input:checked) {
        border-color: var(--primary-brown);
        background-color: var(--card-bg);
        box-shadow: 0 4px 15px rgba(54, 41, 30, 0.1);
    }
    .method-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-dark);
    }

    /* --- Warning box --- */
    .warning-box {
        background-color: rgba(195, 154, 59, 0.1);
        border: 1px solid rgba(195, 154, 59, 0.35);
        border-radius: 8px;
        padding: 14px 18px;
        font-size: 0.82rem;
        color: var(--text-dark);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .warning-box a {
        color: var(--gold-accent);
        font-weight: 700;
        text-decoration: none;
    }
    .warning-box a:hover {
        text-decoration: underline;
    }
    .warning-box svg {
        flex-shrink: 0;
        width: 16px;
        height: 16px;
        fill: none;
        stroke: var(--gold-accent);
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    /* --- Total box --- */
    .total-box {
        background-color: var(--primary-brown);
        border-radius: 8px;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #fff;
        margin-bottom: 20px;
    }
    .total-box-label {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #d1c8bd;
    }
    .total-box-val {
        font-size: 1.8rem;
        font-weight: 800;
    }

    /* --- Pay button --- */
    .btn-pay {
        width: 100%;
        background-color: var(--btn-gold);
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 18px;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        cursor: pointer;
        transition: background-color 0.3s;
        box-shadow: 0 4px 15px rgba(191, 163, 116, 0.3);
    }
    .btn-pay:hover:not(:disabled) {
        background-color: #a88d60;
    }
    .btn-pay:disabled {
        opacity: 0.45;
        cursor: not-allowed;
        box-shadow: none;
    }
    .payment-note {
        text-align: center;
        font-size: 0.7rem;
        color: var(--text-muted);
        margin-top: 15px;
    }

    /* --- Ticket icon circle --- */
    .ticket-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background-color: rgba(195, 154, 59, 0.12);
        border-radius: 50%;
        flex-shrink: 0;
    }
    .ticket-icon svg {
        width: 15px;
        height: 15px;
        fill: none;
        stroke: var(--gold-accent);
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }
</style>

<div class="checkout-wrapper">
    <div class="container">

        <header class="checkout-header">
            <h1 class="checkout-title">Checkout</h1>
            <p class="checkout-subtitle">Review your order &amp; complete payment</p>
            <p class="checkout-meta">Secure &bull; Encrypted &bull; Instant Confirmation</p>
        </header>

        <?php if ($viewModel->error !== null): ?>
            <div class="alert-custom" style="max-width:900px;margin:0 auto 30px;">
                ⚠ &nbsp;<?= htmlspecialchars($viewModel->error) ?>
            </div>
        <?php endif; ?>

        <form action="/checkout" method="POST" class="checkout-container">

            <!-- Left: Order Summary -->
            <div class="order-col">
                <div class="panel">
                    <h3 class="panel-title">Order Summary</h3>

                    <?php foreach ($viewModel->items as $item): ?>
                        <div class="order-item">
                            <div style="display:flex;align-items:center;gap:14px;">
                                <div class="ticket-icon">
                                    <svg viewBox="0 0 24 24"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v2z"/></svg>
                                </div>
                                <div>
                                    <div class="item-name">
                                        <?= $item->ticket ? htmlspecialchars($item->ticket->name) : '&mdash;' ?>
                                    </div>
                                    <div class="item-meta">
                                        <?= $item->quantity ?> &times; &euro;<?= number_format($item->price, 2) ?>
                                    </div>
                                </div>
                            </div>
                            <span class="item-total">&euro;<?= number_format($item->price * $item->quantity, 2) ?></span>
                        </div>
                    <?php endforeach; ?>

                    <div class="order-total-row">
                        <span class="order-total-label">Total</span>
                        <span class="order-total-val">&euro;<?= number_format($viewModel->total, 2) ?></span>
                    </div>
                </div>

                <div>
                    <a href="/cart" class="btn-back">
                        <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                        Back to Cart
                    </a>
                </div>
            </div>

            <!-- Right: Payment -->
            <div class="payment-col">
                <div class="payment-card">
                    <h2 class="payment-title">Payment Method</h2>

                    <?php foreach ($viewModel->paymentMethods as $i => $method): ?>
                        <label class="method-option">
                            <input type="radio"
                                   name="payment_method"
                                   value="<?= htmlspecialchars($method['value']) ?>"
                                   <?= $i === 0 ? 'checked required' : '' ?>>
                            <span class="method-label"><?= htmlspecialchars($method['label']) ?></span>
                        </label>
                    <?php endforeach; ?>

                    <?php if (!$viewModel->isLoggedIn): ?>
                        <div class="warning-box" style="margin-top:20px;">
                            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            Please <a href="/login">log in</a>&nbsp;to complete your purchase.
                        </div>
                    <?php endif; ?>

                    <div class="total-box" style="margin-top:25px;">
                        <span class="total-box-label">Total Amount</span>
                        <span class="total-box-val">&euro;<?= number_format($viewModel->total, 2) ?></span>
                    </div>

                    <button type="submit" class="btn-pay" <?= !$viewModel->isLoggedIn ? 'disabled' : '' ?>>
                        ✦ &nbsp;Pay &euro;<?= number_format($viewModel->total, 2) ?> &nbsp;✦
                    </button>

                    <p class="payment-note">🔒 &nbsp;Your payment is secure and encrypted</p>
                </div>
            </div>

        </form>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>