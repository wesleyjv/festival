<?php require __DIR__ . '/../../partials/header.php'; ?>

<h1><?= htmlspecialchars($restaurant->restaurantName) ?></h1>

<?php if ($restaurant->imagePath): ?>
    <img src="/<?= htmlspecialchars($restaurant->imagePath) ?>" alt="<?= htmlspecialchars($restaurant->restaurantName) ?>" style="max-width: 400px;">
<?php endif; ?>

<?php if ($restaurant->description): ?>
    <p><?= nl2br(htmlspecialchars($restaurant->description)) ?></p>
<?php endif; ?>

<?php if ($restaurant->address): ?>
    <p><?= htmlspecialchars($restaurant->address) ?></p>
<?php endif; ?>

<?php if (!empty($restaurant->cuisineTags)): ?>
    <ul>
        <?php foreach ($restaurant->cuisineTags as $tag): ?>
            <li><?= htmlspecialchars($tag) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php require __DIR__ . '/../../partials/footer.php'; ?>
