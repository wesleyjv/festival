<?php require __DIR__ . '/../../partials/header.php'; ?>

<h1>Yummy Events</h1>
<p>Taste the finest culinary experiences in Haarlem.</p>

<form method="GET">
    <select name="cuisine">
        <option value="">All</option>
        <option value="Italian">Italian</option>
        <option value="Asian">Asian</option>
        <option value="Mexican">Mexican</option>
    </select>
    <button type="submit">Filter</button>
</form>

<hr>

<?php foreach ($restaurants as $restaurant): ?>

    <div style="margin-bottom:20px;">
        <h2><?= htmlspecialchars($restaurant['restaurant_name']) ?></h2>
        <p><strong>Cuisine:</strong> <?= htmlspecialchars($restaurant['cuisine']) ?></p>
        <p><?= htmlspecialchars($restaurant['description']) ?></p>

        <?php if (!empty($restaurant['image'])): ?>
            <img src="/images/<?= htmlspecialchars($restaurant['image']) ?>" width="200" alt="<?= htmlspecialchars($restaurant['restaurant_name']) ?>">
        <?php endif; ?>
    </div>

<?php endforeach; ?>

<?php require __DIR__ . '/../../partials/footer.php'; ?>