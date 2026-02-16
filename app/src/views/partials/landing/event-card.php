<?php
/**
 * Reusable EventCard. Alternating layout: image left or right via $reverse.
 * Expects: $event (object with title, description, imageUrl, detailsUrl), $reverse (bool, for image-right layout)
 */
if (!isset($event)) {
    return;
}
$title = $event->title ?? $event->name ?? 'Event';
$description = $event->description ?? '';
$imageUrl = $event->imageUrl ?? $event->image ?? '';
$detailsUrl = $event->detailsUrl ?? '#';
$reverse = !empty($reverse);
?>
<article class="event-card <?php echo $reverse ? 'event-card--reverse' : ''; ?>">
  <div class="event-card__media">
    <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="" loading="lazy">
  </div>
  <div class="event-card__content">
    <h3 class="event-card__title"><?php echo htmlspecialchars($title); ?></h3>
    <p class="event-card__description"><?php echo htmlspecialchars($description); ?></p>
    <?php
    $href = $detailsUrl;
    $label = 'View Details';
    $variant = 'primary';
    require __DIR__ . '/button.php';
    ?>
  </div>
</article>
