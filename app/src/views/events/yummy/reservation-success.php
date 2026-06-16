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
<link rel="stylesheet" href="/assets/yummy/css/yummy-reservation-success.css" />

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
