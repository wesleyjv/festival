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

    .cart-wrapper {
        background-color: var(--bg-dark);
        background-image: radial-gradient(circle at bottom left, rgba(195, 154, 59, 0.05) 0%, transparent 40%),
                          radial-gradient(circle at bottom right, rgba(195, 154, 59, 0.05) 0%, transparent 40%);
        min-height: 100vh;
        padding: 60px 0 100px;
    }

    /* --- Header --- */
    .cart-header {
        text-align: center;
        margin-bottom: 50px;
    }
    .cart-title {
        font-size: 2.8rem;
        font-weight: 800;
        margin-bottom: 8px;
        letter-spacing: -0.5px;
    }
    .cart-subtitle {
        font-size: 1.1rem;
        font-style: italic;
        color: #d1c8bd;
        margin-bottom: 8px;
    }
    .cart-meta {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--text-muted);
        font-weight: 600;
    }

    /* --- Panel --- */
    .panel {
        background-color: var(--card-bg);
        border-radius: 12px;
        color: var(--text-dark);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        margin-bottom: 20px;
    }
    .panel-title {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 22px 30px 0;
        margin-bottom: 15px;
        color: var(--text-dark);
    }

    /* --- Table --- */
    .cart-table {
        width: 100%;
        border-collapse: collapse;
    }
    .cart-table thead tr {
        border-bottom: 2px solid var(--border-color);
    }
    .cart-table thead th {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: var(--text-muted);
        padding: 12px 20px;
    }
    .cart-table thead th:first-child { padding-left: 30px; }
    .cart-table thead th:last-child  { padding-right: 30px; text-align: right; }

    .cart-table tbody tr {
        border-bottom: 1px solid var(--border-color);
        transition: background-color 0.15s;
    }
    .cart-table tbody tr:last-child { border-bottom: none; }
    .cart-table tbody tr:hover { background-color: rgba(195, 154, 59, 0.04); }

    .cart-table td {
        padding: 18px 20px;
        font-size: 0.9rem;
    }
    .cart-table td:first-child { padding-left: 30px; }
    .cart-table td:last-child  { padding-right: 30px; text-align: right; }

    .td-ticket {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .ticket-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        background-color: rgba(195, 154, 59, 0.1);
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
    .td-name { font-weight: 700; color: var(--text-dark); }
    .td-price { color: var(--text-muted); font-weight: 500; }
    .td-subtotal { font-weight: 700; color: var(--text-dark); }

    /* Qty badge */
    .qty-badge {
        display: inline-block;
        background-color: rgba(54, 41, 30, 0.08);
        color: var(--primary-brown);
        border-radius: 20px;
        padding: 4px 14px;
        font-size: 0.82rem;
        font-weight: 700;
    }

    /* Remove button */
    .btn-remove {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: transparent;
        border: 1px solid var(--border-color);
        color: var(--text-muted);
        border-radius: 6px;
        padding: 7px 14px;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: border-color 0.2s, color 0.2s, background-color 0.2s;
    }
    .btn-remove:hover {
        border-color: #c0392b;
        color: #c0392b;
        background-color: rgba(192, 57, 43, 0.05);
    }
    .btn-remove svg {
        width: 13px;
        height: 13px;
        fill: none;
        stroke: currentColor;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    /* --- Footer row --- */
    .cart-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    /* Total box */
    .total-box {
        background-color: var(--primary-brown);
        border-radius: 8px;
        padding: 18px 28px;
        display: flex;
        align-items: center;
        gap: 20px;
        color: #fff;
    }
    .total-box-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #d1c8bd;
        margin-bottom: 2px;
    }
    .total-box-val {
        font-size: 1.7rem;
        font-weight: 800;
        line-height: 1;
    }

    /* Action buttons */
    .cart-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .btn-browse {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: transparent;
        border: 1px solid rgba(255,255,255,0.2);
        color: #d1c8bd;
        border-radius: 6px;
        padding: 13px 22px;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-decoration: none;
        transition: border-color 0.2s, color 0.2s;
    }
    .btn-browse:hover {
        border-color: var(--gold-accent);
        color: #fff;
    }
    .btn-checkout {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background-color: var(--btn-gold);
        border: none;
        color: #fff;
        border-radius: 6px;
        padding: 13px 28px;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-decoration: none;
        transition: background-color 0.3s;
        box-shadow: 0 4px 15px rgba(191, 163, 116, 0.3);
    }
    .btn-checkout:hover {
        background-color: #a88d60;
        color: #fff;
    }
    .btn-checkout svg, .btn-browse svg {
        width: 14px;
        height: 14px;
        fill: none;
        stroke: currentColor;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    /* --- Empty state --- */
    .empty-state {
        background-color: var(--card-bg);
        border-radius: 12px;
        padding: 80px 40px;
        text-align: center;
        color: var(--text-dark);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .empty-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 20px;
        background-color: rgba(195, 154, 59, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .empty-icon svg {
        width: 28px;
        height: 28px;
        fill: none;
        stroke: var(--gold-accent);
        stroke-width: 1.5;
        stroke-linecap: round;
        stroke-linejoin: round;
    }
    .empty-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--text-dark);
    }
    .empty-desc {
        font-size: 0.9rem;
        color: var(--text-muted);
        margin-bottom: 30px;
    }
    .btn-empty-browse {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background-color: var(--btn-gold);
        border: none;
        color: #fff;
        border-radius: 6px;
        padding: 13px 28px;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-decoration: none;
        transition: background-color 0.3s;
        box-shadow: 0 4px 15px rgba(191, 163, 116, 0.3);
    }
    .btn-empty-browse:hover {
        background-color: #a88d60;
        color: #fff;
    }
</style>

<div class="cart-wrapper">
    <div class="container">

        <header class="cart-header">
            <h1 class="cart-title">Shopping Cart</h1>
            <p class="cart-subtitle">Review your selected tickets</p>
            <p class="cart-meta">Secure &bull; Encrypted &bull; Instant Confirmation</p>
        </header>

        <?php if ($viewModel->isEmpty): ?>

            <div class="empty-state">
                <div class="empty-icon">
                    <svg viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                </div>
                <p class="empty-title">Your cart is empty</p>
                <p class="empty-desc">Browse our events and add tickets to get started.</p>
                <a href="/" class="btn-empty-browse">✦ &nbsp;Continue Browsing</a>
            </div>

        <?php else: ?>

            <div class="panel">
                <p class="panel-title">Order Items</p>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Ticket</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($viewModel->items as $index => $item): ?>
                            <tr>
                                <td>
                                    <div class="td-ticket">
                                        <div class="ticket-icon">
                                            <svg viewBox="0 0 24 24"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v2z"/></svg>
                                        </div>
                                        <span class="td-name">
                                            <?= $item->ticket ? htmlspecialchars($item->ticket->name) : '&mdash;' ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="td-price">&euro;<?= number_format($item->price, 2) ?></td>
                                <td><span class="qty-badge">&times;<?= $item->quantity ?></span></td>
                                <td class="td-subtotal">&euro;<?= number_format($item->price * $item->quantity, 2) ?></td>
                                <td>
                                    <form action="/cart/remove" method="POST">
                                        <?= \App\Security\Csrf::field() ?>
                                        <input type="hidden" name="item_index" value="<?= $index ?>">
                                        <button type="submit" class="btn-remove">
                                            <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="cart-footer">
                <div class="total-box">
                    <div>
                        <div class="total-box-label">Total Amount</div>
                        <div class="total-box-val">&euro;<?= number_format($viewModel->total, 2) ?></div>
                    </div>
                </div>

                <div class="cart-actions">
                    <a href="/" class="btn-browse">
                        <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                        Continue Browsing
                    </a>
                    <a href="/checkout" class="btn-checkout">
                        <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        ✦ &nbsp;Proceed to Checkout
                    </a>
                </div>
            </div>

        <?php endif; ?>

    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>