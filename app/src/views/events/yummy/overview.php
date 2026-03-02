<?php require __DIR__ . '/../../partials/header.php'; ?>

<?php
/** @var array<string,string> $yummyContent */
$introHeading = $yummyContent['intro_heading'] ?? 'Yummy Events';
$introText = $yummyContent['intro_text'] ?? 'Taste the finest culinary experiences in Haarlem.';
?>

<h1><?= $introHeading ?></h1>
<p><?= $introText ?></p>

<?php require __DIR__ . '/../../partials/footer.php'; ?>
<?php
// yummy.php