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
<link rel="stylesheet" href="/assets/yummy/css/yummy-reservation-overview.css" />

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
