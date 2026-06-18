<?php
/** @var array<string,mixed> $event */
/** @var int|null $ticketId */
/** @var array<string,string> $storiesContent */
$event          = $event ?? [];
$ticketId       = $ticketId ?? null;
$storiesContent = $storiesContent ?? [];

$bodyClass = 'storytelling-layout page-jazz page-stories';
$mainClass = 'storytelling-main-wrapper';
$extraStylesheets = [
    'https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap',
    '/css/jazz/jazz-public.css?v=20260324',
    '/css/stories/stories-jazz.css?v=20260615a',
];

$heroImage = $storiesContent['hero_image'] ?? '/img/hero-homepage-haarlem.png';

$eventId       = (int) ($event['id'] ?? 0);
$title         = (string) ($event['title'] ?? 'Story Session');
$description   = trim((string) ($event['description'] ?? ''));
$date          = trim((string) ($event['date'] ?? ''));
$time          = trim((string) ($event['time'] ?? ''));
$location      = trim((string) ($event['location_name'] ?? ''));
$ageGroup      = trim((string) ($event['age_group'] ?? ''));
$language      = trim((string) ($event['language'] ?? ''));
$price         = trim((string) ($event['price'] ?? ''));

require __DIR__ . '/../../partials/header.php';
?>

<div class="storytelling-page">
    <section class="jazz-hero storytelling-jazz-hero" style="background-image:url('<?php echo htmlspecialchars($heroImage, ENT_QUOTES, 'UTF-8'); ?>');background-size:cover;background-position:center;">
        <div class="jazz-hero__overlay"></div>
        <div class="jazz-hero__content storytelling-jazz-hero__content">
            <span class="storytelling-jazz-hero__kicker">Haarlem Festival Storytelling</span>
            <h1><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></h1>
        </div>
    </section>

    <main class="storytelling-main">
        <a class="story-detail__back" href="/events/stories">&larr; Back to all stories</a>

        <article class="story-detail">
            <div class="story-detail__head">
                <h2 class="story-detail__title"><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></h2>
                <?php if ($price !== ''): ?>
                    <span class="story-detail__price">EUR <?php echo htmlspecialchars($price, ENT_QUOTES, 'UTF-8'); ?></span>
                <?php endif; ?>
            </div>

            <dl class="story-detail__meta">
                <?php if ($date !== ''): ?>
                    <div class="story-detail__meta-item"><dt>Date</dt><dd><?php echo htmlspecialchars($date, ENT_QUOTES, 'UTF-8'); ?></dd></div>
                <?php endif; ?>
                <?php if ($time !== ''): ?>
                    <div class="story-detail__meta-item"><dt>Time</dt><dd><?php echo htmlspecialchars($time, ENT_QUOTES, 'UTF-8'); ?></dd></div>
                <?php endif; ?>
                <?php if ($location !== ''): ?>
                    <div class="story-detail__meta-item"><dt>Location</dt><dd><?php echo htmlspecialchars($location, ENT_QUOTES, 'UTF-8'); ?></dd></div>
                <?php endif; ?>
                <?php if ($ageGroup !== ''): ?>
                    <div class="story-detail__meta-item"><dt>Age group</dt><dd><?php echo htmlspecialchars($ageGroup, ENT_QUOTES, 'UTF-8'); ?></dd></div>
                <?php endif; ?>
                <?php if ($language !== ''): ?>
                    <div class="story-detail__meta-item"><dt>Language</dt><dd><?php echo htmlspecialchars($language, ENT_QUOTES, 'UTF-8'); ?></dd></div>
                <?php endif; ?>
            </dl>

            <?php if ($description !== ''): ?>
                <p class="story-detail__desc"><?php echo htmlspecialchars($description, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

            <div class="story-detail__actions">
                <?php if ($ticketId !== null): ?>
                    <form action="/cart/add" method="post" class="js-cart-add-form storytelling-event-cart-form">
                        <?= \App\Security\Csrf::field() ?>
                        <input type="hidden" name="ticket_id" value="<?= (int) $ticketId ?>">
                        <label class="storytelling-event-qty-label" for="story-detail-qty">Qty</label>
                        <input id="story-detail-qty" type="number" name="quantity" class="form-control form-control-sm storytelling-event-qty" value="1" min="1" max="99" aria-label="Ticket quantity for <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>">
                        <button type="submit" class="artist-card__btn storytelling-event-add-btn">Add to cart</button>
                    </form>
                <?php else: ?>
                    <span class="text-muted small">Tickets unavailable</span>
                <?php endif; ?>
            </div>
        </article>
    </main>
</div>

<?php require __DIR__ . '/../../partials/footer.php'; ?>
