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

$styles   = array_filter(array_map('trim', explode(',', $artist->style ?? '')));
$day      = $artist->startTime ? date('l', strtotime($artist->startTime)) : null;
$timeFrom = $artist->startTime ? date('H:i', strtotime($artist->startTime)) : null;
$timeTo   = $artist->endTime   ? date('H:i', strtotime($artist->endTime))   : null;
$tracks   = $artist->tracks ?? [];
?>

<style>
/* ?? Detail hero ??????????????????????????????????????????????? */
.detail-hero {
    position: relative;
    height: 380px;
    background: #111;
    overflow: hidden;
}
.detail-hero__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.detail-hero__placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #111 0%, #2d2346 60%, #1a3a2a 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255,255,255,0.07);
    font-size: 6rem;
}
.detail-hero__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.55) 0%, transparent 60%);
}
.detail-hero__tags {
    position: absolute;
    bottom: 3.5rem;
    right: 1.5rem;
    text-align: right;
    z-index: 2;
}
.detail-hero__tags span {
    display: block;
    color: #fff;
    font-size: 1.15rem;
    font-weight: 700;
    line-height: 1.35;
    text-shadow: 0 1px 6px rgba(0,0,0,0.7);
}
.detail-hero__perf-btn {
    display: inline-block;
    margin-top: 0.7rem;
    padding: 0.45rem 1.1rem;
    background: rgba(0,0,0,0.65);
    color: #fff;
    text-decoration: none;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    border-radius: 6px;
    border: 1px solid rgba(255,255,255,0.25);
    backdrop-filter: blur(4px);
    transition: background 0.2s;
}
.detail-hero__perf-btn:hover {
    background: rgba(0,0,0,0.85);
    color: #fff;
}

/* ?? Go back ??????????????????????????????????????????????????? */
.detail-goback {
    background: #fff;
    padding: 0.75rem 1.5rem;
    border-bottom: 1px solid #eee;
}
.detail-goback a {
    font-size: 0.88rem;
    color: #333;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}
.detail-goback a:hover { color: #000; }

/* ?? Main content area ????????????????????????????????????????? */
.detail-page {
    background: linear-gradient(180deg, #fdf6ee 0%, #fce8c8 100%);
    min-height: 60vh;
    padding-bottom: 4rem;
}

/* ?? Bio section ??????????????????????????????????????????????? */
.detail-bio {
    padding: 3rem 0 2rem;
}
.detail-bio__name {
    font-size: clamp(1.6rem, 3.5vw, 2.1rem);
    font-weight: 800;
    margin-bottom: 1.25rem;
}
.detail-bio__text {
    font-size: 0.93rem;
    color: #444;
    line-height: 1.75;
}
.detail-bio__img {
    width: 100%;
    max-height: 320px;
    object-fit: cover;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    display: block;
}
.detail-bio__img-placeholder {
    width: 100%;
    height: 280px;
    background: #ddd;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(0,0,0,0.2);
    font-size: 4rem;
}

/* ?? Tracks ???????????????????????????????????????????????????? */
.detail-tracks {
    padding: 2rem 0;
}
.detail-tracks__heading {
    font-size: 1.55rem;
    font-weight: 700;
    margin-bottom: 1.25rem;
}
.track-item {
    background: #fff;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    margin-bottom: 0.75rem;
    box-shadow: 0 1px 6px rgba(0,0,0,0.07);
}
.track-item__header {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: 0.35rem;
}
.track-item__title {
    font-size: 0.9rem;
    font-weight: 600;
    color: #222;
}
.track-item__genre {
    font-size: 0.78rem;
    color: #888;
}
.track-item audio {
    width: 100%;
    height: 28px;
    outline: none;
}

/* ?? Performances ?????????????????????????????????????????????? */
.detail-performances {
    padding: 2rem 0 1rem;
}
.detail-performances__heading {
    font-size: 1.55rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
}
.perf-card {
    background: #fff;
    border-radius: 14px;
    padding: 1.75rem 1.5rem;
    box-shadow: 0 2px 14px rgba(0,0,0,0.09);
    max-width: 360px;
    margin: 0 auto;
    text-align: center;
}
.perf-card__time {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 0.6rem;
    color: #111;
}
.perf-card__location {
    font-size: 1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1rem;
}
.perf-card__price {
    font-size: 1.15rem;
    font-weight: 800;
    color: #111;
    margin-bottom: 0.2rem;
}
.perf-card__price-note {
    font-size: 0.78rem;
    color: #888;
    margin-bottom: 1.25rem;
}
.perf-card__btn {
    display: inline-block;
    background: #1a1a2e;
    color: #fff;
    text-decoration: none;
    padding: 0.7rem 1.5rem;
    border-radius: 999px;
    font-size: 0.76rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    transition: background 0.2s;
    border: none;
    cursor: pointer;
}
.perf-card__btn:hover {
    background: #2e2e5e;
    color: #fff;
}

/* ?? Back to artists ??????????????????????????????????????????? */
.detail-back-artists {
    padding: 1.5rem 0 2rem;
}
.detail-back-artists a {
    font-size: 0.9rem;
    color: #333;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}
.detail-back-artists a:hover { color: #000; }
</style>

<!-- ?? Hero ????????????????????????????????????????????????????? -->
<div class="detail-hero">
    <?php if ($artist->bannerImage): ?>
        <img src="<?= htmlspecialchars($artist->bannerImage) ?>"
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
                    <h1 class="detail-bio__name"><?= htmlspecialchars($artist->artist) ?></h1>
                    <p class="detail-bio__text"><?= nl2br(htmlspecialchars($artist->description)) ?></p>
                </div>
                <div class="col-md-6">
                    <?php if ($artist->profileImage): ?>
                        <img src="<?= htmlspecialchars($artist->profileImage) ?>"
                             alt="<?= htmlspecialchars($artist->artist) ?>"
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
                <h2 class="detail-tracks__heading">Listen to their sounds</h2>
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
            <section class="detail-performances" id="performances">
                <h2 class="detail-performances__heading">Upcoming performances</h2>
                <div class="perf-card">
                    <div class="perf-card__time">
                        <?= htmlspecialchars($day) ?> &middot; <?= $timeFrom ?>&ndash;<?= $timeTo ?>
                    </div>
                    <?php if ($artist->location): ?>
                        <div class="perf-card__location"><?= htmlspecialchars($artist->location) ?></div>
                    <?php endif; ?>
                    <?php if ($artist->price !== null): ?>
                        <div class="perf-card__price">&euro;<?= number_format($artist->price, 0) ?></div>
                        <div class="perf-card__price-note">Included in passes</div>
                    <?php endif; ?>
                    <a href="/cart/add?session_id=<?= $artist->eventId ?>" class="perf-card__btn">
                        Add to Program &amp; Cart
                    </a>
                </div>
            </section>
        <?php endif; ?>

        <!-- Back to artists -->
        <div class="detail-back-artists">
            <a href="/events/jazz"><i class="bi bi-arrow-left"></i> Back to artists</a>
        </div>

    </div>
</div>
