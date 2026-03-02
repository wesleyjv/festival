<?php require __DIR__ . '/../../partials/header.php'; ?>

<?php
/** @var array<string,string> $jazzContent */
$introHeading = $jazzContent['intro_heading'] ?? 'Jazz Events';
$introText = $jazzContent['intro_text'] ?? 'Discover the best jazz performances at the festival.';
?>

<h1><?= $introHeading ?></h1>
<p><?= $introText ?></p>

<?php require __DIR__ . '/../../partials/footer.php'; ?>
<?php
// jazz.php