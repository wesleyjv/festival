<?php
$currentDay      = trim((string) ($_GET['day'] ?? ''));
$currentTime     = trim((string) ($_GET['time'] ?? ''));
$currentLocation = trim((string) ($_GET['location'] ?? ''));

/**
 * Build a /events/stories URL from the currently active filters, with one or
 * more keys overridden. Passing null/'' for a value removes that filter, which
 * is how the "All" buttons clear just their own group while keeping the rest.
 */
$buildFilterUrl = static function (array $overrides) use ($currentDay, $currentTime, $currentLocation): string {
    $params = array_filter([
        'day'      => $currentDay,
        'time'     => $currentTime,
        'location' => $currentLocation,
    ], static fn ($value) => $value !== '');

    foreach ($overrides as $key => $value) {
        if ($value === null || $value === '') {
            unset($params[$key]);
        } else {
            $params[$key] = $value;
        }
    }

    return '/events/stories' . ($params ? '?' . http_build_query($params) : '') . '#schedule';
};

// Day options derived from the full (unfiltered) event list.
$days = [];
foreach (($allEvents ?? []) as $allEvent) {
    $day = $allEvent['day_of_week'] ?? ($allEvent['day'] ?? '');
    if ($day !== '' && !isset($days[$day])) {
        $days[$day] = $day;
    }
}
if (empty($days)) {
    $days = ['Thursday' => 'Thursday', 'Friday' => 'Friday', 'Saturday' => 'Saturday', 'Sunday' => 'Sunday'];
}

$locationOptions = $locations ?? [];

/** Renders a single filter button that navigates to $url. */
$filterButton = static function (string $url, string $label, bool $active): void {
    printf(
        '<button class="filter-btn %s" onclick="window.location.href=\'%s\'">%s</button>',
        $active ? 'active' : '',
        htmlspecialchars($url, ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($label, ENT_QUOTES, 'UTF-8')
    );
};
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
                    <?php $filterButton($buildFilterUrl(['day' => null]), 'All', $currentDay === ''); ?>
                    <?php foreach ($days as $dayKey => $dayLabel): ?>
                        <?php $filterButton($buildFilterUrl(['day' => $dayKey]), $dayLabel, $currentDay === $dayKey); ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="filter-group">
                <div class="filter-label">Time</div>
                <div class="filter-buttons">
                    <?php $filterButton($buildFilterUrl(['time' => null]), 'All', $currentTime === ''); ?>
                    <?php $filterButton($buildFilterUrl(['time' => 'morning']), 'Morning', $currentTime === 'morning'); ?>
                    <?php $filterButton($buildFilterUrl(['time' => 'afternoon']), 'Afternoon', $currentTime === 'afternoon'); ?>
                    <?php $filterButton($buildFilterUrl(['time' => 'evening']), 'Evening', $currentTime === 'evening'); ?>
                </div>
            </div>

            <?php if (!empty($locationOptions)): ?>
                <div class="filter-group">
                    <div class="filter-label">Location</div>
                    <div class="filter-buttons">
                        <?php $filterButton($buildFilterUrl(['location' => null]), 'All', $currentLocation === ''); ?>
                        <?php foreach ($locationOptions as $location): ?>
                            <?php $name = (string) ($location['name'] ?? ''); ?>
                            <?php if ($name !== ''): ?>
                                <?php $filterButton($buildFilterUrl(['location' => $name]), $name, $currentLocation === $name); ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
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
