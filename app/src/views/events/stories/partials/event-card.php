<?php
$eventId = (int) ($event['id'] ?? 0);
$ticketId = $storyTicketIds[$eventId] ?? null;
$eventDate = trim((string) ($event['date'] ?? ''));
$eventTime = trim((string) ($event['time'] ?? ''));
$eventLocation = trim((string) ($event['location_name'] ?? ''));
$eventAgeGroup = trim((string) ($event['age_group'] ?? ''));
$detailUrl = '/events/stories/' . $eventId;
?>
<article class="artist-card story-artist-card">
    <div class="artist-card__body">
        <div class="story-artist-card__head">
            <h3 class="artist-card__name">
                <a class="story-artist-card__title-link" href="<?= htmlspecialchars($detailUrl, ENT_QUOTES, 'UTF-8') ?>"><?php echo htmlspecialchars($event['title']); ?></a>
            </h3>
            <span class="story-artist-card__price">EUR <?php echo htmlspecialchars($event['price']); ?></span>
        </div>
        <?php if ($eventDate !== ''): ?>
            <p class="artist-card__day">On <strong><?php echo htmlspecialchars($eventDate); ?></strong></p>
        <?php endif; ?>
        <?php if ($eventTime !== ''): ?>
            <p class="artist-card__time">
                <i class="bi bi-clock"></i>
                <strong><?php echo htmlspecialchars($eventTime); ?></strong>
            </p>
        <?php endif; ?>
        <?php if ($eventLocation !== ''): ?>
            <p class="artist-card__day">Location: <strong><?php echo htmlspecialchars($eventLocation); ?></strong></p>
        <?php endif; ?>
        <?php if ($eventAgeGroup !== ''): ?>
            <p class="artist-card__day">Age group: <strong><?php echo htmlspecialchars($eventAgeGroup); ?></strong></p>
        <?php endif; ?>
        <p class="artist-card__blurb"><?php echo htmlspecialchars($event['description']); ?></p>
        <div class="story-artist-card__actions">
            <?php if ($ticketId !== null): ?>
                <form action="/cart/add" method="post" class="js-cart-add-form storytelling-event-cart-form">
                    <?= \App\Security\Csrf::field() ?>
                    <input type="hidden" name="ticket_id" value="<?= (int) $ticketId ?>">
                    <label class="storytelling-event-qty-label" for="story-qty-<?= $eventId ?>">Qty</label>
                    <input id="story-qty-<?= $eventId ?>" type="number" name="quantity" class="form-control form-control-sm storytelling-event-qty" value="1" min="1" max="99" aria-label="Ticket quantity for <?= htmlspecialchars($event['title'], ENT_QUOTES, 'UTF-8') ?>">
                    <button type="submit" class="artist-card__btn storytelling-event-add-btn">Add to cart</button>
                </form>
            <?php else: ?>
                <span class="text-muted small">Tickets unavailable</span>
            <?php endif; ?>
            <a class="story-artist-card__details-link" href="<?= htmlspecialchars($detailUrl, ENT_QUOTES, 'UTF-8') ?>">View details &rarr;</a>
        </div>
    </div>
</article>
