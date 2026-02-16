<?php
// Festival Homepage Implementation
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>The Haarlem Festival - 2026</title>
    <link rel="stylesheet" href="/css/globals.css" />
    <link rel="stylesheet" href="/css/styleguide.css" />
    <link rel="stylesheet" href="/css/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700&family=Josefin+Sans:wght@400;700&family=Poppins:wght@400;500;600;700;800;900&family=Playfair+Display:wght@700&family=Wix+Madefor+Display:wght@400;600;700;800&family=Arimo:wght@400;700&family=Lora:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="festival-homepage">
        <!-- Hero Section with Navigation -->
        <header class="hero-section">
            <img class="hero-image" src="/img/78dace7c-a56b-4ac5-a7fb-471cfe2bfebc.png" alt="Haarlem Festival" />
            
            <!-- Navigation Bar -->
            <nav class="navigation-bar" role="navigation" aria-label="Main navigation">
                <a href="/" class="nav-brand">
                    <div class="brand-icon">
                        <svg width="34" height="34" viewBox="0 0 34 34" fill="none" aria-hidden="true">
                            <path d="M17 2L20.09 8.26L27 9.27L22 14.14L23.18 21.02L17 17.77L10.82 21.02L12 14.14L7 9.27L13.91 8.26L17 2Z" fill="url(#brand-gradient)"/>
                            <defs>
                                <linearGradient id="brand-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="47%" style="stop-color:#FFF8F0"/>
                                    <stop offset="100%" style="stop-color:#E59B42"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <div class="brand-text">THE HAARLEM<br/>FESTIVAL</div>
                </a>
                
                <div class="nav-menu">
                    <a href="/events/yummy" class="nav-item">
                        <svg width="12" height="14" viewBox="0 0 12 14" fill="currentColor" aria-hidden="true">
                            <path d="M6 0C4.34 0 3 1.34 3 3V5H2C0.9 5 0 5.9 0 7V12C0 13.1 0.9 14 2 14H10C11.1 14 12 13.1 12 12V7C12 5.9 11.1 5 10 5H9V3C9 1.34 7.66 0 6 0ZM5 3C5 2.45 5.45 2 6 2C6.55 2 7 2.45 7 3V5H5V3Z"/>
                        </svg>
                        YUMMY
                    </a>
                    <a href="/events/jazz" class="nav-item">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="currentColor" aria-hidden="true">
                            <path d="M7 0C3.13 0 0 3.13 0 7C0 10.87 3.13 14 7 14C10.87 14 14 10.87 14 7C14 3.13 10.87 0 7 0ZM7 12C4.24 12 2 9.76 2 7C2 4.24 4.24 2 7 2C9.76 2 12 4.24 12 7C12 9.76 9.76 12 7 12ZM7 3C6.45 3 6 3.45 6 4V7C6 7.55 6.45 8 7 8C7.55 8 8 7.55 8 7V4C8 3.45 7.55 3 7 3Z"/>
                        </svg>
                        JAZZ
                    </a>
                    <a href="/events/history" class="nav-item">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="currentColor" aria-hidden="true">
                            <path d="M7 0C3.13 0 0 3.13 0 7C0 10.87 3.13 14 7 14C10.87 14 14 10.87 14 7C14 3.13 10.87 0 7 0ZM7 12C4.24 12 2 9.76 2 7C2 4.24 4.24 2 7 2C9.76 2 12 4.24 12 7C12 9.76 9.76 12 7 12ZM7 3V7L10 9L11 8L8 6V3H7Z"/>
                        </svg>
                        HISTORY
                    </a>
                    <a href="/events/stories" class="nav-item">
                        <svg width="16" height="12" viewBox="0 0 16 12" fill="currentColor" aria-hidden="true">
                            <path d="M14 0H2C0.9 0 0 0.9 0 2V10C0 11.1 0.9 12 2 12H14C15.1 12 16 11.1 16 10V2C16 0.9 15.1 0 14 0ZM14 10H2V2H14V10Z"/>
                        </svg>
                        STORYTELLING
                    </a>
                </div>
                
                <div class="nav-actions">
                    <div class="language-selector" role="group" aria-label="Language selection">
                        <button class="lang-btn active" aria-pressed="true">EN</button>
                        <span>|</span>
                        <button class="lang-btn">NL</button>
                    </div>
                    <button class="my-program-btn">
                        <svg width="16" height="14" viewBox="0 0 16 14" fill="currentColor" aria-hidden="true">
                            <path d="M8 0C3.58 0 0 3.58 0 8C0 12.42 3.58 16 8 16C12.42 16 16 12.42 16 8C16 3.58 12.42 0 8 0ZM8 14C4.69 14 2 11.31 2 8C2 4.69 4.69 2 8 2C11.31 2 14 4.69 14 8C14 11.31 11.31 14 8 14ZM8 3V8L12 10L11 11.5L8 10V3Z"/>
                        </svg>
                        My Program
                    </button>
                </div>
            </nav>
        </header>

        <!-- Welcome Section -->
        <main class="main-content">
            <section class="welcome-section" aria-labelledby="welcome-title">
                <div class="container">
                    <div class="welcome-content">
                        <h1 id="welcome-title" class="welcome-title">Welcome to The Haarlem Festival</h1>
                        <p class="welcome-description">
                            Discover what the city of Haarlem has to offer at the Haarlem Festival. From jazz to food, there is
                            something for everyone.
                        </p>
                    </div>
                    
                    <article class="festival-intro">
                        <header class="festival-header">
                            <div class="discover-section">
                                <div class="divider"></div>
                                <div class="discover-text">DISCOVER</div>
                            </div>
                            <h2 class="festival-title">The Haarlem Festival</h2>
                        </header>
                        
                        <div class="festival-main">
                            <div class="festival-visual">
                                <div class="festival-image-container">
                                    <div class="festival-image"></div>
                                    <div class="image-overlay"></div>
                                </div>
                                <div class="date-badge">
                                    <div class="year">2026</div>
                                    <div class="dates">JULY 23-26</div>
                                </div>
                            </div>
                            
                            <div class="festival-content">
                                <div class="festival-description">
                                    <p class="main-description">
                                        Experience four unforgettable days where Haarlem transforms into a vibrant celebration of music,
                                        culinary artistry, and cultural heritage. The Haarlem Festival brings together world-class
                                        performances, intimate dining experiences, and the timeless beauty of one of the Netherlands'
                                        most enchanting cities.
                                    </p>
                                    <p class="secondary-description">
                                        From jazz echoing through historic venues to innovative cuisine in centuries-old buildings, every
                                        moment is crafted to inspire and delight. Join thousands of culture enthusiasts in discovering what
                                        makes Haarlem a destination where tradition meets contemporary creativity.
                                    </p>
                                </div>
                                
                                <div class="festival-actions">
                                    <button class="btn-primary">
                                        <span>DISCOVER THE FESTIVAL</span>
                                        <svg width="14" height="16" viewBox="0 0 14 16" fill="currentColor" aria-hidden="true">
                                            <path d="M7 0L6 1L10 5H0V7H10L6 11L7 12L13 6L7 0Z"/>
                                        </svg>
                                    </button>
                                    <button class="btn-secondary">
                                        <svg width="14" height="16" viewBox="0 0 14 16" fill="currentColor" aria-hidden="true">
                                            <path d="M7 0L1 6L7 12L6 11L2 7H12V5H2L6 1L7 0Z"/>
                                        </svg>
                                        <span>VIEW SCHEDULE</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            </section>

            <!-- Events Section -->
            <section class="events-section" aria-labelledby="events-title">
                <div class="container">
                    <h2 id="events-title" class="visually-hidden">Festival Events</h2>
                    
                    <?php 
                    // Debug: Show if events data exists
                    if (isset($eventsData) && !empty($eventsData)): 
                        foreach ($eventsData as $event): 
                    ?>
                    <article class="event-card <?= htmlspecialchars($event['type'] ?? '') ?>-event">
                        <?php if ($event['image_position'] ?? 'left' === 'left'): ?>
                            <img class="event-image" src="<?= htmlspecialchars($event['image'] ?? '') ?>" alt="<?= htmlspecialchars($event['alt_text'] ?? '') ?>" />
                        <?php endif; ?>
                        
                        <div class="event-content">
                            <div class="event-info">
                                <h3 class="event-title"><?= htmlspecialchars($event['title'] ?? '') ?></h3>
                                <p class="event-description">
                                    <?= htmlspecialchars($event['description'] ?? '') ?>
                                </p>
                            </div>
                            <a href="<?= htmlspecialchars($event['link'] ?? '') ?>" class="event-btn">View Details...</a>
                        </div>
                        
                        <?php if (($event['image_position'] ?? 'left') === 'right'): ?>
                            <img class="event-image" src="<?= htmlspecialchars($event['image'] ?? '') ?>" alt="<?= htmlspecialchars($event['alt_text'] ?? '') ?>" />
                        <?php endif; ?>
                    </article>
                    <?php 
                        endforeach; 
                    else: 
                        // Fallback static events if dynamic data fails
                    ?>
                    <!-- Jazz Event -->
                    <article class="event-card jazz-event">
                        <img class="event-image" src="/img/jazz-festival.jpg" alt="Haarlem Jazz" />
                        <div class="event-content">
                            <div class="event-info">
                                <h3 class="event-title">Haarlem Jazz</h3>
                                <p class="event-description">
                                    Welcome to Haarlem Jazz – where the city resonates with the soulful notes of jazz. Explore the artists,
                                    events, and the dynamic vibe of this enchanting Dutch festival right here on our Haarlem Jazz page. Get
                                    ready for a musical journey that defines the spirit of jazz in the heart of Haarlem!
                                </p>
                            </div>
                            <a href="/events/jazz" class="event-btn">View Details...</a>
                        </div>
                    </article>

                    <!-- Storytelling Event -->
                    <article class="event-card storytelling-event">
                        <img class="event-image" src="/img/storytelling.jpg" alt="Storytelling Event" />
                        <div class="event-content">
                            <div class="event-info">
                                <h3 class="event-title">Storytelling</h3>
                                <p class="event-description">
                                    Step into the world of one of our featured storytellers and discover what makes their voice unique. This
                                    page invites you to explore their craft, their stories, and the experiences they bring to the festival.
                                </p>
                            </div>
                            <a href="/events/stories" class="event-btn">View Details...</a>
                        </div>
                    </article>

                    <!-- Yummy Event -->
                    <article class="event-card yummy-event">
                        <img class="event-image" src="/img/food-festival.jpg" alt="Yummy Food Festival" />
                        <div class="event-content">
                            <div class="event-info">
                                <h3 class="event-title">Yummy!</h3>
                                <p class="event-description">
                                    Get excited for the festival! Check out all the tasty restaurants and stay tuned for a closer look at
                                    two of them, including pics, chef info, and a sneak peek at their delicious dishes. It's foodie
                                    heaven coming your way!
                                </p>
                            </div>
                            <a href="/events/yummy" class="event-btn">View Details...</a>
                        </div>
                    </article>

                    <!-- History Event -->
                    <article class="event-card history-event">
                        <img class="event-image" src="/img/history-tour.jpg" alt="History Tour" />
                        <div class="event-content">
                            <div class="event-info">
                                <h3 class="event-title">Stroll Through History</h3>
                                <p class="event-description">
                                    Participate in an amazing historical tour through the beautiful city of Haarlem. From July 28th to July
                                    31st you can participate in such a tour.<br/>
                                    In a duration of 2.5 hours you will be able to visit 9 venues which will surely impress you!
                                </p>
                            </div>
                            <a href="/events/history" class="event-btn">View Details...</a>
                        </div>
                    </article>
                    <?php endif; ?>
                </div>
            </section>
        </main>

        <!-- Footer -->
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
                    <p>&copy; 2025 The Festival Haarlem. All rights reserved. • Privacy Policy • Terms of Service</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
