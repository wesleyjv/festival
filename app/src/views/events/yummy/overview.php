<?php require __DIR__ . '/../../partials/header.php'; ?>

<link rel="stylesheet" href="/assets/yummy/css/globals.css" />
<link rel="stylesheet" href="/assets/yummy/css/styleguide.css" />
<link rel="stylesheet" href="/assets/yummy/css/style.css" />

<style>
  /* Banner (no SVG navbar) */
  .yummy-hero {
    margin: 0;
    width: 100%;
    overflow: hidden;
    position: relative;

    /* Banner image */
    background-image: url("/assets/yummy/image/yummy-hero.jpg");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;

    /* fallback background if image missing */
    background-color: #f6f0e6;
    min-height: 420px;
  }

  .yummy-hero::before {
    /* soft overlay for text readability */
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, rgba(255,248,240,0.92) 0%, rgba(255,248,240,0.75) 40%, rgba(255,248,240,0.15) 100%);
  }

  .yummy-hero-content {
    position: relative;
    padding: 56px 48px;
    max-width: 720px;
  }

  .yummy-pill {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 8px 16px;
    border-radius: 999px;
    background: #b46b29;
    color: #fff;
    font-weight: 600;
    letter-spacing: 0.3px;
    font-size: 14px;
  }

  .yummy-title {
    margin: 18px 0 12px 0;
    font-size: 72px;
    line-height: 0.95;
    font-weight: 800;
    color: #1a2a3a;
  }

  .yummy-subtitle {
    margin: 0 0 22px 0;
    font-size: 18px;
    line-height: 1.5;
    color: #1a2a3a;
    max-width: 560px;
  }

  .yummy-cta {
    display: inline-block;
    padding: 14px 22px;
    border-radius: 999px;
    border: 2px solid #1a2a3a;
    color: #1a2a3a;
    text-decoration: none;
    font-weight: 600;
    background: rgba(255,255,255,0.35);
    backdrop-filter: blur(6px);
  }

  /* Restaurants section */
  .restaurants-section {
    margin: 40px auto 80px auto;
    max-width: 1200px;
    padding: 0 8px;
  }

  .filter-row {
    margin-top: 12px;
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
  }

  .filter-row select {
    padding: 8px 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
  }

  .restaurant-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 16px;
    margin-top: 18px;
  }

  .restaurant-card {
    border: 1px solid #ddd;
    border-radius: 12px;
    padding: 14px;
    background: #fff;
  }

  .restaurant-card h2 {
    margin: 0 0 6px 0;
    font-size: 18px;
  }

  .muted { color: #666; font-size: 0.95rem; }

  /* Breadcrumb section */
  .BREADCRUMB {
    display: flex;
    flex-direction: column;
    width: 100%;
    align-items: flex-start;
    gap: 10px;
    padding: 16px 24px;
    position: relative;
    background-color: #1f1e1d;
    border-top-width: 1px;
    border-top-style: solid;
    border-color: #374151;
  }

  .BREADCRUMB .nav-breadcrumb {
    display: flex;
    height: 16px;
    align-items: flex-start;
    position: relative;
    align-self: stretch;
    width: 100%;
  }

  .BREADCRUMB .ordered-list {
    display: inline-flex;
    align-items: center;
    position: relative;
    align-self: stretch;
    flex: 0 0 auto;
  }

  .BREADCRUMB .item-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    position: relative;
    flex: 0 0 auto;
  }

  .BREADCRUMB .SVG {
    position: relative;
    width: 13.5px;
    height: 12px;
    background-image: url(/assets/yummy/img/image.svg);
    background-size: 100% 100%;
  }

  .BREADCRUMB .text-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 53px;
    height: 16px;
    margin-top: -1.00px;
    font-family: "Inter-Regular", Helvetica;
    font-weight: 400;
    color: #9ca3af;
    font-size: 18px;
    letter-spacing: 0;
    line-height: 16px;
    white-space: nowrap;
  }

  .BREADCRUMB .item {
    display: inline-flex;
    align-items: center;
    position: relative;
    flex: 0 0 auto;
  }

  .BREADCRUMB .margin {
    display: inline-flex;
    flex-direction: column;
    align-items: flex-start;
    padding: 0px 4px;
    position: relative;
    flex: 0 0 auto;
  }

  .BREADCRUMB .vector-wrapper {
    position: relative;
    width: 11px;
    height: 12px;
  }

  .BREADCRUMB .vector {
    position: absolute;
    width: 81.82%;
    height: 100%;
    top: 8.33%;
    left: 22.73%;
  }

  .BREADCRUMB .link {
    display: inline-flex;
    flex-direction: column;
    align-items: flex-start;
    position: relative;
    flex: 0 0 auto;
  }

  .BREADCRUMB .div {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 63px;
    height: 16px;
    margin-top: -1.00px;
    font-family: "Inter-Regular", Helvetica;
    font-weight: 400;
    color: #c4b59d;
    font-size: 18px;
    letter-spacing: 0;
    line-height: 16px;
    white-space: nowrap;
  }

  /* Featured Restaurants Cards Section */
  .featured-container {
    width: 100%;
    display: flex;
    justify-content: center;
    gap: 40px;
    padding: 60px 24px;
    background-color: #faf8f5;
    flex-wrap: wrap;
  }

  .featured-card {
    width: 320px;
    position: relative;
    background-color: rgba(255, 255, 255, 0.68);
    border-radius: 12px;
    box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.05);
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 40px 30px;
    text-align: center;
    gap: 16px;
  }

  .featured-card.card-alt {
    width: 320px;
  }

  .icon-wrapper {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background-color: #2c3e50;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .featured-card .card-icon {
    width: 36px;
    height: 36px;
    object-fit: contain;
    filter: brightness(0) invert(1);
  }

  .featured-card .card-title {
    font-family: "Josefin Sans-Bold", Helvetica;
    font-weight: 700;
    color: #111827;
    font-size: 20px;
    letter-spacing: 0;
    line-height: 28px;
    margin: 0;
  }

  .featured-card .card-content {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
  }

  .featured-card .card-text {
    font-family: "Inter-Regular", Helvetica;
    font-weight: 400;
    color: #4b5563;
    font-size: 16px;
    letter-spacing: 0.80px;
    line-height: 30px;
    text-align: center;
    margin: 0;
  }
</style>

<!-- ✅ HERO BANNER ONLY -->
<section class="yummy-hero">
  <div class="yummy-hero-content">
    <div class="yummy-pill">JULY 23–26, 2026</div>

    <h1 class="yummy-title">YUMMY!<br>GOURMET WITH<br>A TWIST</h1>

    <p class="yummy-subtitle">
      A curated culinary experience featuring seven restaurants, exclusive festival-only menus.
    </p>

    <a class="yummy-cta" href="#restaurants">Explore Restaurants</a>
  </div>
</section>

<!-- ✅ BREADCRUMB SECTION -->
<div class="BREADCRUMB">
  <div class="nav-breadcrumb">
    <div class="ordered-list">
      <a href="/" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; color: #9ca3af;">
        <div class="SVG"></div>
        <div class="text-wrapper">Home</div>
      </a>
      
      <div style="display: inline-flex; align-items: center; gap: 8px; padding: 0 8px; color: #9ca3af;">
        >
      </div>
      
      <a href="/events/yummy" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; color: #c4b59d;">
        <div class="link"><div class="div">Yummy</div></div>
      </a>
    </div>
  </div>
</div>

<!-- ✅ FEATURED RESTAURANTS CARDS SECTION -->
<div class="featured-container">
  <div class="featured-card">
    <div class="icon-wrapper"><img class="card-icon" src="/assets/yummy/image/IconSvg_iconCarrier.png" alt="Featured Restaurants" /></div>
    <div class="card-title">Featured Restaurants</div>
    <div class="card-content">
      <p class="card-text">Seven participating Haarlem restaurants offer a special festival experience, each serving a unique menu created exclusively for THE FESTIVAL.</p>
    </div>
  </div>

  <div class="featured-card card-alt">
    <div class="icon-wrapper"><img class="card-icon" src="/assets/yummy/image/Room_iconSvg.png" alt="Festival Only Menus" /></div>
    <div class="card-title">Festival Only Menus</div>
    <div class="card-content">
      <p class="card-text">Each restaurant prepares one special menu designed specifically for THE FESTIVAL. These menus are available only during the festival days and offer a curated dining experience.</p>
    </div>
  </div>

  <div class="featured-card card-alt">
    <div class="icon-wrapper"><img class="card-icon" src="/assets/yummy/image/IconSvg_schdual.png" alt="Multiple Sessions" /></div>
    <div class="card-title">Multiple Sessions</div>
    <div class="card-content">
      <p class="card-text">Restaurants offer several dining sessions each evening, giving visitors the flexibility to choose a time that fits their festival schedule. Seats are limited, and reservations are required.</p>
    </div>
  </div>
</div>

<!-- ✅ DYNAMIC RESTAURANTS LIST -->
<section id="restaurants" class="restaurants-section">
  <h2>Explore Restaurants</h2>
  <p class="muted">Taste the finest culinary experiences in Haarlem.</p>

  <form method="GET" class="filter-row">
    <label for="cuisine"><strong>Cuisine:</strong></label>

    <select name="cuisine" id="cuisine" onchange="this.form.submit()">
      <option value="">All</option>

      <?php
      // If you already pass $cuisines from controller, this uses it.
      // Otherwise it falls back to a simple list.
      $availableCuisines = $cuisines ?? [
        ['cuisine' => 'Italian'],
        ['cuisine' => 'Asian'],
        ['cuisine' => 'Mexican'],
      ];

      foreach ($availableCuisines as $row):
        $c = $row['cuisine'];
      ?>
        <option value="<?= htmlspecialchars($c) ?>" <?= (($_GET['cuisine'] ?? '') === $c) ? 'selected' : '' ?>>
          <?= htmlspecialchars($c) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <noscript><button type="submit">Filter</button></noscript>
  </form>

  <hr>

  <?php if (empty($restaurants)): ?>
    <p>No restaurants found.</p>
  <?php else: ?>
    <div class="restaurant-grid">
      <?php foreach ($restaurants as $restaurant): ?>
        <div class="restaurant-card">
          <h2><?= htmlspecialchars($restaurant['restaurant_name']) ?></h2>
          <p class="muted"><strong>Cuisine:</strong> <?= htmlspecialchars($restaurant['cuisine'] ?? 'Unknown') ?></p>

          <?php if (!empty($restaurant['description'])): ?>
            <p><?= nl2br(htmlspecialchars($restaurant['description'])) ?></p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
<?php require __DIR__ . '/../../partials/footer.php'; ?>
