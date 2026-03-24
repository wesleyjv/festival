<?php

/** @var \App\Models\JazzEvent $artist */

use App\Services\ContentService;

$detailContent = (new ContentService())->getPageContent('jazz_' . $artist->eventId);

$bodyClass = 'page-jazz';
$extraStylesheets = [
    'https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap',
    '/css/jazz/jazz-public.css?v=20260324',
];
require __DIR__ . '/../../partials/header.php';
?>

<main>
    <?php require __DIR__ . '/../../partials/artist-detail.php'; ?>
</main>

<?php require __DIR__ . '/../../partials/footer.php'; ?>
