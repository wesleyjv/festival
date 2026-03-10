<?php
$mainClass = '';
require __DIR__ . '/../partials/header.php';
?>

<style>
/* ==============================
   HOMEPAGE
   ============================== */

/* Hero */
.hp-hero {
    position: relative;
    min-height: 100vh;
    background: #111;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    overflow: hidden;
    margin-top: -56px;
}
.hp-hero-bg {
    position: absolute;
    inset: 0;
    background: url('https://placehold.co/1920x1080/1a1a1a/333333?text=+') center/cover no-repeat;
    opacity: 0.55;
}
.hp-hero-content {
    position: relative;
    z-index: 2;
    padding: 120px 20px 60px;
}
.hp-hero-content .festival-the {
    font-size: clamp(1.6rem, 3.5vw, 3rem);
    font-weight: 900;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 0.35em;
    line-height: 1;
    margin: 0;
}
.hp-hero-content .festival-title {
    font-size: clamp(4.5rem, 13vw, 10rem);
    font-weight: 900;
    color: #f5c218;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    line-height: 1;
    text-shadow: 0 6px 40px rgba(0,0,0,0.5);
    font-style: italic;
    margin: 0;
}
.hp-hero-content .festival-date {
    color: rgba(255,255,255,0.7);
    font-size: 0.9rem;
    letter-spacing: 2px;
    margin-top: 20px;
    text-transform: uppercase;
}

/* Welcome */
.hp-welcome {
    padding: 72px 20px;
    background: #fff;
    text-align: center;
}
.hp-welcome h2 {
    font-size: 1.9rem;
    font-weight: 800;
    margin-bottom: 16px;
}
.hp-welcome p {
    max-width: 580px;
    margin: 0 auto;
    color: #666;
    font-size: 0.95rem;
    line-height: 1.85;
}

/* About */
.hp-about {
    background: #efefef;
    padding: 72px 0;
}
.hp-about .about-img-wrap {
    position: relative;
}
.hp-about .about-img-wrap img {
    width: 100%;
    height: 360px;
    object-fit: cover;
    border-radius: 4px;
    display: block;
}
.hp-about .year-badge {
    position: absolute;
    bottom: 16px;
    left: 24px;
    background: #e07b2a;
    color: #fff;
    font-size: 1.8rem;
    font-weight: 900;
    padding: 12px 22px;
    border-radius: 4px;
    line-height: 1;
}
.hp-about .about-content {
    padding-left: 48px;
}
.section-label {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    color: #e07b2a;
    margin-bottom: 8px;
}
.hp-about .about-content h2 {
    font-size: 1.9rem;
    font-weight: 800;
    margin-bottom: 14px;
}
.hp-about .about-content p {
    color: #555;
    font-size: 0.92rem;
    line-height: 1.85;
    margin-bottom: 24px;
}
.btn-fp {
    background: #e07b2a;
    color: #fff;
    border: none;
    padding: 10px 22px;
    font-weight: 700;
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-radius: 4px;
    text-decoration: none;
    display: inline-block;
    transition: background 0.2s;
}
.btn-fp:hover { background: #c86a1a; color: #fff; }
.btn-fo {
    border: 2px solid #333;
    color: #333;
    background: transparent;
    padding: 9px 22px;
    font-weight: 700;
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-radius: 4px;
    text-decoration: none;
    display: inline-block;
    margin-left: 10px;
    transition: background 0.2s, color 0.2s;
}
.btn-fo:hover { background: #333; color: #fff; }

/* Experiences */
.hp-experiences {
    background: #1e1e1e;
    padding: 72px 0;
    color: #fff;
}
.hp-experiences .section-label { color: #e07b2a; }
.hp-experiences h2 {
    font-size: 1.9rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 16px;
}
.hp-experiences p {
    color: rgba(255,255,255,0.55);
    font-size: 0.92rem;
    line-height: 1.85;
}
.exp-main-img {
    width: 100%;
    height: 240px;
    object-fit: cover;
    border-radius: 4px;
    display: block;
    margin-bottom: 16px;
}
.artist-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid rgba(255,255,255,0.08);
}
.artist-item:last-child { border-bottom: none; }
.artist-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    background: #444;
}
.artist-role {
    font-size: 0.72rem;
    color: rgba(255,255,255,0.4);
    text-transform: uppercase;
    letter-spacing: 0.8px;
    line-height: 1.2;
}
.artist-name {
    font-size: 0.85rem;
    font-weight: 600;
    color: #fff;
    line-height: 1.3;
}

/* Festival Events */
.hp-events {
    background: #f5f5f5;
    padding: 72px 0;
}
.hp-events .section-heading {
    font-size: 2.4rem;
    font-weight: 900;
    text-align: center;
    margin-bottom: 48px;
}
.event-row {
    background: #fff;
    border-radius: 6px;
    overflow: hidden;
    margin-bottom: 22px;
    box-shadow: 0 2px 14px rgba(0,0,0,0.06);
    display: flex;
    min-height: 220px;
}
.event-row.reverse { flex-direction: row-reverse; }
.event-row .er-img {
    width: 44%;
    flex-shrink: 0;
    object-fit: cover;
    display: block;
}
.event-row .er-info {
    padding: 32px 36px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.event-row .er-info .section-label { color: #e07b2a; }
.event-row .er-info h3 {
    font-size: 1.65rem;
    font-weight: 900;
    margin-bottom: 10px;
}
.event-row .er-info p {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.75;
    margin-bottom: 20px;
}
.btn-event {
    background: #e07b2a;
    color: #fff;
    border: none;
    padding: 9px 20px;
    font-size: 0.78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-radius: 4px;
    text-decoration: none;
    display: inline-block;
    align-self: flex-start;
    transition: background 0.2s;
}
.btn-event:hover { background: #c86a1a; color: #fff; }

/* Schedule */
.hp-schedule {
    background: #2b2b2b;
    padding: 72px 0;
    color: #fff;
}
.hp-schedule h2 {
    font-size: 2.4rem;
    font-weight: 900;
    text-align: center;
    margin-bottom: 10px;
}
.hp-schedule .schedule-sub {
    text-align: center;
    color: rgba(255,255,255,0.5);
    font-size: 0.88rem;
    max-width: 600px;
    margin: 0 auto 36px;
    line-height: 1.75;
}
.schedule-box {
    background: #fff;
    border-radius: 10px;
    padding: 28px 28px 12px;
    color: #333;
}
.sched-day-block { margin-bottom: 24px; }
.schedule-day {
    font-size: 1rem;
    font-weight: 800;
    margin-bottom: 2px;
}
.schedule-count {
    font-size: 0.76rem;
    color: #aaa;
    margin-bottom: 12px;
}
.schedule-cols {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}
.sched-slot {
    border-radius: 6px;
    padding: 10px 12px;
}
.sched-cat {
    font-weight: 800;
    font-size: 0.73rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}
.sched-name {
    font-size: 0.72rem;
    opacity: 0.85;
    margin-bottom: 3px;
}
.sched-time {
    font-size: 0.68rem;
    opacity: 0.65;
}
.sched-jazz    { background: #dce8ff; color: #1a4b9e; }
.sched-history { background: #f4e5d3; color: #7a3e0a; }
.sched-yummy   { background: #ffe8d0; color: #a04500; }
.sched-stories { background: #fff4cc; color: #7a5c00; }

/* Locations */
.hp-locations {
    padding: 72px 0;
    background: #fff;
}
.hp-locations h2 {
    font-size: 2.4rem;
    font-weight: 900;
    text-align: center;
    margin-bottom: 40px;
}
.map-legend { padding-right: 28px; }
.legend-item {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 14px;
    font-size: 0.9rem;
    font-weight: 600;
}
.legend-dot {
    width: 13px;
    height: 13px;
    border-radius: 50%;
    flex-shrink: 0;
}
img.locations-map {
    width: 100%;
    height: 360px;
    object-fit: cover;
    border-radius: 8px;
    display: block;
}
@media (max-width: 767px) {
    .map-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 8px 24px;
        padding-right: 0;
        margin-bottom: 20px;
    }
    .legend-item { margin-bottom: 0; }
}

/* Hero CTAs */
.hero-ctas {
    margin-top: 32px;
    display: flex;
    gap: 14px;
    justify-content: center;
    flex-wrap: wrap;
}
.btn-hero-primary {
    background: #f5c218;
    color: #111;
    font-weight: 800;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    padding: 13px 30px;
    border-radius: 4px;
    text-decoration: none;
    transition: background 0.2s;
}
.btn-hero-primary:hover { background: #e0b000; color: #111; }
.btn-hero-outline {
    border: 2px solid rgba(255,255,255,0.65);
    color: #fff;
    font-weight: 700;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    padding: 11px 30px;
    border-radius: 4px;
    text-decoration: none;
    transition: background 0.2s;
}
.btn-hero-outline:hover { background: rgba(255,255,255,0.1); color: #fff; }
.hero-scroll {
    position: absolute;
    bottom: 32px;
    left: 50%;
    transform: translateX(-50%);
    color: rgba(255,255,255,0.45);
    font-size: 1.6rem;
    text-decoration: none;
    animation: hp-bounce 2.2s ease-in-out infinite;
    z-index: 2;
    line-height: 1;
}
@keyframes hp-bounce {
    0%, 100% { transform: translateX(-50%) translateY(0); }
    50%       { transform: translateX(-50%) translateY(9px); }
}

/* Stats bar */
.hp-stats {
    background: #111;
    padding: 28px 0;
}
.hp-stats-grid {
    display: flex;
    align-items: center;
    justify-content: space-evenly;
    flex-wrap: wrap;
}
.hp-stat {
    flex: 1;
    text-align: center;
    padding: 14px 20px;
    min-width: 120px;
}
.hp-stat-divider {
    width: 1px;
    height: 44px;
    background: rgba(255,255,255,0.12);
    flex-shrink: 0;
}
.hp-stats .stat-number {
    font-size: 2.2rem;
    font-weight: 900;
    color: #f5c218;
    line-height: 1;
    margin-bottom: 5px;
}
.hp-stats .stat-label {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 1.8px;
    color: rgba(255,255,255,0.45);
}
@media (max-width: 480px) {
    .hp-stat-divider { display: none; }
    .hp-stat { flex: 0 0 50%; border-bottom: 1px solid rgba(255,255,255,0.08); padding: 16px 8px; }
}

/* Ticket CTA */
.hp-ticket-cta {
    background: #e07b2a;
    padding: 64px 20px;
    text-align: center;
}
.hp-ticket-cta h2 {
    font-size: 2.1rem;
    font-weight: 900;
    color: #fff;
    margin-bottom: 10px;
}
.hp-ticket-cta p {
    color: rgba(255,255,255,0.85);
    font-size: 0.95rem;
    margin-bottom: 28px;
    max-width: 480px;
    margin-left: auto;
    margin-right: auto;
}
.btn-ticket {
    background: #fff;
    color: #e07b2a;
    font-weight: 800;
    font-size: 0.82rem;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    padding: 14px 36px;
    border-radius: 4px;
    text-decoration: none;
    display: inline-block;
    transition: background 0.2s, color 0.2s;
}
.btn-ticket:hover { background: #111; color: #fff; }

/* Responsive */
@media (max-width: 991px) {
    .hp-about .about-content { padding-left: 0; margin-top: 44px; }
}
@media (max-width: 767px) {
    .event-row, .event-row.reverse { flex-direction: column; }
    .event-row .er-img { width: 100%; height: 200px; }
    .schedule-cols { grid-template-columns: repeat(2, 1fr); }
    .hp-hero-content .festival-title { font-size: 4.5rem; }
}
</style>

<!-- ==============================
     HERO
     ============================== -->
<section class="hp-hero" aria-label="Festival hero">
    <div class="hp-hero-bg"></div>
    <div class="hp-hero-content">
        <p class="festival-the">The</p>
        <h1 class="festival-title">Festival</h1>
        <p class="festival-date">July 23 – 26, 2026 &nbsp;·&nbsp; Haarlem, Netherlands</p>
        <div class="hero-ctas">
            <a href="/tickets" class="btn-hero-primary">Get Tickets</a>
            <a href="#events" class="btn-hero-outline">Explore Events</a>
        </div>
    </div>
    <a href="#about" class="hero-scroll" aria-label="Scroll to about section">
        <i class="bi bi-chevron-down"></i>
    </a>
</section>

<!-- ==============================
     FESTIVAL STATS
     ============================== -->
<div class="hp-stats" aria-label="Festival highlights">
    <div class="container">
        <div class="hp-stats-grid">
            <div class="hp-stat">
                <div class="stat-number">4</div>
                <div class="stat-label">Unique Events</div>
            </div>
            <div class="hp-stat-divider"></div>
            <div class="hp-stat">
                <div class="stat-number">50+</div>
                <div class="stat-label">Artists &amp; Guides</div>
            </div>
            <div class="hp-stat-divider"></div>
            <div class="hp-stat">
                <div class="stat-number">4</div>
                <div class="stat-label">Days of Festivities</div>
            </div>
            <div class="hp-stat-divider"></div>
            <div class="hp-stat">
                <div class="stat-number">1</div>
                <div class="stat-label">Iconic City</div>
            </div>
        </div>
    </div>
</div>

<!-- ==============================
     THE HAARLEM FESTIVAL (ABOUT)
     ============================== -->
<section class="hp-about" id="about" aria-label="About the festival">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 mb-5 mb-lg-0">
                <div class="about-img-wrap">
                    <img src="https://placehold.co/600x360/cccccc/888888?text=Haarlem+City" alt="Haarlem Festival" loading="lazy">
                    <div class="year-badge">2026</div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="about-content">
                    <p class="section-label">The Festival</p>
                    <h2>The Haarlem Festival</h2>
                    <p>
                        Experience fun, unforgettable days where talent is on show.
                        Join a vibrant celebration of music, gastronomy, artistry, and cultural
                        performances. Immerse yourself in the energy of the city, from the
                        world's best stages to intimate culinary tastings and art tours.
                        Celebrate the many facets of Haarlem's history, community, and culture.
                        Each day will delight you and leave you wanting more.
                    </p>
                    <a href="/events" class="btn-fp">Read More</a>
                    <a href="#schedule" class="btn-fo">See Schedule</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==============================
     WHERE EVERY MOMENT BECOMES A MEMORY
     ============================== -->
<section class="hp-experiences" aria-label="Festival experiences">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 mb-5 mb-lg-0">
                <img src="https://placehold.co/600x240/2a2a2a/666666?text=Concert+Stage" alt="Concert" class="exp-main-img" loading="lazy">
                <div class="artist-item">
                    <img src="https://placehold.co/42x42/555/aaa?text=A1" alt="Artist" class="artist-avatar">
                    <div>
                        <div class="artist-role">Nicolas Music:</div>
                        <div class="artist-name">Jazz &amp; Soul Night at De Grote Kerk</div>
                    </div>
                </div>
                <div class="artist-item">
                    <img src="https://placehold.co/42x42/555/aaa?text=A2" alt="Artist" class="artist-avatar">
                    <div>
                        <div class="artist-role">Loretta Harrington:</div>
                        <div class="artist-name">Storytelling at the Windmills</div>
                    </div>
                </div>
                <div class="artist-item">
                    <img src="https://placehold.co/42x42/555/aaa?text=A3" alt="Artist" class="artist-avatar">
                    <div>
                        <div class="artist-role">City Willis / Artist:</div>
                        <div class="artist-name">Yummy Food Tour Through Haarlem</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 ps-lg-5">
                <p class="section-label">The Experiences</p>
                <h2>Where Every Moment<br>Becomes a Memory</h2>
                <p>
                    The Haarlem Festival is more than an event — it's a journey
                    through the unique Jazz performances, captivating storytelling,
                    delicious culinary experiences, and the rich history of this
                    beautiful city.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ==============================
     THE FESTIVAL EVENTS
     ============================== -->
<section class="hp-events" id="events" aria-label="Festival event categories">
    <div class="container">
        <h2 class="section-heading">The Festival Events</h2>

        <!-- Jazz -->
        <div class="event-row">
            <img src="https://placehold.co/700x260/1a1a40/556699?text=Haarlem+Jazz" alt="Haarlem Jazz" class="er-img" loading="lazy">
            <div class="er-info">
                <p class="section-label">The Event</p>
                <h3>Haarlem Jazz</h3>
                <p>
                    Immerse yourself in the soulful sounds of world-class jazz performers.
                    Through the streets and iconic stages of Haarlem, jazz fills the air
                    with rhythm, passion, and unforgettable melodies.
                </p>
                <a href="/events/jazz" class="btn-event">Explore Jazz &rarr;</a>
            </div>
        </div>

        <!-- Storytelling -->
        <div class="event-row reverse">
            <img src="https://placehold.co/700x260/1a0a2a/664466?text=Storytelling" alt="Storytelling" class="er-img" loading="lazy">
            <div class="er-info">
                <p class="section-label">The Event</p>
                <h3>Storytelling</h3>
                <p>
                    Step inside one of the most beautiful storytelling settings you'll ever encounter.
                    Expert storytellers will take you on magical cultural journeys across the
                    stories that still live in Haarlem today.
                </p>
                <a href="/events/stories" class="btn-event">Explore Stories &rarr;</a>
            </div>
        </div>

        <!-- Yummy -->
        <div class="event-row">
            <img src="https://placehold.co/700x260/2a1500/886633?text=Yummy+Food" alt="Yummy" class="er-img" loading="lazy">
            <div class="er-info">
                <p class="section-label">The Event</p>
                <h3>Yummy!</h3>
                <p>
                    Let all the flavours of Haarlem light up your day at the Yummy Event!
                    Indulge in the finest street food and culinary delights on offer.
                    Various cuisines. It's Haarlem Festival coming your way!
                </p>
                <a href="/events/yummy" class="btn-event">Explore Yummy &rarr;</a>
            </div>
        </div>

        <!-- History -->
        <div class="event-row reverse">
            <img src="https://placehold.co/700x260/cccccc/888888?text=History+Walk" alt="History" class="er-img" style="filter: grayscale(60%);" loading="lazy">
            <div class="er-info">
                <p class="section-label">The Event</p>
                <h3>Stroll Through History</h3>
                <p>
                    Tour the unique streets of Haarlem and explore its past. Hear stories
                    from the old days. Haarlem has a lot to share — you'll be surprised
                    what history discovers along the way.
                </p>
                <a href="/events/history" class="btn-event">Explore History &rarr;</a>
            </div>
        </div>
    </div>
</section>

<!-- ==============================
     ALL EVENTS SCHEDULE
     ============================== -->
<section class="hp-schedule" id="schedule" aria-label="Full festival schedule">
    <div class="container">
        <h2>All Events Schedule</h2>
        <p class="schedule-sub">
            Four days, four experiences. Here's what's happening across the festival — plan your visit and make the most of every moment.
        </p>
        <div class="schedule-box">

            <!-- Thursday -->
            <div class="sched-day-block">
                <div class="schedule-day">Thursday, July 23rd</div>
                <div class="schedule-count">4 events</div>
                <div class="schedule-cols">
                    <div class="sched-slot sched-jazz">
                        <div class="sched-cat">Jazz</div>
                        <div class="sched-name">Haarlemse Jazzband</div>
                        <div class="sched-time">18:00 – 20:00</div>
                    </div>
                    <div class="sched-slot sched-history">
                        <div class="sched-cat">History</div>
                        <div class="sched-name">Amsterdam History (Tour)</div>
                        <div class="sched-time">10:00 – 12:00</div>
                    </div>
                    <div class="sched-slot sched-yummy">
                        <div class="sched-cat">Yummy</div>
                        <div class="sched-name">Street Dine Area</div>
                        <div class="sched-time">12:00 – 22:00</div>
                    </div>
                    <div class="sched-slot sched-stories">
                        <div class="sched-cat">Storytelling</div>
                        <div class="sched-name">City Legends</div>
                        <div class="sched-time">19:00 – 21:00</div>
                    </div>
                </div>
            </div>

            <!-- Friday -->
            <div class="sched-day-block">
                <div class="schedule-day">Friday, July 24th</div>
                <div class="schedule-count">4 events</div>
                <div class="schedule-cols">
                    <div class="sched-slot sched-jazz">
                        <div class="sched-cat">Jazz</div>
                        <div class="sched-name">Haarlemse Jazzband</div>
                        <div class="sched-time">18:00 – 21:00</div>
                    </div>
                    <div class="sched-slot sched-history">
                        <div class="sched-cat">History</div>
                        <div class="sched-name">Amsterdam History (Tour)</div>
                        <div class="sched-time">10:30 – 12:30</div>
                    </div>
                    <div class="sched-slot sched-yummy">
                        <div class="sched-cat">Yummy</div>
                        <div class="sched-name">Street Dine Area</div>
                        <div class="sched-time">12:00 – 22:00</div>
                    </div>
                    <div class="sched-slot sched-stories">
                        <div class="sched-cat">Storytelling</div>
                        <div class="sched-name">City Legends</div>
                        <div class="sched-time">19:30 – 21:30</div>
                    </div>
                </div>
            </div>

            <!-- Saturday -->
            <div class="sched-day-block">
                <div class="schedule-day">Saturday, July 25th</div>
                <div class="schedule-count">4 events</div>
                <div class="schedule-cols">
                    <div class="sched-slot sched-jazz">
                        <div class="sched-cat">Jazz</div>
                        <div class="sched-name">Haarlemse Jazzband</div>
                        <div class="sched-time">17:00 – 20:00</div>
                    </div>
                    <div class="sched-slot sched-history">
                        <div class="sched-cat">History</div>
                        <div class="sched-name">Amsterdam History (Tour)</div>
                        <div class="sched-time">10:00 – 13:00</div>
                    </div>
                    <div class="sched-slot sched-yummy">
                        <div class="sched-cat">Yummy</div>
                        <div class="sched-name">Fusion Dine Area</div>
                        <div class="sched-time">12:00 – 22:00</div>
                    </div>
                    <div class="sched-slot sched-stories">
                        <div class="sched-cat">Storytelling</div>
                        <div class="sched-name">Lore &amp; Legends</div>
                        <div class="sched-time">20:00 – 22:00</div>
                    </div>
                </div>
            </div>

            <!-- Sunday -->
            <div class="sched-day-block">
                <div class="schedule-day">Sunday, July 26th</div>
                <div class="schedule-count">4 events</div>
                <div class="schedule-cols">
                    <div class="sched-slot sched-jazz">
                        <div class="sched-cat">Jazz</div>
                        <div class="sched-name">Haarlemse Jazzband</div>
                        <div class="sched-time">15:00 – 18:00</div>
                    </div>
                    <div class="sched-slot sched-history">
                        <div class="sched-cat">History</div>
                        <div class="sched-name">Amsterdam History (Tour)</div>
                        <div class="sched-time">10:00 – 12:00</div>
                    </div>
                    <div class="sched-slot sched-yummy">
                        <div class="sched-cat">Yummy</div>
                        <div class="sched-name">Closing Dine</div>
                        <div class="sched-time">12:00 – 20:00</div>
                    </div>
                    <div class="sched-slot sched-stories">
                        <div class="sched-cat">Storytelling</div>
                        <div class="sched-name">Final Chapter</div>
                        <div class="sched-time">18:00 – 20:00</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ==============================
     ALL EVENT LOCATIONS
     ============================== -->
<section class="hp-locations" aria-label="Event locations">
    <div class="container">
        <h2>All Event Locations</h2>
        <div class="row align-items-start">
            <div class="col-md-3 mb-4 mb-md-0">
                <div class="map-legend">
                    <div class="legend-item">
                        <div class="legend-dot" style="background:#4a7fd6;"></div>
                        <span>Jazz</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot" style="background:#c87a3a;"></div>
                        <span>History</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot" style="background:#e07b2a;"></div>
                        <span>Yummy</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot" style="background:#d4b800;"></div>
                        <span>Storytelling</span>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <img src="https://placehold.co/900x360/e0e0e0/999999?text=Map+of+Haarlem" alt="Map of Haarlem event locations" class="locations-map">
            </div>
        </div>
    </div>
</section>

<!-- ==============================
     TICKET CTA
     ============================== -->
<section class="hp-ticket-cta" aria-label="Get tickets">
    <h2>Don't Miss Out</h2>
    <p>Secure your spot at the Haarlem Festival. Four days of music, food, stories, and history — all in one unforgettable city.</p>
    <a href="/tickets" class="btn-ticket">Get Your Tickets</a>
</section>

<?php require __DIR__ . '/../partials/footer.php'; ?>
