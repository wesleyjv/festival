<?php
$bodyClass = 'storytelling-layout';
$mainClass = 'storytelling-main-wrapper';
require __DIR__ . '/../../partials/header.php';

$heroImage = $storiesContent['hero_image'] ?? '/img/storytelling-hero.jpg';
$heroTitleCustom = isset($storiesContent['hero_title']) ? trim((string) $storiesContent['hero_title']) : '';
$heroDescriptionCustom = isset($storiesContent['hero_description']) ? trim((string) $storiesContent['hero_description']) : '';

$featuredTitle = $featuredStoryteller['title'] ?? 'Experience the Art of Storytelling in Haarlem';
$featuredDescription = $featuredStoryteller['description'] ?? 'Join us for an enchanting journey through the world of storytelling, where words come alive and imagination knows no bounds. Our featured storytellers bring decades of experience and unique perspectives to create unforgettable experiences.';
$featuredImage = $storiesContent['featured_image'] ?? ($featuredStoryteller['image'] ?? '/img/featured-storyteller.jpg');
$featuredName = $featuredStoryteller['guide_name'] ?? 'Elena van der Meer';
?>

<div class="storytelling-page">
    <!-- Hero Section -->
    <header class="storytelling-hero">
        <img class="storytelling-hero-image" src="<?php echo htmlspecialchars($heroImage); ?>" alt="Storytelling Event" />

        <!-- Hero Content -->
        <div class="storytelling-hero-overlay">
            <div class="storytelling-hero-content">
                <h1 class="storytelling-hero-title"><?php
                    if ($heroTitleCustom !== '') {
                        echo htmlspecialchars(strip_tags($heroTitleCustom), ENT_QUOTES, 'UTF-8');
                    } else {
                        ?>The Art of <span class="highlight">Storytelling</span><?php
                    }
                ?></h1>
                <p class="storytelling-hero-description"><?php
                    if ($heroDescriptionCustom !== '') {
                        $descPlain = preg_replace('#</p>\s*<p[^>]*>#i', "\n\n", $heroDescriptionCustom);
                        $descPlain = str_replace(['<br>', '<br/>', '<br />'], "\n", $descPlain);
                        echo nl2br(htmlspecialchars(strip_tags($descPlain), ENT_QUOTES, 'UTF-8'), false);
                    } else {
                        echo htmlspecialchars(
                            'Experience the magic of oral tradition as master storytellers weave tales that transport you through time and imagination. From ancient myths to contemporary narratives, discover the power of stories that connect us all.',
                            ENT_QUOTES,
                            'UTF-8'
                        );
                    }
                ?></p>
                <a href="#schedule" class="storytelling-hero-cta">View Event Schedule</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="storytelling-main">
        <!-- Featured Section -->
        <section class="storytelling-featured" aria-labelledby="featured-title">
            <div class="storytelling-featured-content">
                <h2 id="featured-title"><?php echo htmlspecialchars($featuredTitle); ?></h2>
                <p><?php echo htmlspecialchars($featuredDescription); ?></p>
                <p>
                    From intimate gatherings in historic venues to grand performances under the stars, each story is carefully crafted 
                    to captivate audiences of all ages and backgrounds.
                </p>
            </div>
            <div class="storytelling-featured-image">
                <img src="<?php echo htmlspecialchars($featuredImage); ?>" alt="Featured Storyteller" />
                <div class="storyteller-name"><?php echo htmlspecialchars($featuredName); ?></div>
                <div class="storyteller-line"></div>
                <div class="storyteller-description">
                    Master storyteller with over 20 years of experience bringing Dutch folklore to life
                </div>
            </div>
        </section>

        <!-- Schedule Section -->
        <section class="storytelling-schedule" id="schedule" aria-labelledby="schedule-title">
            <div class="schedule-header">
                <h2 id="schedule-title" class="schedule-title">Event Schedule</h2>
                <p class="schedule-subtitle">
                    Filter through our storytelling events to find the perfect tales for your festival experience
                </p>
            </div>

            <!-- Filter Component -->
            <div class="storytelling-filter">
                <div class="filter-grid">
                    <?php
                    $currentDay = $_GET['day'] ?? '';
                    $currentTime = $_GET['time'] ?? '';
                    $currentGenre = $_GET['genre'] ?? '';
                    $currentLocation = $_GET['location'] ?? '';
                    ?>

                    <!-- Date Filter -->
                    <div class="filter-group">
                        <div class="filter-label">Date</div>
                        <div class="filter-buttons">
                            <button class="filter-btn <?php echo !$currentDay ? 'active' : ''; ?>" onclick="window.location.href='/events/stories'">All</button>
                            <?php
                            $days = [];
                            if (!empty($allEvents ?? null)) {
                                foreach ($allEvents as $event) {
                                    $day = $event['day_of_week'] ?? ($event['day'] ?? '');
                                    if ($day && !isset($days[$day])) {
                                        $days[$day] = $day;
                                    }
                                }
                            }
                            if (empty($days)) {
                                $days = ['Thursday' => 'Thursday', 'Friday' => 'Friday', 'Saturday' => 'Saturday', 'Sunday' => 'Sunday'];
                            }
                            ?>
                            <?php foreach ($days as $dayKey => $dayLabel): ?>
                                <button class="filter-btn <?php echo ($currentDay === $dayKey) ? 'active' : ''; ?>" onclick="window.location.href='/events/stories?day=<?php echo urlencode($dayKey); ?>'">
                                    <?php echo htmlspecialchars($dayLabel); ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Time Filter -->
                    <div class="filter-group">
                        <div class="filter-label">Time</div>
                        <div class="filter-buttons">
                            <button class="filter-btn <?php echo !$currentTime ? 'active' : ''; ?>" onclick="window.location.href='/events/stories'">All</button>
                            <button class="filter-btn <?php echo ($currentTime === 'morning') ? 'active' : ''; ?>" onclick="window.location.href='/events/stories?time=morning'">Morning</button>
                            <button class="filter-btn <?php echo ($currentTime === 'afternoon') ? 'active' : ''; ?>" onclick="window.location.href='/events/stories?time=afternoon'">Afternoon</button>
                            <button class="filter-btn <?php echo ($currentTime === 'evening') ? 'active' : ''; ?>" onclick="window.location.href='/events/stories?time=evening'">Evening</button>
                        </div>
                    </div>

                    <!-- Genre Filter -->
                    <div class="filter-group">
                        <div class="filter-label">Genre</div>
                        <div class="filter-buttons">
                            <button class="filter-btn <?php echo !$currentGenre ? 'active' : ''; ?>" onclick="window.location.href='/events/stories'">All</button>
                            <button class="filter-btn <?php echo ($currentGenre === 'folklore') ? 'active' : ''; ?>" onclick="window.location.href='/events/stories?genre=folklore'">Folklore</button>
                            <button class="filter-btn <?php echo ($currentGenre === 'contemporary') ? 'active' : ''; ?>" onclick="window.location.href='/events/stories?genre=contemporary'">Contemporary</button>
                            <button class="filter-btn <?php echo ($currentGenre === 'children') ? 'active' : ''; ?>" onclick="window.location.href='/events/stories?genre=children'">Children</button>
                            <button class="filter-btn <?php echo ($currentGenre === 'historical') ? 'active' : ''; ?>" onclick="window.location.href='/events/stories?genre=historical'">Historical</button>
                        </div>
                    </div>

                    <!-- Location Filter -->
                    <div class="filter-group">
                        <div class="filter-label">Location</div>
                        <div class="filter-buttons">
                            <button class="filter-btn <?php echo !$currentLocation ? 'active' : ''; ?>" onclick="window.location.href='/events/stories'">All</button>
                            <?php foreach ($locations as $location): ?>
                                <button class="filter-btn <?php echo ($currentLocation === (string)$location['id']) ? 'active' : ''; ?>" onclick="window.location.href='/events/stories?location=<?php echo urlencode($location['id']); ?>'">
                                    <?php echo htmlspecialchars($location['name']); ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Event Cards -->
            <div class="storytelling-events">
                <?php if (empty($events)): ?>
                    <p>No storytelling events found for the selected filters.</p>
                <?php else: ?>
                    <?php foreach ($events as $event): ?>
                        <article class="storytelling-event-card">
                            <img class="storytelling-event-image" src="<?php echo htmlspecialchars($event['image']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>" />
                            <div class="storytelling-event-content">
                                <div class="storytelling-event-header">
                                    <h3 class="storytelling-event-title"><?php echo htmlspecialchars($event['title']); ?></h3>
                                    <p class="storytelling-event-description">
                                        <?php echo htmlspecialchars($event['description']); ?>
                                    </p>
                                </div>
                                <div class="storytelling-event-meta">
                                    <div class="storytelling-event-meta-item">
                                        📅 <?php echo htmlspecialchars($event['date']); ?>
                                    </div>
                                    <div class="storytelling-event-meta-item">
                                        🕐 <?php echo htmlspecialchars($event['time']); ?>
                                    </div>
                                    <div class="storytelling-event-meta-item">
                                        📍 <?php echo htmlspecialchars($event['location_name']); ?>
                                    </div>
                                </div>
                                <div class="storytelling-event-actions">
                                    <div class="storytelling-event-price">€<?php echo htmlspecialchars($event['price']); ?></div>
                                    <button class="storytelling-event-btn" onclick="addToProgram(<?php echo $event['session_id']; ?>)">Add to Program</button>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- Info Section -->
        <section class="storytelling-info" aria-labelledby="info-title">
            <div class="info-icon">ℹ️</div>
            <div class="info-content">
                <h3 id="info-title">Additional Information</h3>
                <p>
                    <?php
                    $infoParagraph = $storiesContent['info_paragraph'] ?? "All storytelling events are suitable for ages 12 and above unless specifically marked as children's events. Tickets can be purchased online or at the venue 30 minutes before each performance. In case of rain, outdoor events will be moved to covered locations nearby.";
                    $infoPlain = preg_replace('#</p>\s*<p[^>]*>#i', "\n\n", $infoParagraph);
                    $infoPlain = str_replace(['<br>', '<br/>', '<br />'], "\n", $infoPlain);
                    echo nl2br(htmlspecialchars(strip_tags($infoPlain), ENT_QUOTES, 'UTF-8'), false);
                    ?>
                </p>
            </div>
        </section>
    </main>
</div>

<?php require __DIR__ . '/../../partials/footer.php'; ?>