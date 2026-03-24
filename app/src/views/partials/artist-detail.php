<?php
/**
 * Reusable artist detail component for Jazz events.
 *
 * Expects $artist (JazzEvent) with all fields populated.
 * Include this partial from any Jazz detail page view.
 *
 * Track JSON format expected in $artist->tracks:
 *   [{"title":"Pinkie Binkie","genre":"Jazz Fusion","url":"/audio/...","duration":"5:15"}, ...]
 */
if (!isset($artist)) {
    return;
}

/** @var array<string,string>|null $detailContent CMS overrides from jazz_page_contents (page jazz_{eventId}) */
$dc = $detailContent ?? [];
$bannerImg = !empty($dc['banner_image']) ? $dc['banner_image'] : ($artist->bannerImage ?? null);
$profileImg = !empty($dc['profile_image']) ? $dc['profile_image'] : ($artist->profileImage ?? null);
$displayName = isset($dc['artist_name']) && trim((string) $dc['artist_name']) !== ''
    ? $dc['artist_name']
    : $artist->artist;

$styles   = array_filter(array_map('trim', explode(',', $artist->style ?? '')));
$day      = $artist->startTime ? date('l', strtotime($artist->startTime)) : null;
$timeFrom = $artist->startTime ? date('H:i', strtotime($artist->startTime)) : null;
$timeTo   = $artist->endTime   ? date('H:i', strtotime($artist->endTime))   : null;
$tracks   = $artist->tracks ?? [];
?>

<!-- Hero -->
<div class="detail-hero">
    <?php if ($bannerImg): ?>
        <img src="<?= htmlspecialchars($bannerImg) ?>"
             alt="" class="detail-hero__img">
    <?php else: ?>
        <div class="detail-hero__placeholder"><i class="bi bi-music-note-beamed"></i></div>
    <?php endif; ?>
    <div class="detail-hero__overlay"></div>
    <?php if ($styles): ?>
        <div class="detail-hero__tags">
            <?php foreach ($styles as $tag): ?>
                <span><?= htmlspecialchars($tag) ?></span>
            <?php endforeach; ?>
            <a href="#performances" class="detail-hero__perf-btn">View Performances</a>
        </div>
    <?php endif; ?>
</div>

<!-- ?? Go back ?????????????????????????????????????????????????? -->
<div class="detail-goback">
    <a href="/events/jazz"><i class="bi bi-arrow-left"></i> Go back</a>
</div>

<!-- ?? Page body ???????????????????????????????????????????????? -->
<div class="detail-page">
    <div class="container">

        <!-- Bio -->
        <section class="detail-bio">
            <div class="row align-items-start g-5">
                <div class="col-md-6">
                    <h1 class="detail-bio__name"><?= htmlspecialchars($displayName) ?></h1>
                    <?php if (!empty(trim($dc['bio_html'] ?? ''))): ?>
                        <div class="detail-bio__text"><?= $dc['bio_html'] ?></div>
                    <?php else: ?>
                        <p class="detail-bio__text"><?= nl2br(htmlspecialchars($artist->description)) ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <?php if ($profileImg): ?>
                        <img src="<?= htmlspecialchars($profileImg) ?>"
                             alt="<?= htmlspecialchars($displayName) ?>"
                             class="detail-bio__img">
                    <?php else: ?>
                        <div class="detail-bio__img-placeholder"><i class="bi bi-person-bounding-box"></i></div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Tracks -->
        <?php if (!empty($tracks)): ?>
            <section class="detail-tracks">
                <h2 class="detail-tracks__heading"><?= htmlspecialchars($dc['tracks_heading'] ?? 'Listen to their sounds') ?></h2>
                <?php foreach ($tracks as $track): ?>
                    <div class="track-item">
                        <div class="track-item__header">
                            <span class="track-item__title"><?= htmlspecialchars($track['title'] ?? '') ?></span>
                            <?php if (!empty($track['genre'])): ?>
                                <span class="track-item__genre">Genre: <?= htmlspecialchars($track['genre']) ?></span>
                            <?php endif; ?>
                        </div>
                        <audio controls preload="none">
                            <?php if (!empty($track['url'])): ?>
                                <source src="<?= htmlspecialchars($track['url']) ?>">
                            <?php endif; ?>
                        </audio>
                    </div>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>

        <!-- Upcoming performances -->
        <?php if ($day && $timeFrom): ?>
            <?php
            $jazzCartTicket = $jazzCartTicket ?? null;
            $perfMaxQty = 99;
            if ($artist->seats !== null && $artist->seats > 0) {
                $perfMaxQty = min(99, $artist->seats);
            }
            ?>
            <section class="detail-performances" id="performances">
                <h2 class="detail-performances__heading"><?= htmlspecialchars($dc['performances_heading'] ?? 'Upcoming performances') ?></h2>
                <div class="perf-card">
                    <div class="perf-card__time">
                        <?= htmlspecialchars($day) ?> &middot; <?= $timeFrom ?>&ndash;<?= $timeTo ?>
                    </div>
                    <?php if ($artist->location): ?>
                        <div class="perf-card__location"><?= htmlspecialchars($artist->location) ?></div>
                    <?php endif; ?>
                    <?php if ($artist->price !== null): ?>
                        <div class="perf-card__price">&euro;<?= number_format($artist->price, 0) ?></div>
                        <div class="perf-card__price-note"><?= htmlspecialchars($dc['price_note'] ?? 'Included in passes') ?></div>
                    <?php endif; ?>
                    <?php if ($jazzCartTicket !== null): ?>
                        <form action="/cart/add" method="post" class="perf-card__cart-form js-cart-add-form">
                            <?= \App\Security\Csrf::field() ?>
                            <input type="hidden" name="ticket_id" value="<?= (int) $jazzCartTicket->id ?>">
                            <div class="perf-card__qty-row">
                                <label for="perf-cart-qty" class="perf-card__qty-label">Tickets</label>
                                <input type="number" name="quantity" id="perf-cart-qty" class="form-control form-control-sm perf-card__qty-input" value="1" min="1" max="<?= (int) $perfMaxQty ?>">
                            </div>
                            <button type="submit" class="perf-card__btn">
                                <i class="bi bi-bag-plus me-1" aria-hidden="true"></i>Add to cart
                            </button>
                        </form>
                    <?php else: ?>
                        <p class="perf-card__unavailable text-muted small mb-0">Online tickets are not available for this performance.</p>
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>

        <!-- Back to artists -->
        <div class="detail-back-artists">
            <a href="/events/jazz"><i class="bi bi-arrow-left"></i> Back to artists</a>
        </div>

    </div>
</div>
