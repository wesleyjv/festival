<?php
/**
 * Reservation success page.
 * Data is supplied by EventsController::displayReservationSuccessPage()
 * via the $successData local variable (read from session).
 *
 * @var array $successData {
 *   reservation_id: int,
 *   restaurant_name: string,
 *   restaurant_slug: string,
 *   restaurant_image_path: string|null,
 *   festival_date: string,        — e.g. "Wed 23 July"
 *   session_start: string,        — e.g. "17:00"
 *   session_end: string,          — e.g. "19:30"
 *   adults: int,
 *   children: int,
 *   reservation_fee_cents: int,
 * }
 */

$fmt = static fn (int $cents): string =>
    '€' . number_format($cents / 100, 2, ',', '.');

require __DIR__ . '/../../partials/header.php';
?>

<link rel="stylesheet" href="/assets/yummy/css/globals.css" />
<link rel="stylesheet" href="/assets/yummy/css/styleguide.css" />
<link rel="stylesheet" href="/assets/yummy/css/style.css" />

<style>
  /* ── Shared tokens ───────────────────────────────────── */
  :root {
    --dk:    #1a2a3a;
    --acc:   #b46b29;
    --green: #16a34a;
    --bg:    #faf3e8;
    --card:  #fff8f0;
    --border:#e8d9c4;
    --muted: #6b7280;
  }

  /* ── Hero ────────────────────────────────────────────── */
  .rs-hero {
    position: relative;
    width: 100%;
    min-height: 320px;
    display: flex;
    align-items: flex-end;
    overflow: hidden;
    background-color: #1a2a3a;
  }

  .rs-hero__img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
  }

  .rs-hero__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.75) 0%, rgba(0,0,0,0.2) 60%, transparent 100%);
  }

  .rs-hero__content {
    position: relative;
    padding: 40px 48px;
    display: flex;
    align-items: center;
    gap: 16px;
  }

  .rs-hero__check {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: #16a34a;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    flex-shrink: 0;
  }

  .rs-hero__title {
    font-size: 36px;
    font-weight: 800;
    color: #fff;
    margin: 0;
    text-shadow: 0 2px 8px rgba(0,0,0,0.4);
  }

  /* ── Page ────────────────────────────────────────────── */
  .rs-page {
    background: #faf3e8;
    min-height: 60vh;
    padding: 48px 24px 80px;
  }

  .rs-container {
    max-width: 640px;
    margin: 0 auto;
  }

  /* ── Confirmation banner ─────────────────────────────── */
  .rs-banner {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 14px;
    padding: 20px 24px;
    margin-bottom: 24px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
  }

  .rs-banner__icon { font-size: 24px; flex-shrink: 0; margin-top: 2px; }

  .rs-banner__body h2 {
    font-size: 18px;
    font-weight: 700;
    color: #166534;
    margin: 0 0 4px 0;
  }

  .rs-banner__body p {
    font-size: 14px;
    color: #15803d;
    margin: 0;
  }

  /* ── Detail card ─────────────────────────────────────── */
  .rs-card {
    background: #fff8f0;
    border: 1px solid #e8d9c4;
    border-radius: 14px;
    padding: 24px;
    margin-bottom: 16px;
  }

  .rs-card-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: #b46b29;
    margin: 0 0 16px 0;
  }

  .rs-detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 9px 0;
    border-bottom: 1px solid #e8d9c4;
    font-size: 14px;
  }

  .rs-detail-row:last-child { border-bottom: none; padding-bottom: 0; }

  .rs-detail-row dt {
    color: #6b7280;
    font-weight: 400;
  }

  .rs-detail-row dd {
    color: #1a2a3a;
    font-weight: 600;
    margin: 0;
    text-align: right;
  }

  /* ── Fee total ───────────────────────────────────────── */
  .rs-fee-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    background: #1a2a3a;
    border-radius: 14px;
    margin-bottom: 28px;
  }

  .rs-fee-total__lbl { font-size: 14px; font-weight: 600; color: rgba(255,255,255,0.8); }
  .rs-fee-total__val { font-size: 24px; font-weight: 800; color: #fff; }

  /* ── Action buttons ──────────────────────────────────── */
  .rs-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
  }

  .rs-btn {
    flex: 1;
    padding: 14px 20px;
    border-radius: 999px;
    font-size: 15px;
    font-weight: 700;
    text-align: center;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: background .15s, color .15s;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 160px;
  }

  .rs-btn--outline {
    border: 2px solid #1a2a3a;
    background: transparent;
    color: #1a2a3a;
  }

  .rs-btn--outline:hover {
    background: #1a2a3a;
    color: #fff;
  }

  .rs-btn--primary {
    background: #b46b29;
    color: #fff;
  }

  .rs-btn--primary:hover { background: #9a5a22; }

  /* ── Mobile ──────────────────────────────────────────── */
  @media (max-width: 640px) {
    .rs-hero__content { padding: 24px 20px; }
    .rs-hero__title   { font-size: 26px; }
    .rs-page          { padding: 32px 16px 60px; }
    .rs-actions       { flex-direction: column; }
    .rs-btn           { flex: none; width: 100%; }
  }
</style>

<!-- Hero -->
<section class="rs-hero">
  <?php if (!empty($successData['restaurant_image_path'])): ?>
    <img
      class="rs-hero__img"
      src="<?= htmlspecialchars($successData['restaurant_image_path'], ENT_QUOTES) ?>"
      alt="<?= htmlspecialchars($successData['restaurant_name'], ENT_QUOTES) ?>"
    >
  <?php endif; ?>

  <div class="rs-hero__overlay"></div>

  <div class="rs-hero__content">
    <div class="rs-hero__check" aria-hidden="true">&#10003;</div>
    <h1 class="rs-hero__title">Reservation Confirmed!</h1>
  </div>
</section>

<!-- Page body -->
<div class="rs-page">
  <div class="rs-container">

    <!-- Confirmation banner -->
    <div class="rs-banner">
      <span class="rs-banner__icon">&#127860;</span>
      <div class="rs-banner__body">
        <h2>Your table is reserved</h2>
        <p>
          Your table at
          <strong><?= htmlspecialchars($successData['restaurant_name'], ENT_QUOTES) ?></strong>
          has been reserved. See you at the festival!
        </p>
      </div>
    </div>

    <!-- Booking details -->
    <div class="rs-card">
      <p class="rs-card-label">Booking details</p>
      <dl>
        <div class="rs-detail-row">
          <dt>Restaurant</dt>
          <dd><?= htmlspecialchars($successData['restaurant_name'], ENT_QUOTES) ?></dd>
        </div>
        <div class="rs-detail-row">
          <dt>Date</dt>
          <dd><?= htmlspecialchars($successData['festival_date'], ENT_QUOTES) ?></dd>
        </div>
        <div class="rs-detail-row">
          <dt>Session time</dt>
          <dd>
            <?= htmlspecialchars($successData['session_start'], ENT_QUOTES) ?>
            – <?= htmlspecialchars($successData['session_end'], ENT_QUOTES) ?>
          </dd>
        </div>
        <div class="rs-detail-row">
          <dt>Adults</dt>
          <dd><?= (int) $successData['adults'] ?></dd>
        </div>
        <?php if ((int) $successData['children'] > 0): ?>
          <div class="rs-detail-row">
            <dt>Children</dt>
            <dd><?= (int) $successData['children'] ?></dd>
          </div>
        <?php endif; ?>
        <div class="rs-detail-row">
          <dt>Reservation #</dt>
          <dd>#<?= (int) $successData['reservation_id'] ?></dd>
        </div>
      </dl>
    </div>

    <!-- Fee paid -->
    <div class="rs-fee-total">
      <span class="rs-fee-total__lbl">Reservation fee paid</span>
      <span class="rs-fee-total__val">
        <?= htmlspecialchars($fmt((int) $successData['reservation_fee_cents']), ENT_QUOTES) ?>
      </span>
    </div>

    <!-- Actions -->
    <div class="rs-actions">
      <a href="/events/yummy" class="rs-btn rs-btn--outline">
        &#8592; Back to Yummy
      </a>
      <a href="#" class="rs-btn rs-btn--primary">
        View My Program &#128197;
      </a>
    </div>

  </div>
</div>

<?php require __DIR__ . '/../../partials/footer.php'; ?>
