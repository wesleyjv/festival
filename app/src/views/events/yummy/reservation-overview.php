<?php
/** @var \App\ViewModels\ReservationOverviewViewModel $viewModel */

use App\Security\Csrf;

$vm  = $viewModel;
$r   = $viewModel->restaurant;

/* Price formatter — European format with comma decimal separator */
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
    --bg:    #faf3e8;
    --card:  #fff8f0;
    --muted: #6b7280;
    --border:#e8d9c4;
  }

  /* ── Hero ────────────────────────────────────────────── */
  .ro-hero {
    position: relative;
    width: 100%;
    min-height: 320px;
    display: flex;
    align-items: flex-end;
    overflow: hidden;
    background-color: #1a2a3a;
  }

  .ro-hero__img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
  }

  .ro-hero__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.75) 0%, rgba(0,0,0,0.2) 60%, transparent 100%);
  }

  .ro-hero__breadcrumb {
    position: relative;
    padding: 32px 48px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: rgba(255,255,255,0.75);
    flex-wrap: wrap;
  }

  .ro-hero__breadcrumb a { color: rgba(255,255,255,0.75); text-decoration: none; }
  .ro-hero__breadcrumb a:hover { color: #fff; }
  .ro-hero__breadcrumb .sep     { color: rgba(255,255,255,0.4); }
  .ro-hero__breadcrumb .current { color: #fff; font-weight: 600; }

  /* ── Page layout ─────────────────────────────────────── */
  .ro-page {
    background: #faf3e8;
    min-height: 100vh;
    padding: 48px 24px 80px;
  }

  .ro-container {
    max-width: 780px;
    margin: 0 auto;
  }

  .ro-heading {
    font-size: 28px;
    font-weight: 800;
    color: #1a2a3a;
    margin: 0 0 8px 0;
  }

  .ro-subheading {
    font-size: 14px;
    color: #6b7280;
    margin: 0 0 32px 0;
  }

  .ro-subheading strong {
    color: #1a2a3a;
    font-weight: 600;
  }

  /* ── Card base ───────────────────────────────────────── */
  .ro-card {
    background: #fff8f0;
    border: 1px solid #e8d9c4;
    border-radius: 14px;
    padding: 24px;
    margin-bottom: 16px;
  }

  .ro-card-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: #b46b29;
    margin: 0 0 14px 0;
  }

  /* ── Guest summary boxes ─────────────────────────────── */
  .ro-guests-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    margin-bottom: 16px;
  }

  .ro-guest-box {
    background: #fff8f0;
    border: 1px solid #e8d9c4;
    border-radius: 14px;
    padding: 20px;
  }

  .ro-guest-box__icon {
    font-size: 22px;
    margin-bottom: 10px;
    display: block;
  }

  .ro-guest-box__count {
    font-size: 20px;
    font-weight: 800;
    color: #1a2a3a;
    display: block;
    margin-bottom: 2px;
  }

  .ro-guest-box__type {
    font-size: 13px;
    color: #6b7280;
    display: block;
    margin-bottom: 10px;
  }

  .ro-guest-box__price-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 10px;
    border-top: 1px solid #e8d9c4;
  }

  .ro-guest-box__price-lbl { font-size: 12px; color: #6b7280; }
  .ro-guest-box__price-val { font-size: 14px; font-weight: 700; color: #1a2a3a; }
  .ro-guest-box__total-val { font-size: 15px; font-weight: 800; color: #b46b29; }

  /* ── Special request ─────────────────────────────────── */
  .ro-request-box {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
  }

  .ro-request-text {
    font-size: 14px;
    color: #1a2a3a;
    line-height: 1.6;
    flex: 1;
    white-space: pre-wrap;
  }

  .ro-request-delete {
    padding: 6px 14px;
    border-radius: 999px;
    border: 1px solid #e8d9c4;
    background: #fff;
    color: #6b7280;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    transition: border-color .15s, color .15s;
    flex-shrink: 0;
  }

  .ro-request-delete:hover {
    border-color: #d94c2a;
    color: #d94c2a;
  }

  /* ── Total line ──────────────────────────────────────── */
  .ro-total-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    background: #1a2a3a;
    border-radius: 14px;
    margin-bottom: 16px;
  }

  .ro-total-line__lbl { font-size: 15px; font-weight: 600; color: #fff; }
  .ro-total-line__val { font-size: 26px; font-weight: 800; color: #fff; }

  /* ── Fee notice ──────────────────────────────────────── */
  .ro-fee-notice {
    font-size: 13px;
    color: #6b7280;
    line-height: 1.6;
    padding: 16px 20px;
    background: #fff8f0;
    border: 1px solid #e8d9c4;
    border-radius: 10px;
    margin-bottom: 16px;
  }

  /* ── Fee breakdown ───────────────────────────────────── */
  .ro-fee-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    font-size: 14px;
    color: #1a2a3a;
    border-bottom: 1px solid #e8d9c4;
  }

  .ro-fee-row:last-child { border-bottom: none; }

  .ro-fee-row.total {
    padding-top: 12px;
    font-weight: 700;
    font-size: 15px;
  }

  .ro-fee-row.total span:last-child { color: #b46b29; }

  /* ── Action buttons ──────────────────────────────────── */
  .ro-actions {
    display: flex;
    gap: 12px;
    margin-top: 28px;
    flex-wrap: wrap;
  }

  .ro-btn-back {
    flex: 1;
    padding: 14px 20px;
    border-radius: 999px;
    border: 2px solid #1a2a3a;
    background: transparent;
    color: #1a2a3a;
    font-size: 15px;
    font-weight: 600;
    text-align: center;
    text-decoration: none;
    transition: background .15s, color .15s;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .ro-btn-back:hover {
    background: #1a2a3a;
    color: #fff;
  }

  .ro-btn-confirm {
    flex: 2;
    padding: 14px 20px;
    border-radius: 999px;
    background: #b46b29;
    color: #fff;
    font-size: 15px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    text-align: center;
    transition: background .15s;
  }

  .ro-btn-confirm:hover { background: #9a5a22; }

  /* ── Mobile ──────────────────────────────────────────── */
  @media (max-width: 640px) {
    .ro-hero__breadcrumb { padding: 24px 20px; }
    .ro-page { padding: 32px 16px 60px; }
    .ro-guests-row { grid-template-columns: 1fr; }
    .ro-actions { flex-direction: column; }
    .ro-btn-back, .ro-btn-confirm { flex: none; width: 100%; }
    .ro-total-line__val { font-size: 22px; }
  }
</style>

<!-- Hero -->
<section class="ro-hero">
  <?php if (!empty($r->restaurantImagePath)): ?>
    <img
      class="ro-hero__img"
      src="<?= htmlspecialchars($r->restaurantImagePath, ENT_QUOTES) ?>"
      alt="<?= htmlspecialchars($r->restaurantName, ENT_QUOTES) ?>"
    >
  <?php endif; ?>

  <div class="ro-hero__overlay"></div>

  <nav class="ro-hero__breadcrumb" aria-label="Breadcrumb">
    <a href="/">Home</a>
    <span class="sep">›</span>
    <a href="/events/yummy">Yummy</a>
    <span class="sep">›</span>
    <a href="/events/yummy/restaurant/<?= htmlspecialchars($r->slug, ENT_QUOTES) ?>">
      <?= htmlspecialchars($r->restaurantName, ENT_QUOTES) ?>
    </a>
    <span class="sep">›</span>
    <span class="current">Reserve</span>
  </nav>
</section>

<!-- Page body -->
<div class="ro-page">
  <div class="ro-container">

    <h1 class="ro-heading">Reservation Overview</h1>
    <p class="ro-subheading">
      <strong><?= htmlspecialchars($r->restaurantName, ENT_QUOTES) ?></strong>
      &nbsp;·&nbsp;
      <?= htmlspecialchars($vm->festivalDate, ENT_QUOTES) ?>
      &nbsp;·&nbsp;
      Session <?= (int) $vm->sessionNumber ?>
      (<?= htmlspecialchars($vm->sessionStartTime, ENT_QUOTES) ?>–<?= htmlspecialchars($vm->sessionEndTime, ENT_QUOTES) ?>)
    </p>

    <!-- Guest summary boxes -->
    <div class="ro-guests-row">

      <!-- Adults -->
      <div class="ro-guest-box">
        <span class="ro-guest-box__icon">&#128100;</span>
        <span class="ro-guest-box__count"><?= (int) $vm->adults ?>x</span>
        <span class="ro-guest-box__type">Adult</span>
        <div class="ro-guest-box__price-row">
          <span class="ro-guest-box__price-lbl">Per person</span>
          <span class="ro-guest-box__price-val"><?= htmlspecialchars($fmt($vm->adultPriceCents), ENT_QUOTES) ?></span>
        </div>
        <div class="ro-guest-box__price-row">
          <span class="ro-guest-box__price-lbl">Total</span>
          <span class="ro-guest-box__total-val"><?= htmlspecialchars($fmt($vm->adultTotalCents), ENT_QUOTES) ?></span>
        </div>
      </div>

      <!-- Children (only shown when count > 0) -->
      <?php if ($vm->children > 0): ?>
        <div class="ro-guest-box">
          <span class="ro-guest-box__icon">&#128102;</span>
          <span class="ro-guest-box__count"><?= (int) $vm->children ?>x</span>
          <span class="ro-guest-box__type">
            Under <?= $r->childMaxAge !== null ? (int) $r->childMaxAge : '12' ?>
          </span>
          <div class="ro-guest-box__price-row">
            <span class="ro-guest-box__price-lbl">Per person</span>
            <span class="ro-guest-box__price-val"><?= htmlspecialchars($fmt($vm->childPriceCents), ENT_QUOTES) ?></span>
          </div>
          <div class="ro-guest-box__price-row">
            <span class="ro-guest-box__price-lbl">Total</span>
            <span class="ro-guest-box__total-val"><?= htmlspecialchars($fmt($vm->childTotalCents), ENT_QUOTES) ?></span>
          </div>
        </div>
      <?php endif; ?>

    </div>

    <!-- Special request (only shown when non-empty) -->
    <?php if (!empty($vm->specialRequest)): ?>
      <div class="ro-card" id="special-request-card">
        <p class="ro-card-label">Special request</p>
        <div class="ro-request-box">
          <span class="ro-request-text" id="special-request-text">
            <?= htmlspecialchars($vm->specialRequest, ENT_QUOTES) ?>
          </span>
          <button
            type="button"
            class="ro-request-delete"
            id="delete-request-btn"
            aria-label="Remove special request"
          >
            Delete
          </button>
        </div>
      </div>
    <?php endif; ?>

    <!-- Total amount -->
    <div class="ro-total-line">
      <span class="ro-total-line__lbl">Total amount</span>
      <span class="ro-total-line__val"><?= htmlspecialchars($fmt($vm->grandTotalCents), ENT_QUOTES) ?></span>
    </div>

    <!-- Fee notice -->
    <p class="ro-fee-notice">
      To confirm your reservation, a fee of €10 per person is required.
      This amount will be deducted from your final restaurant bill.
    </p>

    <!-- Fee breakdown -->
    <div class="ro-card">
      <p class="ro-card-label">Reservation fee breakdown</p>

      <?php if ($vm->adults > 0): ?>
        <div class="ro-fee-row">
          <span>€10 reservation fee × <?= (int) $vm->adults ?> Adult<?= $vm->adults !== 1 ? 's' : '' ?></span>
          <span><?= htmlspecialchars($fmt($vm->adults * 1000), ENT_QUOTES) ?></span>
        </div>
      <?php endif; ?>

      <?php if ($vm->children > 0): ?>
        <div class="ro-fee-row">
          <span>
            €10 reservation fee × <?= (int) $vm->children ?>
            Under <?= $r->childMaxAge !== null ? (int) $r->childMaxAge : '12' ?>
          </span>
          <span><?= htmlspecialchars($fmt($vm->children * 1000), ENT_QUOTES) ?></span>
        </div>
      <?php endif; ?>

      <div class="ro-fee-row total">
        <span>Total reservation fee</span>
        <span><?= htmlspecialchars($fmt($vm->reservationFeeCents), ENT_QUOTES) ?></span>
      </div>
    </div>

    <!-- Action buttons -->
    <div class="ro-actions">

      <a
        href="/events/yummy/restaurant/<?= htmlspecialchars($r->slug, ENT_QUOTES) ?>#reserve"
        class="ro-btn-back"
      >
        &#8592; Back to Restaurant
      </a>

      <!-- POST form — submits the reservation to the controller -->
      <form
        method="POST"
        action="/events/yummy/reservation/confirm"
        id="confirm-form"
        style="flex:2;display:flex;"
      >
        <?= Csrf::field() ?>
        <input type="hidden" name="restaurant_id"  value="<?= (int) $r->id ?>">
        <input type="hidden" name="festival_date"  value="<?= htmlspecialchars($vm->festivalDateRaw,  ENT_QUOTES) ?>">
        <input type="hidden" name="session_number" value="<?= (int) $vm->sessionNumber ?>">
        <input type="hidden" name="adults"         value="<?= (int) $vm->adults ?>">
        <input type="hidden" name="children"       value="<?= (int) $vm->children ?>">
        <input type="hidden" name="special_request" id="confirm-special-request"
               value="<?= htmlspecialchars($vm->specialRequest, ENT_QUOTES) ?>">

        <button type="submit" class="ro-btn-confirm" style="width:100%;">
          Add to personal program &#128197;
        </button>
      </form>

    </div>

  </div>
</div>

<script>
  /* Delete special request — clears both the visible card and the hidden form field */
  var deleteBtn = document.getElementById('delete-request-btn');
  if (deleteBtn) {
    deleteBtn.addEventListener('click', function () {
      var card = document.getElementById('special-request-card');
      if (card) {
        card.style.display = 'none';
      }
      var field = document.getElementById('confirm-special-request');
      if (field) {
        field.value = '';
      }
    });
  }
</script>

<?php require __DIR__ . '/../../partials/footer.php'; ?>
