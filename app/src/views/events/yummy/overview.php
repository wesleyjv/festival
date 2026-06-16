<?php
/** @var array $yummyContent */
require __DIR__ . '/../../partials/header.php';
?>

<?php
/** @var \App\ViewModels\YummyOverviewViewModel $viewModel */
$content = $viewModel->content;
$restaurants = $viewModel->restaurants;
$cuisines = $viewModel->cuisines;
$selectedCuisine = $viewModel->selectedCuisine;
?>

<link rel="stylesheet" href="/assets/yummy/css/globals.css" />
<link rel="stylesheet" href="/assets/yummy/css/styleguide.css" />
<link rel="stylesheet" href="/assets/yummy/css/yummy-overview.css" />

<?php
$heroImage = $content['hero_image'] ?? '/assets/yummy/image/yummy-hero.jpg';
?>

<section class="yummy-hero" style="background-image: url('<?= htmlspecialchars($heroImage, ENT_QUOTES) ?>')">
  <div class="yummy-hero-content">
    <div class="yummy-pill"><?= htmlspecialchars($content['hero_date'] ?? '') ?></div>

<h1 class="yummy-title"><?= nl2br(htmlspecialchars($content['hero_title'] ?? '')) ?></h1>

<p class="yummy-subtitle">
  <?= htmlspecialchars($content['hero_subtitle'] ?? '') ?>
</p>

    <a class="yummy-cta" href="#restaurants"><?= htmlspecialchars($content['explore_heading'] ?? 'Explore Restaurants') ?></a>
  </div>
</section>

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

<div class="featured-container">
  <div class="featured-card">
    <div class="icon-wrapper"><img class="card-icon" src="/assets/yummy/image/IconSvg_iconCarrier.png" alt="Featured Restaurants" /></div>
    <div class="card-title"><?= htmlspecialchars($content['feature_card_one_title'] ?? '') ?></div>
    <div class="card-content">
      <p class="card-text"><?= htmlspecialchars($content['feature_card_one_text'] ?? '') ?></p>
    </div>
  </div>

  <div class="featured-card card-alt">
    <div class="icon-wrapper"><img class="card-icon" src="/assets/yummy/image/Room_iconSvg.png" alt="Festival Only Menus" /></div>
    <div class="card-title"><?= htmlspecialchars($content['feature_card_two_title'] ?? '') ?></div>
    <div class="card-content">
      <p class="card-text"><?= htmlspecialchars($content['feature_card_two_text'] ?? '') ?></p>
    </div>
  </div>

  <div class="featured-card card-alt">
    <div class="icon-wrapper"><img class="card-icon" src="/assets/yummy/image/IconSvg_schdual.png" alt="Multiple Sessions" /></div>
    <div class="card-title"><?= htmlspecialchars($content['feature_card_three_title'] ?? '') ?></div>
    <div class="card-content">
      <p class="card-text"><?= htmlspecialchars($content['feature_card_three_text'] ?? '') ?></p>
    </div>
  </div>
</div>

<section id="restaurants" class="restaurants-section">
  <h2><?= htmlspecialchars($content['explore_heading'] ?? 'Explore Restaurants') ?></h2>
  <p class="muted"><?= htmlspecialchars($content['explore_text'] ?? 'Taste the finest culinary experiences in Haarlem.') ?></p>

  <form method="GET" class="filter-row">
    <label for="cuisine"><strong>Cuisine:</strong></label>

    <select name="cuisine" id="cuisine" onchange="this.form.submit()">
      <option value="">All</option>

      <?php foreach ($cuisines as $cuisine): ?>
        <option value="<?= htmlspecialchars($cuisine) ?>" <?= $selectedCuisine === $cuisine ? 'selected' : '' ?>>
          <?= htmlspecialchars($cuisine) ?>
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
        <div class="restaurant-card-new">
          <?php if ($restaurant->adultPriceCents !== null): ?>
            <div class="price-badge">€<?= number_format($restaurant->adultPriceCents / 100, 2) ?></div>
          <?php endif; ?>

          <h3 class="restaurant-title">
            <?= htmlspecialchars($restaurant->restaurantName) ?>
          </h3>

          <?php if ($restaurant->rating !== null): ?>
            <div class="rating">Rating: <?= htmlspecialchars((string) $restaurant->rating) ?></div>
          <?php endif; ?>

          <img
            class="restaurant-image"
            src="<?= htmlspecialchars(!empty($restaurant->restaurantImagePath) ? $restaurant->restaurantImagePath : '/assets/yummy/image/yummy-hero.jpg', ENT_QUOTES) ?>"
            alt="<?= htmlspecialchars($restaurant->restaurantName, ENT_QUOTES) ?>">

          <div class="cuisine-tags">
            <?php foreach ($restaurant->cuisineTags as $tag): ?>
              <span class="tag"><?= htmlspecialchars($tag) ?></span>
            <?php endforeach; ?>
          </div>

          <?php if (!empty($restaurant->description)): ?>
            <p class="restaurant-description">
              <?= nl2br(htmlspecialchars($restaurant->description)) ?>
            </p>
          <?php endif; ?>

          <?php if (!empty($restaurant->address)): ?>
            <p class="restaurant-address">
              <?= htmlspecialchars($restaurant->address) ?>
            </p>
          <?php endif; ?>

          <a href="/events/yummy/restaurant/<?= htmlspecialchars($restaurant->slug) ?>" class="view-btn">
            View restaurant
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
<?php require __DIR__ . '/../../partials/footer.php'; ?>
