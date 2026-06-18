<?php
/** @var \App\ViewModels\YummyDetailViewModel $viewModel */
$restaurant = $viewModel->restaurant;
$menuItems  = $viewModel->menuItems;

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
<link rel="stylesheet" href="/assets/yummy/css/yummy-detail.css" />


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

      <?php if (!empty($restaurant->about)): ?>
        <div class="d-about__text"><?= $restaurant->about ?></div>
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

      <?php if (!empty($_GET['error'])): ?>
        <div class="d-reserve__error" style="background:#fdecea;border:1px solid #d94c2a;color:#d94c2a;border-radius:10px;padding:12px 16px;margin-bottom:20px;font-size:14px;font-weight:600;">
          <?= htmlspecialchars($_GET['error'], ENT_QUOTES) ?>
        </div>
      <?php endif; ?>

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
        <p class="d-session-seats" id="availability-msg" style="margin:-4px 0 20px;"></p>
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
        <div class="d-chef__bio"><?= $restaurant->chefBio ?></div>
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
              <div class="d-menu-card__desc"><?= $item->description ?></div>
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
  const ADULT_CENTS   = <?= (int) $adultPriceCents ?>;
  const CHILD_CENTS   = <?= (int) $childPriceCents ?>;
  const RESTAURANT_ID = <?= (int) $restaurant->id ?>;

  let adultsCount    = 0;
  let kidsCount      = 0;
  let selectedDate   = null;
  let selectedSession = null;
  let remainingSeats = null;

  /* ── Helpers ─────────────────────────────────────────── */
  function checkReady() {
    const guests = adultsCount + kidsCount;
    const capacityOk = remainingSeats === null || (remainingSeats > 0 && guests <= remainingSeats);
    const ready = selectedDate !== null && selectedSession !== null && guests > 0 && capacityOk;
    document.getElementById('reservation-submit').disabled = !ready;
  }

  /* ── Live availability via /api/yummy/availability ────── */
  function updateAvailability() {
    const msg = document.getElementById('availability-msg');
    if (!msg) {
      return;
    }

    if (selectedDate === null || selectedSession === null) {
      remainingSeats = null;
      msg.textContent = '';
      checkReady();
      return;
    }

    const params = new URLSearchParams({
      restaurant_id: RESTAURANT_ID,
      festival_date: selectedDate,
      session_number: selectedSession,
    });

    fetch('/api/yummy/availability?' + params.toString())
      .then(function (response) { return response.json(); })
      .then(function (data) {
        if (typeof data.remaining !== 'number') {
          remainingSeats = null;
          msg.textContent = '';
          return;
        }
        remainingSeats = data.remaining;
        msg.textContent = data.remaining > 0
          ? '\u{1F465} ' + data.remaining + ' seat(s) left for this session'
          : 'This session is fully booked';
      })
      .catch(function () {
        remainingSeats = null;
        msg.textContent = '';
      })
      .finally(function () {
        checkReady();
      });
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
      updateAvailability();
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
      updateAvailability();
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
