<?php require __DIR__ . '/../../partials/header.php'; ?>

<h1>Storytelling Events</h1>
<p>Experience the captivating stories of Haarlem.</p>

<?php
// Storytelling Event Page Implementation
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>The Art of Storytelling - Haarlem Festival 2026</title>
    <link rel="stylesheet" href="/css/globals.css" />
    <link rel="stylesheet" href="/css/styleguide.css" />
    <link rel="stylesheet" href="/css/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700&family=Josefin+Sans:wght@400;700&family=Poppins:wght@400;500;600;700;800;900&family=Playfair+Display:wght@700&family=Wix+Madefor+Display:wght@400;600;700;800&family=Arimo:wght@400;700&family=Lora:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <script>
        function addToProgram(sessionId) {
            // Simple alert for now - in a real application, this would add to cart/database
            alert('Added to program! Session ID: ' + sessionId);
            
            // In a real implementation, you would:
            // 1. Send AJAX request to add to shopping cart
            // 2. Update UI to show item is in program
            // 3. Maybe show a success notification
        }
        
        // Function to handle filter changes
        function applyFilter(filterType, value) {
            const url = new URL(window.location);
            if (value && value !== 'all') {
                url.searchParams.set(filterType, value);
            } else {
                url.searchParams.delete(filterType);
            }
            window.location.href = url.toString();
        }
    </script>
</head>
<body>
    <div class="storytelling-page">
        <!-- Hero Section -->
        <header class="storytelling-hero">
            <img class="storytelling-hero-image" src="/img/storytelling-hero.jpg" alt="Storytelling Event" />
            
            <!-- Hero Content -->
            <div class="storytelling-hero-overlay">
                <div class="storytelling-hero-content">
                    <h1 class="storytelling-hero-title">
                        <?php
                        $heroTitle = $storiesContent['hero_title'] ?? 'The Art of <span class="highlight">Storytelling</span>';
                        echo $heroTitle;
                        ?>
                    </h1>
                    <p class="storytelling-hero-description">
                        <?php
                        $heroDescription = $storiesContent['hero_description'] ?? 'Experience the magic of oral tradition as master storytellers weave tales that transport you through time and imagination. From ancient myths to contemporary narratives, discover the power of stories that connect us all.';
                        echo $heroDescription;
                        ?>
                    </p>
                    <a href="#schedule" class="storytelling-hero-cta">View Event Schedule</a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="storytelling-main">
            <!-- Featured Section -->
            <section class="storytelling-featured" aria-labelledby="featured-title">
                <div class="storytelling-featured-content">
                    <h2 id="featured-title"><?php echo htmlspecialchars($featuredStoryteller['title'] ?? 'Experience the Art of Storytelling in Haarlem'); ?></h2>
                    <p>
                        <?php echo htmlspecialchars($featuredStoryteller['description'] ?? 'Join us for an enchanting journey through the world of storytelling, where words come alive and imagination knows no bounds. Our featured storytellers bring decades of experience and unique perspectives to create unforgettable experiences.'); ?>
                    </p>
                    <p>
                        From intimate gatherings in historic venues to grand performances under the stars, each story is carefully crafted 
                        to captivate audiences of all ages and backgrounds.
                    </p>
                </div>
                <div class="storytelling-featured-image">
                    <img src="<?php echo htmlspecialchars($featuredStoryteller['image'] ?? '/img/featured-storyteller.jpg'); ?>" alt="Featured Storyteller" />
                    <div class="storyteller-name"><?php echo htmlspecialchars($featuredStoryteller['guide_name'] ?? 'Elena van der Meer'); ?></div>
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
                        <div class="filter-group">
                            <div class="filter-label">Date</div>
                            <div class="filter-buttons">
                                <button class="filter-btn <?php echo (!isset($_GET['date']) || $_GET['date'] === 'all') ? 'active' : ''; ?>" onclick="window.location.href='/events/stories'">All</button>
                                <button class="filter-btn <?php echo ($_GET['date'] ?? '') === '2026-07-24' ? 'active' : ''; ?>" onclick="window.location.href='/events/stories?date=2026-07-24'">Thursday</button>
                                <button class="filter-btn <?php echo ($_GET['date'] ?? '') === '2026-07-25' ? 'active' : ''; ?>" onclick="window.location.href='/events/stories?date=2026-07-25'">Friday</button>
                                <button class="filter-btn <?php echo ($_GET['date'] ?? '') === '2026-07-26' ? 'active' : ''; ?>" onclick="window.location.href='/events/stories?date=2026-07-26'">Saturday</button>
                                <button class="filter-btn <?php echo ($_GET['date'] ?? '') === '2026-07-27' ? 'active' : ''; ?>" onclick="window.location.href='/events/stories?date=2026-07-27'">Sunday</button>
                            </div>
                        </div>
                        <div class="filter-group">
                            <div class="filter-label">Time</div>
                            <div class="filter-buttons">
                                <button class="filter-btn <?php echo (!isset($_GET['time']) || $_GET['time'] === 'all') ? 'active' : ''; ?>" onclick="window.location.href='/events/stories'">All</button>
                                <button class="filter-btn <?php echo ($_GET['time'] ?? '') === 'morning' ? 'active' : ''; ?>" onclick="window.location.href='/events/stories?time=morning'">Morning</button>
                                <button class="filter-btn <?php echo ($_GET['time'] ?? '') === 'afternoon' ? 'active' : ''; ?>" onclick="window.location.href='/events/stories?time=afternoon'">Afternoon</button>
                                <button class="filter-btn <?php echo ($_GET['time'] ?? '') === 'evening' ? 'active' : ''; ?>" onclick="window.location.href='/events/stories?time=evening'">Evening</button>
                            </div>
                        </div>
                        <div class="filter-group">
                            <div class="filter-label">Genre</div>
                            <div class="filter-buttons">
                                <button class="filter-btn active" onclick="window.location.href='/events/stories'">All</button>
                                <button class="filter-btn" onclick="window.location.href='/events/stories?genre=folklore'">Folklore</button>
                                <button class="filter-btn" onclick="window.location.href='/events/stories?genre=contemporary'">Contemporary</button>
                                <button class="filter-btn" onclick="window.location.href='/events/stories?genre=children'">Children</button>
                                <button class="filter-btn" onclick="window.location.href='/events/stories?genre=historical'">Historical</button>
                            </div>
                        </div>
                        <div class="filter-group">
                            <div class="filter-label">Location</div>
                            <div class="filter-buttons">
                                <button class="filter-btn <?php echo (!isset($_GET['location']) || $_GET['location'] === 'all') ? 'active' : ''; ?>" onclick="window.location.href='/events/stories'">All</button>
                                <?php foreach ($locations as $location): ?>
                                    <button class="filter-btn <?php echo ($_GET['location'] ?? '') === (string)$location['id'] ? 'active' : ''; ?>" onclick="window.location.href='/events/stories?location=<?php echo $location['id']; ?>'">
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
                        echo $infoParagraph;
                        ?>
                    </p>
                </div>
            </section>
        </main>

        <!-- Footer (Same as Homepage) -->
        <footer class="festival-footer" role="contentinfo">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-brand">
                        <div class="brand-icon">
                            <svg width="34" height="34" viewBox="0 0 34 34" fill="none" aria-hidden="true">
                                <path d="M17 2L20.09 8.26L27 9.27L22 14.14L23.18 21.02L17 17.77L10.82 21.02L12 14.14L7 9.27L13.91 8.26L17 2Z" fill="url(#footer-brand-gradient)"/>
                                <defs>
                                    <linearGradient id="footer-brand-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="47%" style="stop-color:#FFF8F0"/>
                                        <stop offset="100%" style="stop-color:#E59B42"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
                        <div class="brand-text">THE HAARLEM<br/>FESTIVAL</div>
                    </div>
                    
                    <nav class="footer-links" aria-label="Footer navigation">
                        <div class="footer-column">
                            <h4>Festival</h4>
                            <ul>
                                <li><a href="/events/yummy">Yummy Food Event</a></li>
                                <li><a href="/events/jazz">Jazz Event</a></li>
                                <li><a href="/events/history">History Event</a></li>
                                <li><a href="/events/stories">Storytelling Event</a></li>
                            </ul>
                        </div>
                        
                        <div class="footer-column">
                            <h4>Information</h4>
                            <ul>
                                <li><a href="/about">About</a></li>
                                <li><a href="/accessibility">Accessibility</a></li>
                                <li><a href="/contact">Contact</a></li>
                            </ul>
                        </div>
                        
                        <div class="footer-column">
                            <h4>Support</h4>
                            <ul>
                                <li><a href="/customer-service">Customer Service</a></li>
                                <li><a href="/booking-help">Booking Help</a></li>
                                <li><a href="/cancellation">Cancellation</a></li>
                            </ul>
                        </div>
                        
                        <div class="footer-column">
                            <h4>Follow Us</h4>
                            <div class="social-links">
                                <!-- Social media icons would go here -->
                            </div>
                        </div>
                    </nav>
                </div>
                
                <div class="footer-bottom">
                    <p>&copy; 2026 The Haarlem Festival. All rights reserved. • Privacy Policy • Terms of Service</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>