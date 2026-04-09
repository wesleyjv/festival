<?php
$currentDay = $_GET['day'] ?? '';
$currentTime = $_GET['time'] ?? '';
$days = [];
if (!empty($allEvents ?? null)) {
    foreach ($allEvents as $allEvent) {
        $day = $allEvent['day_of_week'] ?? ($allEvent['day'] ?? '');
        if ($day && !isset($days[$day])) {
            $days[$day] = $day;
        }
    }
}
if (empty($days)) {
    $days = ['Thursday' => 'Thursday', 'Friday' => 'Friday', 'Saturday' => 'Saturday', 'Sunday' => 'Sunday'];
}
?>
<section class="storytelling-schedule" id="schedule" aria-labelledby="schedule-title">
    <div class="schedule-header">
        <h2 id="schedule-title" class="schedule-title">Event Schedule</h2>
        <p class="schedule-subtitle">
            Filter through our storytelling events to find the perfect tales for your festival experience
        </p>
    </div>

    <div class="storytelling-filter">
        <div class="filter-grid">
            <div class="filter-group">
                <div class="filter-label">Date</div>
                <div class="filter-buttons">
                    <button class="filter-btn <?php echo !$currentDay ? 'active' : ''; ?>" onclick="window.location.href='/events/stories'">All</button>
                    <?php foreach ($days as $dayKey => $dayLabel): ?>
                        <button class="filter-btn <?php echo ($currentDay === $dayKey) ? 'active' : ''; ?>" onclick="window.location.href='/events/stories?day=<?php echo urlencode($dayKey); ?>'">
                            <?php echo htmlspecialchars($dayLabel); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="filter-group">
                <div class="filter-label">Time</div>
                <div class="filter-buttons">
                    <button class="filter-btn <?php echo !$currentTime ? 'active' : ''; ?>" onclick="window.location.href='/events/stories'">All</button>
                    <button class="filter-btn <?php echo ($currentTime === 'morning') ? 'active' : ''; ?>" onclick="window.location.href='/events/stories?time=morning'">Morning</button>
                    <button class="filter-btn <?php echo ($currentTime === 'afternoon') ? 'active' : ''; ?>" onclick="window.location.href='/events/stories?time=afternoon'">Afternoon</button>
                    <button class="filter-btn <?php echo ($currentTime === 'evening') ? 'active' : ''; ?>" onclick="window.location.href='/events/stories?time=evening'">Evening</button>
                </div>
            </div>
        </div>
    </div>

    <div class="storytelling-events">
        <?php if (empty($events)): ?>
            <p>No storytelling events found for the selected filters.</p>
        <?php else: ?>
            <?php foreach ($events as $event): ?>
                <?php require __DIR__ . '/event-card.php'; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
