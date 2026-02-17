<?php

/**
 * Reusable event card partial. Expects $event (FeaturedEventDto).
 * Used in homepage featured events section; do not hardcode event data here.
 */
if (!isset($event)) {
    return;
}
?>
<div class="card h-100 shadow-sm">
    <img src="<?php echo htmlspecialchars($event->imageUrl); ?>" class="card-img-top object-fit-cover" alt="" style="height: 200px;">
    <div class="card-body d-flex flex-column">
        <h3 class="card-title h5"><?php echo htmlspecialchars($event->title); ?></h3>
        <p class="card-text small text-muted mb-2">
            <i class="bi bi-calendar3 me-1"></i> <?php echo htmlspecialchars($event->date); ?>
            <br>
            <i class="bi bi-geo-alt me-1"></i> <?php echo htmlspecialchars($event->location); ?>
        </p>
        <?php if ($event->description !== ''): ?>
            <p class="card-text flex-grow-1 small"><?php echo htmlspecialchars($event->description); ?></p>
        <?php endif; ?>
        <a href="<?php echo htmlspecialchars($event->detailsUrl); ?>" class="btn btn-primary btn-sm mt-2 align-self-start">Read more</a>
    </div>
</div>
