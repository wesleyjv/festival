<?php
/**
 * Reusable ScheduleItem: one day card with date heading and 4 colored event blocks.
 * Expects: $date (string), $blocks (array of { name, time, category } where category = jazz|storytelling|food|history)
 */
$date = $date ?? '';
$blocks = $blocks ?? [];
?>
<div class="schedule-item">
  <h3 class="schedule-item__date"><?php echo htmlspecialchars($date); ?></h3>
  <div class="schedule-item__blocks">
    <?php foreach ($blocks as $block): ?>
      <div class="schedule-block schedule-block--<?php echo htmlspecialchars($block['category'] ?? 'jazz'); ?>">
        <p class="schedule-block__name"><?php echo htmlspecialchars($block['name'] ?? ''); ?></p>
        <p class="schedule-block__time"><?php echo htmlspecialchars($block['time'] ?? ''); ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</div>
