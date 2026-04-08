<?php
/** @var \App\ViewModels\YummyDetailViewModel $viewModel */
$restaurant = $viewModel->restaurant;

require __DIR__ . '/../../partials/header.php';
?>

<link rel="stylesheet" href="/assets/yummy/css/globals.css" />
<link rel="stylesheet" href="/assets/yummy/css/styleguide.css" />
<link rel="stylesheet" href="/assets/yummy/css/style.css" />

<style>
  .detail-hero {
    position: relative;
    width: 100%;
    min-height: 420px;
    display: flex;
    align-items: flex-end;
    overflow: hidden;
    background-color: #2c3e50;
  }

  .detail-hero__img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
  }

  .detail-hero__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.72) 0%, rgba(0,0,0,0.18) 60%, transparent 100%);
  }

  .detail-hero__content {
    position: relative;
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    width: 100%;
    padding: 40px 48px;
    gap: 16px;
  }

  .detail-hero__title {
    margin: 0;
    font-size: 52px;
    font-weight: 800;
    line-height: 1.05;
    color: #ffffff;
    text-shadow: 0 2px 8px rgba(0,0,0,0.45);
  }

  .detail-hero__price {
    flex-shrink: 0;
    background: #b46b29;
    color: #ffffff;
    font-size: 22px;
    font-weight: 700;
    padding: 10px 20px;
    border-radius: 10px;
    white-space: nowrap;
    align-self: flex-start;
  }

  .detail-card {
    max-width: 860px;
    margin: 40px auto 80px auto;
    padding: 40px 48px;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.07);
  }

  .detail-back {
    display: inline-block;
    margin-bottom: 28px;
    padding: 10px 20px;
    background: #1a2a3a;
    color: #ffffff;
    text-decoration: none;
    border-radius: 999px;
    font-weight: 600;
    font-size: 15px;
  }

  .detail-back:hover {
    background: #2d3f52;
  }

  .detail-rating {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
  }

  .detail-rating__stars {
    color: #d94c2a;
    font-size: 22px;
    letter-spacing: 2px;
  }

  .detail-rating__value {
    color: #4b5563;
    font-size: 15px;
  }

  .detail-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 24px;
  }

  .tag {
    display: inline-block;
    background: #e0c4a4;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
  }

  .detail-address {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    margin-bottom: 24px;
    color: #4b5563;
    font-size: 15px;
    line-height: 1.5;
  }

  .detail-address__pin {
    flex-shrink: 0;
    font-size: 18px;
    margin-top: 1px;
  }

  /* Description */
  .detail-description {
    color: #1a2a3a;
    font-size: 16px;
    line-height: 1.75;
    margin: 0;
  }

  /* Divider */
  .detail-divider {
    border: none;
    border-top: 1px solid #e5e7eb;
    margin: 24px 0;
  }

  /* Mobile */
  @media (max-width: 768px) {
    .detail-hero {
      min-height: 300px;
    }

    .detail-hero__content {
      flex-direction: column;
      align-items: flex-start;
      padding: 24px 20px;
    }

    .detail-hero__title {
      font-size: 32px;
    }

    .detail-hero__price {
      font-size: 18px;
      padding: 8px 14px;
    }

    .detail-card {
      margin: 20px 12px 48px 12px;
      padding: 24px 20px;
    }
  }
</style>

<!-- HERO -->
<section class="detail-hero">
  <?php if (!empty($restaurant->imagePath)): ?>
    <img
      class="detail-hero__img"
      src="/<?= htmlspecialchars($restaurant->imagePath) ?>"
      alt="<?= htmlspecialchars($restaurant->restaurantName) ?>"
    >
  <?php endif; ?>

  <div class="detail-hero__overlay"></div>

  <div class="detail-hero__content">
    <h1 class="detail-hero__title"><?= htmlspecialchars($restaurant->restaurantName) ?></h1>

    <?php if ($restaurant->price !== null): ?>
      <div class="detail-hero__price">€<?= htmlspecialchars(number_format($restaurant->price, 2)) ?></div>
    <?php endif; ?>
  </div>
</section>

<!-- CONTENT CARD -->
<div class="detail-card">
  <a class="detail-back" href="/events/yummy">&larr; Back to restaurants</a>

  <?php if ($restaurant->rating !== null): ?>
    <?php
      $rating          = $restaurant->rating;
      $filledStarCount = min((int) floor($rating), 5);
      $hasHalfStar     = ($rating - $filledStarCount) >= 0.5 ? 1 : 0;
      $emptyStarCount  = max(0, 5 - $filledStarCount - $hasHalfStar);
      $starCharacters  = str_repeat('&#9733;', $filledStarCount)
                       . str_repeat('&#9734;', $hasHalfStar)
                       . str_repeat('&#9734;', $emptyStarCount);
    ?>
    <div class="detail-rating">
      <span class="detail-rating__stars"><?= $starCharacters ?></span>
      <span class="detail-rating__value"><?= htmlspecialchars(number_format($rating, 1)) ?> / 5</span>
    </div>
    <hr class="detail-divider">
  <?php endif; ?>

  <?php if (!empty($restaurant->cuisineTags)): ?>
    <div class="detail-tags">
      <?php foreach ($restaurant->cuisineTags as $tag): ?>
        <span class="tag"><?= htmlspecialchars($tag) ?></span>
      <?php endforeach; ?>
    </div>
    <hr class="detail-divider">
  <?php endif; ?>

  <?php if (!empty($restaurant->address)): ?>
    <div class="detail-address">
      <span class="detail-address__pin">&#128205;</span>
      <span><?= htmlspecialchars($restaurant->address) ?></span>
    </div>
    <hr class="detail-divider">
  <?php endif; ?>

  <?php if (!empty($restaurant->description)): ?>
    <p class="detail-description"><?= nl2br(htmlspecialchars($restaurant->description)) ?></p>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/../../partials/footer.php'; ?>
