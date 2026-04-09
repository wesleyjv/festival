<?php
/**
 * Reusable artist card component for Jazz events.
 *
 * Expects $artist (JazzEvent) with:
 *   - homepageImage : line-up card image from CMS (preferred)
 *   - profileImage  : fallback cover if homepage image is empty
 *   - artist        : artist name
 *   - startTime     : datetime string  (e.g. "2025-06-20 21:00:00")
 *   - endTime       : datetime string
 *   - description   : short sentence about the artist
 *   - eventId       : used to build the detail URL
 */
if (!isset($artist)) {
    return;
}

$day       = $artist->startTime ? date('l', strtotime($artist->startTime)) : '';
$timeFrom  = $artist->startTime ? date('H:i', strtotime($artist->startTime)) : '';
$timeTo    = $artist->endTime   ? date('H:i', strtotime($artist->endTime))   : '';
$coverPath = $artist->homepageImage ?? $artist->profileImage ?? null;
$imgSrc    = $coverPath ? htmlspecialchars($coverPath) : null;
$name      = htmlspecialchars($artist->artist);
$blurb     = htmlspecialchars($artist->description);
$detailUrl = '/events/jazz/' . $artist->eventId;
?>
<div class="artist-card">
    <div class="artist-card__cover">
        <?php if ($imgSrc): ?>
            <img src="<?= $imgSrc ?>" alt="<?= $name ?>" loading="lazy">
        <?php else: ?>
            <div class="artist-card__cover-placeholder">
                <i class="bi bi-music-note-beamed"></i>
            </div>
        <?php endif; ?>
    </div>
    <div class="artist-card__body">
        <h3 class="artist-card__name"><?= $name ?></h3>
        <?php if ($day): ?>
            <p class="artist-card__day">Plays on <strong><?= htmlspecialchars($day) ?></strong></p>
        <?php endif; ?>
        <?php if ($timeFrom): ?>
            <p class="artist-card__time">
                <i class="bi bi-clock"></i>
                <strong><?= $timeFrom ?> - <?= $timeTo ?></strong>
            </p>
        <?php endif; ?>
        <?php if ($blurb): ?>
            <p class="artist-card__blurb"><?= $blurb ?></p>
        <?php endif; ?>
        <a href="<?= $detailUrl ?>" class="artist-card__btn">Tickets &amp; Details</a>
    </div>
</div>
