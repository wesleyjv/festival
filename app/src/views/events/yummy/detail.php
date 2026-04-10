<?php
/** @var \App\ViewModels\YummyDetailViewModel $viewModel */
$restaurant = $viewModel->restaurant;
$menuItems  = $viewModel->menuItems;
$content    = $viewModel->pageContent;

/* --- Session time helpers --- */
$sessions   = [];
$startTimes = [
    $restaurant->sessionOneStartTime,
    $restaurant->sessionTwoStartTime,
    $restaurant->sessionThreeStartTime,
];

foreach ($startTimes as $i => $startTime) {
    if (empty($startTime)) {
        continue;
    }
    $start = \DateTime::createFromFormat('H:i:s', $startTime)
           ?: \DateTime::createFromFormat('H:i', $startTime);
    if (!$start) {
        continue;
    }
    $end = clone $start;
    if ($restaurant->sessionDurationMinutes !== null) {
        $end->modify('+' . $restaurant->sessionDurationMinutes . ' minutes');
    }
    $sessions[] = [
        'number' => $i + 1,
        'start'  => $start->format('H:i'),
        'end'    => $restaurant->sessionDurationMinutes !== null ? $end->format('H:i') : null,
    ];
}

/* --- Pricing helpers --- */
$adultPriceFormatted = $restaurant->adultPriceCents !== null
    ? '€' . number_format($restaurant->adultPriceCents / 100, 2, ',', '.')
    : null;
$childPriceFormatted = $restaurant->childPriceCents !== null
    ? '€' . number_format($restaurant->childPriceCents / 100, 2, ',', '.')
    : null;
$adultPriceCents = $restaurant->adultPriceCents ?? 0;
$childPriceCents = $restaurant->childPriceCents ?? 0;

/* --- About paragraphs --- */
$aboutParagraphs = [];
if (!empty($restaurant->about)) {
    $parts = preg_split('/\n\s*\n/', trim($restaurant->about), 2);
    foreach ($parts as $p) {
        $trimmed = trim($p);
        if ($trimmed !== '') {
            $aboutParagraphs[] = $trimmed;
        }
    }
}

/* --- Duration display --- */
$durationDisplay = null;
if ($restaurant->sessionDurationMinutes !== null) {
    $hrs             = $restaurant->sessionDurationMinutes / 60;
    $durationDisplay = rtrim(rtrim(number_format($hrs, 1, '.', ''), '0'), '.');
}

require __DIR__ . '/../../partials/header.php';
?>

<link rel="stylesheet" href="/assets/yummy/css/globals.css" />
<link rel="stylesheet" href="/assets/yummy/css/styleguide.css" />
<link rel="stylesheet" href="/assets/yummy/css/style.css" />

<style>
  /* ── Shared tokens ─────────────────────────────────────── */
  :root {
    --dk:   #1a2a3a;
    --acc:  #b46b29;
    --red:  #d94c2a;
    --tag:  #e0c4a4;
    --card: #f5efe7;
    --bg:   #faf8f5;
    --muted:#4b5563;
  }

  /* ── HERO ──────────────────────────────────────────────── */
  .d-hero {
    position: relative;
    width: 100%;
    min-height: 480px;
    display: flex;
    align-items: flex-end;
    overflow: hidden;
    background-color: #1a2a3a;
  }

  .d-hero__img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
  }

  .d-hero__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.78) 0%, rgba(0,0,0,0.25) 55%, transparent 100%);
  }

  .d-hero__breadcrumb {
    position: relative;
    padding: 40px 48px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: rgba(255,255,255,0.75);
    flex-wrap: wrap;
  }

  .d-hero__breadcrumb a {
    color: rgba(255,255,255,0.75);
    text-decoration: none;
  }

  .d-hero__breadcrumb a:hover { color: #fff; }

  .d-hero__breadcrumb .sep     { color: rgba(255,255,255,0.45); }
  .d-hero__breadcrumb .current { color: #fff; font-weight: 600; }

  /* ── ABOUT ─────────────────────────────────────────────── */
  .d-about {
    background: #fff;
    padding: 80px 48px;
  }

  .d-about__inner {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 420px;
    gap: 64px;
    align-items: center;
  }

  .d-about__heading {
    font-size: 36px;
    font-weight: 800;
    color: #1a2a3a;
    margin: 0 0 20px 0;
    line-height: 1.15;
  }

  .d-about__text {
    color: #4b5563;
    font-size: 16px;
    line-height: 1.75;
    margin: 0 0 16px 0;
  }

  .d-about__text:last-of-type { margin-bottom: 32px; }

  .d-about__pills {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
  }

  .d-about__pill {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 14px 22px;
    border-radius: 999px;
    background: #f5efe7;
    border: 1px solid #e0c4a4;
    min-width: 100px;
  }

  .d-about__pill-val {
    font-size: 22px;
    font-weight: 800;
    color: #1a2a3a;
    line-height: 1;
  }

  .d-about__pill-lbl {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: #4b5563;
    margin-top: 4px;
  }

  .d-about__img {
    width: 100%;
    height: 380px;
    object-fit: cover;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.12);
  }

  .d-about__img-placeholder {
    width: 100%;
    height: 380px;
    border-radius: 16px;
    background: #f5efe7;
  }

  /* ── RESERVATION ───────────────────────────────────────── */
  .d-reserve {
    background: #faf8f5;
    padding: 80px 48px;
  }

  .d-reserve__inner {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 420px 1fr;
    gap: 64px;
    align-items: start;
  }

  .d-reserve__img-wrap {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    height: 520px;
    background: #e0c4a4;
  }

  .d-reserve__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .d-reserve__img-label {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 20px 24px;
    background: linear-gradient(to top, rgba(0,0,0,0.65) 0%, transparent 100%);
    color: #fff;
    font-size: 20px;
    font-weight: 700;
  }

  .d-reserve__heading {
    font-size: 32px;
    font-weight: 800;
    color: #1a2a3a;
    margin: 0 0 8px 0;
  }

  .d-reserve__sub {
    color: #4b5563;
    font-size: 15px;
    margin: 0 0 28px 0;
    line-height: 1.55;
  }

  .d-section-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: #b46b29;
    margin: 0 0 10px 0;
  }

  /* Date pills */
  .d-date-pills {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 28px;
  }

  .d-date-pill {
    padding: 8px 16px;
    border-radius: 999px;
    border: 2px solid #e0c4a4;
    background: #fff;
    color: #1a2a3a;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: background .15s, border-color .15s, color .15s;
  }

  .d-date-pill.active,
  .d-date-pill:hover {
    background: #1a2a3a;
    border-color: #1a2a3a;
    color: #fff;
  }

  /* Session cards */
  .d-sessions {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 28px;
  }

  .d-session-card {
    display: grid;
    grid-template-columns: auto 1fr auto auto;
    gap: 16px;
    align-items: center;
    padding: 14px 18px;
    border-radius: 12px;
    border: 2px solid #e5e7eb;
    background: #fff;
    cursor: pointer;
    transition: border-color .15s, background .15s;
  }

  .d-session-card.active {
    border-color: #1a2a3a;
    background: #f0f4f8;
  }

  .d-session-card:hover:not(.active) {
    border-color: #b46b29;
    background: #fdf7f2;
  }

  .d-session-num {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #4b5563;
    white-space: nowrap;
  }

  .d-session-time {
    font-size: 16px;
    font-weight: 700;
    color: #1a2a3a;
  }

  .d-session-seats {
    font-size: 13px;
    color: #4b5563;
    white-space: nowrap;
  }

  .d-session-price {
    text-align: right;
    white-space: nowrap;
  }

  .d-session-price span {
    display: block;
    font-size: 13px;
    color: #1a2a3a;
    font-weight: 600;
  }

  .d-session-price span.child {
    font-size: 11px;
    color: #4b5563;
    font-weight: 400;
  }

  /* Guest counter */
  .d-guests {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 20px;
  }

  .d-guest-row {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 10px 14px;
    flex: 1;
    min-width: 160px;
  }

  .d-guest-lbl {
    flex: 1;
  }

  .d-guest-lbl strong {
    display: block;
    font-size: 14px;
    color: #1a2a3a;
  }

  .d-guest-lbl span {
    font-size: 11px;
    color: #4b5563;
  }

  .d-counter {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .d-counter button {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: 2px solid #1a2a3a;
    background: #fff;
    color: #1a2a3a;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    line-height: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background .12s, color .12s;
  }

  .d-counter button:hover {
    background: #1a2a3a;
    color: #fff;
  }

  .d-counter__val {
    font-size: 16px;
    font-weight: 700;
    color: #1a2a3a;
    min-width: 20px;
    text-align: center;
  }

  /* Total */
  .d-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 18px;
    background: #1a2a3a;
    color: #fff;
    border-radius: 12px;
    margin-bottom: 20px;
  }

  .d-total__lbl { font-size: 14px; font-weight: 600; }
  .d-total__val { font-size: 22px; font-weight: 800; }

  /* Special request */
  .d-textarea {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    font-size: 14px;
    resize: vertical;
    font-family: inherit;
    color: #1a2a3a;
    background: #fff;
    margin-bottom: 20px;
    box-sizing: border-box;
  }

  .d-textarea:focus {
    outline: none;
    border-color: #b46b29;
  }

  .d-cta-btn {
    display: block;
    width: 100%;
    padding: 16px;
    border-radius: 999px;
    background: #1a2a3a;
    color: #fff;
    font-size: 16px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    text-align: center;
    transition: background .15s, opacity .15s;
  }

  .d-cta-btn:hover:not(:disabled) {
    background: #2d3f52;
  }

  .d-cta-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
  }

  /* ── CHEF ──────────────────────────────────────────────── */
  .d-chef {
    background: #fff;
    padding: 80px 48px;
  }

  .d-chef__inner {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: 64px;
    align-items: center;
  }

  .d-chef__img {
    width: 100%;
    height: 440px;
    object-fit: cover;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.12);
  }

  .d-chef__bubble {
    background: #f5efe7;
    border-radius: 16px;
    padding: 40px;
    position: relative;
  }

  .d-chef__bubble::before {
    content: '';
    position: absolute;
    left: -20px;
    top: 60px;
    width: 0;
    height: 0;
    border-top: 14px solid transparent;
    border-bottom: 14px solid transparent;
    border-right: 20px solid #f5efe7;
  }

  .d-chef__name  { font-size: 26px; font-weight: 800; color: #1a2a3a; margin: 0 0 4px 0; }
  .d-chef__title {
    font-size: 14px;
    font-weight: 600;
    color: #b46b29;
    text-transform: uppercase;
    letter-spacing: .05em;
    margin: 0 0 20px 0;
  }

  .d-chef__bio { color: #4b5563; font-size: 15px; line-height: 1.75; margin: 0 0 14px 0; }

  .d-chef__badges { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 24px; }

  .d-chef__badge {
    padding: 6px 14px;
    border-radius: 999px;
    background: #1a2a3a;
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: .03em;
  }

  /* ── MENU ──────────────────────────────────────────────── */
  .d-menu {
    background: #faf8f5;
    padding: 80px 48px;
  }

  .d-menu__inner { max-width: 1200px; margin: 0 auto; }

  .d-menu__heading { font-size: 32px; font-weight: 800; color: #1a2a3a; margin: 0 0 32px 0; }

  .d-menu__grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
  }

  .d-menu-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.06);
  }

  .d-menu-card__img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
  }

  .d-menu-card__img-placeholder {
    width: 100%;
    height: 200px;
    background: #e0c4a4;
    display: block;
  }

  .d-menu-card__body  { padding: 18px 20px; }
  .d-menu-card__name  { font-size: 17px; font-weight: 700; color: #1a2a3a; margin: 0 0 6px 0; }
  .d-menu-card__desc  { font-size: 14px; color: #4b5563; line-height: 1.6; margin: 0; }

  /* ── Mobile ────────────────────────────────────────────── */
  @media (max-width: 768px) {
    .d-hero { min-height: 300px; }
    .d-hero__breadcrumb { padding: 24px 20px; }

    .d-about { padding: 48px 20px; }
    .d-about__inner { grid-template-columns: 1fr; gap: 36px; }
    .d-about__img, .d-about__img-placeholder { height: 240px; order: -1; }
    .d-about__heading { font-size: 26px; }

    .d-reserve { padding: 48px 20px; }
    .d-reserve__inner { grid-template-columns: 1fr; gap: 36px; }
    .d-reserve__img-wrap { height: 260px; }
    .d-session-card { grid-template-columns: auto 1fr; }
    .d-session-seats { display: none; }

    .d-chef { padding: 48px 20px; }
    .d-chef__inner { grid-template-columns: 1fr; gap: 36px; }
    .d-chef__img { height: 280px; }
    .d-chef__bubble::before { display: none; }

    .d-menu { padding: 48px 20px; }
    .d-menu__grid { grid-template-columns: 1fr; }
  }
</style>

<!-- HERO -->
<section class="d-hero">
  <?php if (!empty($restaurant->restaurantImagePath)): ?>
    <img
      class="d-hero__img"
      src="<?= htmlspecialchars($restaurant->restaurantImagePath, ENT_QUOTES) ?>"
      alt="<?= htmlspecialchars($restaurant->restaurantName, ENT_QUOTES) ?>"
    >
  <?php endif; ?>

  <div class="d-hero__overlay"></div>

  <nav class="d-hero__breadcrumb" aria-label="Breadcrumb">
    <a href="/">Home</a>
    <span class="sep">›</span>
    <a href="/events/yummy">Yummy</a>
    <span class="sep">›</span>
    <span class="current"><?= htmlspecialchars($restaurant->restaurantName, ENT_QUOTES) ?></span>
  </nav>
</section>

<!-- ABOUT -->
<section class="d-about">
  <div class="d-about__inner">

    <div>
      <h2 class="d-about__heading">About <?= htmlspecialchars($restaurant->restaurantName, ENT_QUOTES) ?></h2>

      <?php if (!empty($aboutParagraphs)): ?>
        <?php foreach ($aboutParagraphs as $para): ?>
          <p class="d-about__text"><?= nl2br(htmlspecialchars($para, ENT_QUOTES)) ?></p>
        <?php endforeach; ?>
      <?php elseif (!empty($restaurant->shortDescription)): ?>
        <p class="d-about__text"><?= nl2br(htmlspecialchars($restaurant->shortDescription, ENT_QUOTES)) ?></p>
      <?php endif; ?>

      <div class="d-about__pills">
        <?php if ($restaurant->sessionCount !== null): ?>
          <div class="d-about__pill">
            <span class="d-about__pill-val"><?= (int) $restaurant->sessionCount ?></span>
            <span class="d-about__pill-lbl">Sessions</span>
          </div>
        <?php endif; ?>

        <?php if ($durationDisplay !== null): ?>
          <div class="d-about__pill">
            <span class="d-about__pill-val"><?= htmlspecialchars($durationDisplay, ENT_QUOTES) ?></span>
            <span class="d-about__pill-lbl">hrs Experience</span>
          </div>
        <?php endif; ?>

        <div class="d-about__pill">
          <span class="d-about__pill-val">2–10</span>
          <span class="d-about__pill-lbl">Group Size</span>
        </div>
      </div>
    </div>

    <?php if (!empty($restaurant->aboutImagePath)): ?>
      <img
        class="d-about__img"
        src="<?= htmlspecialchars($restaurant->aboutImagePath, ENT_QUOTES) ?>"
        alt="<?= htmlspecialchars($restaurant->restaurantName, ENT_QUOTES) ?> dining"
      >
    <?php else: ?>
      <div class="d-about__img-placeholder" aria-hidden="true"></div>
    <?php endif; ?>

  </div>
</section>

<!-- RESERVATION -->
<section class="d-reserve" id="reserve">
  <div class="d-reserve__inner">

    <div class="d-reserve__img-wrap">
      <?php if (!empty($restaurant->reservationImagePath)): ?>
        <img
          class="d-reserve__img"
          src="<?= htmlspecialchars($restaurant->reservationImagePath, ENT_QUOTES) ?>"
          alt="<?= htmlspecialchars($restaurant->restaurantName, ENT_QUOTES) ?> reservation"
        >
      <?php endif; ?>
      <div class="d-reserve__img-label"><?= htmlspecialchars($restaurant->restaurantName, ENT_QUOTES) ?></div>
    </div>

    <!-- Reservation form — submits via GET to the overview page -->
    <form method="GET" action="/events/yummy/reservation/overview" id="reservation-form">

      <!-- Static params set by PHP -->
      <input type="hidden" name="restaurant_id" value="<?= (int) $restaurant->id ?>">

      <!-- Params driven by JS selections -->
      <input type="hidden" name="festival_date"  id="input-festival-date"  value="">
      <input type="hidden" name="session_number" id="input-session-number" value="">
      <input type="hidden" name="adults"         id="input-adults"         value="0">
      <input type="hidden" name="children"       id="input-children"       value="0">

      <h2 class="d-reserve__heading">Reserve Your Table</h2>
      <p class="d-reserve__sub">Choose your date, session, and guests to secure your Yummy dining experience.</p>

      <!-- Date pills -->
      <p class="d-section-label">Select date</p>
      <div class="d-date-pills">
        <button class="d-date-pill" type="button" data-date="2026-07-23">Wed 23 July</button>
        <button class="d-date-pill" type="button" data-date="2026-07-24">Thu 24 July</button>
        <button class="d-date-pill" type="button" data-date="2026-07-25">Fri 25 July</button>
        <button class="d-date-pill" type="button" data-date="2026-07-26">Sat 26 July</button>
      </div>

      <!-- Session cards -->
      <?php if (!empty($sessions)): ?>
        <p class="d-section-label">Select session</p>
        <div class="d-sessions" id="sessions">
          <?php foreach ($sessions as $session): ?>
            <div
              class="d-session-card"
              data-session="<?= (int) $session['number'] ?>"
              tabindex="0"
              role="button"
              aria-pressed="false"
            >
              <span class="d-session-num">Session <?= (int) $session['number'] ?></span>
              <span class="d-session-time">
                <?= htmlspecialchars($session['start'], ENT_QUOTES) ?>
                <?php if ($session['end'] !== null): ?>
                  – <?= htmlspecialchars($session['end'], ENT_QUOTES) ?>
                <?php endif; ?>
              </span>
              <?php if ($restaurant->seats !== null): ?>
                <span class="d-session-seats">&#128100; <?= (int) $restaurant->seats ?> seats</span>
              <?php else: ?>
                <span class="d-session-seats"></span>
              <?php endif; ?>
              <div class="d-session-price">
                <?php if ($adultPriceFormatted !== null): ?>
                  <span>Adult <?= htmlspecialchars($adultPriceFormatted, ENT_QUOTES) ?></span>
                <?php endif; ?>
                <?php if ($childPriceFormatted !== null): ?>
                  <span class="child">
                    Child <?= htmlspecialchars($childPriceFormatted, ENT_QUOTES) ?>
                    <?php if ($restaurant->childMaxAge !== null): ?>
                      (0–<?= (int) $restaurant->childMaxAge ?>)
                    <?php endif; ?>
                  </span>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- Guest counter -->
      <p class="d-section-label">Guests</p>
      <div class="d-guests">
        <div class="d-guest-row">
          <div class="d-guest-lbl">
            <strong>Adults</strong>
            <span>Age 18+</span>
          </div>
          <div class="d-counter">
            <button type="button" onclick="adjustGuests('adults', -1)" aria-label="Remove adult">−</button>
            <span class="d-counter__val" id="adults-val">0</span>
            <button type="button" onclick="adjustGuests('adults', 1)" aria-label="Add adult">+</button>
          </div>
        </div>

        <div class="d-guest-row">
          <div class="d-guest-lbl">
            <strong>Kids</strong>
            <span>0–<?= $restaurant->childMaxAge !== null ? (int) $restaurant->childMaxAge : '12' ?></span>
          </div>
          <div class="d-counter">
            <button type="button" onclick="adjustGuests('kids', -1)" aria-label="Remove child">−</button>
            <span class="d-counter__val" id="kids-val">0</span>
            <button type="button" onclick="adjustGuests('kids', 1)" aria-label="Add child">+</button>
          </div>
        </div>
      </div>

      <!-- Live total -->
      <div class="d-total">
        <span class="d-total__lbl">Total</span>
        <span class="d-total__val" id="total-val">€0,00</span>
      </div>

      <!-- Special request — included directly in GET form -->
      <textarea
        class="d-textarea"
        name="special_request"
        rows="3"
        placeholder="Any dietary requirements or special requests?"
        aria-label="Special request"
      ></textarea>

      <!-- Submit — disabled until date + session + ≥1 guest selected -->
      <button type="submit" class="d-cta-btn" id="reservation-submit" disabled>
        Go to Overview →
      </button>

    </form>

  </div>
</section>

<!-- CHEF -->
<?php if (!empty($restaurant->chefName)): ?>
<section class="d-chef">
  <div class="d-chef__inner">

    <?php if (!empty($restaurant->chefImagePath)): ?>
      <img
        class="d-chef__img"
        src="<?= htmlspecialchars($restaurant->chefImagePath, ENT_QUOTES) ?>"
        alt="Chef <?= htmlspecialchars($restaurant->chefName, ENT_QUOTES) ?>"
      >
    <?php else: ?>
      <div style="width:380px;height:440px;border-radius:16px;background:#e0c4a4;" aria-hidden="true"></div>
    <?php endif; ?>

    <div class="d-chef__bubble">
      <h2 class="d-chef__name"><?= htmlspecialchars($restaurant->chefName, ENT_QUOTES) ?></h2>

      <?php if (!empty($restaurant->chefTitle)): ?>
        <p class="d-chef__title"><?= htmlspecialchars($restaurant->chefTitle, ENT_QUOTES) ?></p>
      <?php endif; ?>

      <?php if (!empty($restaurant->chefBio)): ?>
        <?php
          $bioParagraphs = preg_split('/\n\s*\n/', trim($restaurant->chefBio), 2);
          foreach ($bioParagraphs as $bp):
            $bp = trim($bp);
            if ($bp !== ''):
        ?>
          <p class="d-chef__bio"><?= nl2br(htmlspecialchars($bp, ENT_QUOTES)) ?></p>
        <?php
            endif;
          endforeach;
        ?>
      <?php endif; ?>

      <div class="d-chef__badges">
        <span class="d-chef__badge">Michelin Recognized</span>
        <span class="d-chef__badge">10+ Years Experience</span>
      </div>
    </div>

  </div>
</section>
<?php endif; ?>

<!-- FESTIVAL MENU -->
<?php if (!empty($menuItems)): ?>
<section class="d-menu">
  <div class="d-menu__inner">
    <h2 class="d-menu__heading"><?= htmlspecialchars($restaurant->restaurantName, ENT_QUOTES) ?> Festival Menu</h2>

    <div class="d-menu__grid">
      <?php foreach ($menuItems as $item): ?>
        <div class="d-menu-card">
          <?php if (!empty($item->imagePath)): ?>
            <img
              class="d-menu-card__img"
              src="<?= htmlspecialchars($item->imagePath, ENT_QUOTES) ?>"
              alt="<?= htmlspecialchars($item->name, ENT_QUOTES) ?>"
            >
          <?php else: ?>
            <div class="d-menu-card__img-placeholder" aria-hidden="true"></div>
          <?php endif; ?>
          <div class="d-menu-card__body">
            <h3 class="d-menu-card__name"><?= htmlspecialchars($item->name, ENT_QUOTES) ?></h3>
            <?php if (!empty($item->description)): ?>
              <p class="d-menu-card__desc"><?= htmlspecialchars($item->description, ENT_QUOTES) ?></p>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>
<?php endif; ?>

<script>
  /* Price data from PHP */
  const ADULT_CENTS = <?= (int) $adultPriceCents ?>;
  const CHILD_CENTS = <?= (int) $childPriceCents ?>;

  let adultsCount    = 0;
  let kidsCount      = 0;
  let selectedDate   = null;
  let selectedSession = null;

  /* ── Helpers ─────────────────────────────────────────── */
  function checkReady() {
    const ready = selectedDate !== null && selectedSession !== null && (adultsCount + kidsCount > 0);
    document.getElementById('reservation-submit').disabled = !ready;
  }

  function updateTotal() {
    const totalCents = adultsCount * ADULT_CENTS + kidsCount * CHILD_CENTS;
    const euros      = Math.floor(totalCents / 100);
    const cents      = totalCents % 100;
    document.getElementById('total-val').textContent =
      '€' + euros + ',' + String(cents).padStart(2, '0');
  }

  /* ── Guest counter ───────────────────────────────────── */
  function adjustGuests(type, delta) {
    if (type === 'adults') {
      adultsCount = Math.max(0, adultsCount + delta);
      document.getElementById('adults-val').textContent = adultsCount;
      document.getElementById('input-adults').value     = adultsCount;
    } else {
      kidsCount = Math.max(0, kidsCount + delta);
      document.getElementById('kids-val').textContent  = kidsCount;
      document.getElementById('input-children').value  = kidsCount;
    }
    updateTotal();
    checkReady();
  }

  /* ── Date pill toggle ────────────────────────────────── */
  document.querySelectorAll('.d-date-pill').forEach(function (pill) {
    pill.addEventListener('click', function () {
      document.querySelectorAll('.d-date-pill').forEach(function (p) {
        p.classList.remove('active');
      });
      this.classList.add('active');
      selectedDate = this.dataset.date;
      document.getElementById('input-festival-date').value = selectedDate;
      checkReady();
    });
  });

  /* ── Session card selection ──────────────────────────── */
  document.querySelectorAll('.d-session-card').forEach(function (card) {
    card.addEventListener('click', function () {
      document.querySelectorAll('.d-session-card').forEach(function (c) {
        c.classList.remove('active');
        c.setAttribute('aria-pressed', 'false');
      });
      this.classList.add('active');
      this.setAttribute('aria-pressed', 'true');
      selectedSession = this.dataset.session;
      document.getElementById('input-session-number').value = selectedSession;
      checkReady();
    });

    card.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        this.click();
      }
    });
  });
</script>

<?php require __DIR__ . '/../../partials/footer.php'; ?>
