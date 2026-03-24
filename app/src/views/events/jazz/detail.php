<?php

/** @var \App\Models\JazzEvent $artist */

use App\Services\ContentService;

$detailContent = (new ContentService())->getPageContent('jazz_' . $artist->eventId);

$extraStylesheets = ['/css/jazz/jazz-public.css'];
require __DIR__ . '/../../partials/header.php';
?>

<main>
    <?php require __DIR__ . '/../../partials/artist-detail.php'; ?>
</main>

<?php require __DIR__ . '/../../partials/footer.php'; ?>
