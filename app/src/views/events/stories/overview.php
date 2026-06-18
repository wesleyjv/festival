<?php
/** @var \App\ViewModels\StoriesOverviewViewModel|null $viewModel */
$viewModel = $viewModel ?? null;
$storiesContent = $viewModel?->content ?? ($storiesContent ?? []);
$events = $viewModel?->events ?? ($events ?? []);
$allEvents = $viewModel?->allEvents ?? ($allEvents ?? []);
$featuredStoryteller = $viewModel?->featuredStoryteller ?? ($featuredStoryteller ?? []);
$locations = $viewModel?->locations ?? ($locations ?? []);

$bodyClass = 'storytelling-layout page-jazz page-stories';
$mainClass = 'storytelling-main-wrapper';
$storyTicketIds = $storyTicketIds ?? [];
$extraStylesheets = [
    'https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap',
    '/css/jazz/jazz-public.css?v=20260324',
    '/css/stories/stories-jazz.css?v=20260615a',
];

$heroImage = $storiesContent['hero_image'] ?? '/img/hero-homepage-haarlem.png';
$heroTitleCustom = isset($storiesContent['hero_title']) ? trim((string) $storiesContent['hero_title']) : '';
$heroDescriptionCustom = isset($storiesContent['hero_description']) ? trim((string) $storiesContent['hero_description']) : '';

$featuredTitle = $featuredStoryteller['title'] ?? 'Experience the Art of Storytelling in Haarlem';
$featuredDescription = $featuredStoryteller['description'] ?? 'Join us for an enchanting journey through the world of storytelling, where words come alive and imagination knows no bounds. Our featured storytellers bring decades of experience and unique perspectives to create unforgettable experiences.';
$featuredImage = $storiesContent['featured_image'] ?? ($featuredStoryteller['image'] ?? '/img/about-haarlem-windmill-sunset.png');
$featuredName = $featuredStoryteller['guide_name'] ?? 'Elena van der Meer';
require __DIR__ . '/../../partials/header.php';
?>

<div class="storytelling-page">
    <?php require __DIR__ . '/partials/hero.php'; ?>

    <!-- Main Content -->
    <main class="storytelling-main">
        <?php require __DIR__ . '/partials/featured.php'; ?>
        <?php require __DIR__ . '/partials/schedule.php'; ?>
        <?php require __DIR__ . '/partials/info.php'; ?>
    </main>
</div>

<?php require __DIR__ . '/../../partials/footer.php'; ?>