<?php
$mainClass = '';
$hc = $homepageContent ?? [];
$heroBlurb = trim(strip_tags($hc['hero_subtitle'] ?? ''));
if ($heroBlurb === '') {
    $heroBlurb = 'Celebrate music, flavour, heritage, and live stories across Haarlem\'s canals, squares, and hidden venues.';
}
require __DIR__ . '/../partials/header.php';
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap');

.hp {
    --hp-navy: #1a2d42;
    --hp-navy-deep: #152535;
    --hp-gold: #d4a853;
    --hp-gold-bright: #e8c76b;
    --hp-orange: #e07b2a;
    --hp-jazz: #7c5cbf;
    --hp-yummy: #e07b2a;
    --hp-history: #2d8bba;
    --hp-stories: #e6a317;
    font-family: 'DM Sans', 'Segoe UI', system-ui, sans-serif;
    color: #2c3e50;
}

/* ----- Hero ----- */
.hp-hero {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    overflow: hidden;
    margin-top: -56px;
    padding: 100px 20px 80px;
}
.hp-hero__bg {
    position: absolute;
    inset: 0;
    background:
        linear-gradient(180deg, rgba(21, 37, 53, 0.45) 0%, rgba(21, 37, 53, 0.75) 100%),
        url('/img/hero-homepage-haarlem.png') center/cover no-repeat;
}
.hp-hero__inner {
    position: relative;
    z-index: 2;
    max-width: 900px;
}
.hp-hero__eyebrow {
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.35em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.85);
    margin-bottom: 16px;
}
.hp-hero__title {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: clamp(2.8rem, 8vw, 4.6rem);
    font-weight: 600;
    line-height: 1.08;
    margin: 0 0 12px;
    background: linear-gradient(135deg, #fff 0%, var(--hp-gold-bright) 55%, #f5e6c8 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    -webkit-text-fill-color: transparent;
}
.hp-hero__date {
    color: rgba(255, 255, 255, 0.88);
    font-size: 0.95rem;
    font-weight: 500;
    letter-spacing: 0.04em;
    margin-bottom: 18px;
}
.hp-hero__desc {
    color: rgba(255, 255, 255, 0.78);
    font-size: 0.95rem;
    line-height: 1.65;
    max-width: 560px;
    margin: 0 auto 28px;
}
.hp-hero__filters {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    margin-bottom: 28px;
}
.hp-filter-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.22);
    color: #fff;
    font-size: 0.78rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    text-decoration: none;
    transition: background 0.2s, transform 0.2s;
}
.hp-filter-pill:hover {
    background: rgba(255, 255, 255, 0.22);
    color: #fff;
    transform: translateY(-1px);
}
.hp-filter-pill .fi { font-size: 1rem; }
.hp-filter-pill--jazz .fi { color: #c4a8ff; }
.hp-filter-pill--yummy .fi { color: #ffb88c; }
.hp-filter-pill--history .fi { color: #7fd4ff; }
.hp-filter-pill--stories .fi { color: #ffe08a; }

.hp-hero__ctas {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    justify-content: center;
}
.hp-btn-gold {
    background: linear-gradient(180deg, var(--hp-gold-bright) 0%, var(--hp-gold) 100%);
    color: #1a1a1a !important;
    font-weight: 700;
    font-size: 0.75rem;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    padding: 14px 28px;
    border-radius: 6px;
    text-decoration: none;
    border: none;
    transition: filter 0.2s, transform 0.2s;
}
.hp-btn-gold:hover { filter: brightness(1.06); color: #1a1a1a !important; }
.hp-btn-outline-light {
    border: 2px solid rgba(255, 255, 255, 0.65);
    color: #fff !important;
    font-weight: 600;
    font-size: 0.75rem;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    padding: 12px 26px;
    border-radius: 6px;
    text-decoration: none;
    transition: background 0.2s;
}
.hp-btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.12);
    color: #fff !important;
}
.hp-hero__scroll {
    position: absolute;
    bottom: 28px;
    left: 50%;
    transform: translateX(-50%);
    color: rgba(255, 255, 255, 0.45);
    font-size: 1.5rem;
    z-index: 2;
    animation: hp-bob 2.2s ease-in-out infinite;
}
@keyframes hp-bob {
    0%, 100% { transform: translateX(-50%) translateY(0); }
    50% { transform: translateX(-50%) translateY(8px); }
}

/* ----- Welcome ----- */
.hp-welcome {
    padding: 80px 20px;
    background: #fff;
    text-align: center;
}
.hp-welcome h2 {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: clamp(1.85rem, 4vw, 2.4rem);
    font-weight: 600;
    color: var(--hp-navy);
    margin-bottom: 16px;
}
.hp-welcome p {
    max-width: 640px;
    margin: 0 auto;
    color: #5a6570;
    font-size: 1rem;
    line-height: 1.8;
}

/* ----- About (Four days) ----- */
.hp-about {
    padding: 80px 20px;
    background: linear-gradient(180deg, #fff 0%, #fff8f0 100%);
}
.hp-about__grid {
    display: grid;
    grid-template-columns: 1fr 1.1fr;
    gap: 48px;
    align-items: center;
    max-width: 1140px;
    margin: 0 auto;
}
@media (max-width: 991px) {
    .hp-about__grid { grid-template-columns: 1fr; gap: 36px; }
}
.hp-about__img {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 20px 50px rgba(26, 45, 66, 0.15);
    min-height: 420px;
}
.hp-about__img img {
    width: 100%;
    height: 100%;
    min-height: 420px;
    object-fit: cover;
    object-position: left center;
    display: block;
}
.hp-label {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    color: var(--hp-orange);
    margin-bottom: 10px;
}
.hp-about__title {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: clamp(1.9rem, 4vw, 2.65rem);
    font-weight: 600;
    color: var(--hp-navy);
    line-height: 1.15;
    margin-bottom: 18px;
}
.hp-about__title em {
    font-style: normal;
    color: var(--hp-orange);
}
.hp-about__text {
    color: #5a6570;
    font-size: 0.98rem;
    line-height: 1.85;
    margin-bottom: 28px;
}
.hp-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 28px;
}
@media (max-width: 600px) {
    .hp-stats { grid-template-columns: repeat(2, 1fr); }
}
.hp-stat {
    text-align: center;
    padding: 14px 8px;
    background: #fff;
    border-radius: 10px;
    border: 1px solid rgba(26, 45, 66, 0.06);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
}
.hp-stat__icon {
    font-size: 1.35rem;
    margin-bottom: 6px;
    color: var(--hp-orange);
}
.hp-stat__num {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--hp-navy);
    line-height: 1;
}
.hp-stat__lbl {
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: #8899a8;
    margin-top: 6px;
}
.hp-about__btns {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}
.hp-btn-navy {
    background: var(--hp-navy);
    color: #fff !important;
    font-weight: 700;
    font-size: 0.72rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 12px 22px;
    border-radius: 6px;
    text-decoration: none;
    transition: background 0.2s;
}
.hp-btn-navy:hover { background: var(--hp-navy-deep); color: #fff !important; }
.hp-btn-ghost {
    border: 2px solid var(--hp-navy);
    color: var(--hp-navy) !important;
    font-weight: 700;
    font-size: 0.72rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 10px 22px;
    border-radius: 6px;
    text-decoration: none;
    background: #fff;
    transition: background 0.2s;
}
.hp-btn-ghost:hover { background: var(--hp-navy); color: #fff !important; }

/* ----- Features ----- */
.hp-features {
    padding: 80px 20px;
    background: linear-gradient(180deg, #fff8f0 0%, #fff 45%, #f7f9fc 100%);
}
.hp-features__head {
    text-align: center;
    max-width: 640px;
    margin: 0 auto 48px;
}
.hp-features__head h2 {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: clamp(1.85rem, 4vw, 2.5rem);
    font-weight: 600;
    color: var(--hp-navy);
    line-height: 1.2;
}
.hp-features__head em {
    font-style: normal;
    color: var(--hp-orange);
}
.hp-feature-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    max-width: 1100px;
    margin: 0 auto;
}
@media (max-width: 991px) {
    .hp-feature-cards { grid-template-columns: 1fr; max-width: 420px; }
}
.hp-fcard {
    background: #fff;
    border-radius: 14px;
    padding: 28px 24px;
    box-shadow: 0 8px 32px rgba(26, 45, 66, 0.08);
    border: 1px solid rgba(26, 45, 66, 0.05);
}
.hp-fcard__icon {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(224, 123, 42, 0.12) 0%, rgba(212, 168, 83, 0.2) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    color: var(--hp-orange);
    margin-bottom: 16px;
}
.hp-fcard h3 {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--hp-navy);
    margin-bottom: 10px;
}
.hp-fcard p {
    font-size: 0.9rem;
    color: #6b7780;
    line-height: 1.7;
    margin: 0;
}

/* ----- Event grid ----- */
.hp-events {
    padding: 80px 20px;
    background: #f4f6f9;
}
.hp-events__head {
    max-width: 1140px;
    margin: 0 auto 40px;
}
.hp-events__head h2 {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: clamp(1.85rem, 4vw, 2.5rem);
    font-weight: 600;
    color: var(--hp-navy);
}
.hp-events__head em {
    font-style: normal;
    color: var(--hp-orange);
}
.hp-event-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
    max-width: 1140px;
    margin: 0 auto;
}
@media (max-width: 767px) {
    .hp-event-grid { grid-template-columns: 1fr; }
}
.hp-ecard {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 8px 28px rgba(26, 45, 66, 0.08);
    display: flex;
    flex-direction: column;
    height: 100%;
}
.hp-ecard__media {
    position: relative;
    height: 200px;
    overflow: hidden;
}
.hp-ecard__media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.hp-ecard__media--jazz-promo img {
    object-position: center bottom;
}
.hp-ecard__tag {
    position: absolute;
    top: 14px;
    left: 14px;
    font-size: 0.65rem;
    font-weight: 800;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 6px 12px;
    border-radius: 4px;
    color: #fff;
}
.hp-ecard__tag--jazz { background: var(--hp-jazz); }
.hp-ecard__tag--yummy { background: var(--hp-yummy); }
.hp-ecard__tag--history { background: var(--hp-history); }
.hp-ecard__tag--stories { background: var(--hp-stories); color: #1a1a1a; }
.hp-ecard__body {
    padding: 22px 22px 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.hp-ecard__body h3 {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--hp-navy);
    margin-bottom: 10px;
}
.hp-ecard__body p {
    font-size: 0.88rem;
    color: #6b7780;
    line-height: 1.65;
    flex: 1;
    margin-bottom: 14px;
}
.hp-ecard__meta {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    font-size: 0.78rem;
    color: #8899a8;
    margin-bottom: 14px;
}
.hp-ecard__meta span {
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.hp-ecard__link {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--hp-navy);
    text-decoration: none;
    align-self: flex-start;
}
.hp-ecard__link:hover { color: var(--hp-orange); }

/* ----- Schedule matrix ----- */
.hp-schedule {
    padding: 80px 20px;
    background: var(--hp-navy-deep);
    color: #fff;
}
.hp-schedule__head {
    max-width: 1000px;
    margin: 0 auto 32px;
}
.hp-schedule__head h2 {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: clamp(1.85rem, 4vw, 2.5rem);
    font-weight: 600;
    margin-bottom: 8px;
}
.hp-schedule__head em {
    font-style: normal;
    color: var(--hp-gold-bright);
}
.hp-schedule__sub {
    color: rgba(255, 255, 255, 0.55);
    font-size: 0.92rem;
    max-width: 520px;
    line-height: 1.6;
}
.hp-matrix-wrap {
    max-width: 1000px;
    margin: 0 auto;
    overflow-x: auto;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
}
.hp-matrix {
    display: grid;
    grid-template-columns: 120px repeat(4, 1fr);
    min-width: 640px;
}
.hp-matrix__corner {
    padding: 14px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    border-right: 1px solid rgba(255, 255, 255, 0.1);
}
.hp-matrix__day {
    padding: 14px 10px;
    text-align: center;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    border-right: 1px solid rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.85);
}
.hp-matrix__day:last-child { border-right: none; }
.hp-matrix__row-label {
    padding: 16px 14px;
    font-size: 0.7rem;
    font-weight: 800;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    border-right: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
}
.hp-matrix__row-label--jazz { color: #d4c4ff; }
.hp-matrix__row-label--yummy { color: #ffcba4; }
.hp-matrix__row-label--history { color: #9fd8ff; }
.hp-matrix__row-label--stories { color: #ffe6a0; }
.hp-matrix__cell {
    padding: 10px 8px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    border-right: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
}
.hp-matrix__cell:last-child { border-right: none; }
.hp-matrix > *:nth-last-child(-n+5) {
    border-bottom: none;
}
.hp-slot {
    font-size: 0.68rem;
    font-weight: 700;
    text-align: center;
    line-height: 1.3;
    padding: 10px 8px;
    border-radius: 6px;
    width: 100%;
}
.hp-slot--jazz { background: rgba(124, 92, 191, 0.35); color: #e8deff; }
.hp-slot--yummy { background: rgba(224, 123, 42, 0.4); color: #fff0e5; }
.hp-slot--history { background: rgba(45, 139, 186, 0.4); color: #e5f4ff; }
.hp-slot--stories { background: rgba(230, 163, 23, 0.45); color: #fff8e5; }

/* ----- Locations ----- */
.hp-locations {
    padding: 80px 20px 64px;
    background: #fff;
}
.hp-locations__title {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: clamp(1.65rem, 3.5vw, 2.2rem);
    font-style: italic;
    font-weight: 500;
    color: var(--hp-navy);
    text-align: center;
    margin-bottom: 40px;
}
.hp-loc-grid {
    display: grid;
    grid-template-columns: 1fr 1.35fr;
    gap: 40px;
    max-width: 1140px;
    margin: 0 auto;
    align-items: start;
}
@media (max-width: 991px) {
    .hp-loc-grid { grid-template-columns: 1fr; }
}
.hp-venue-list {
    list-style: none;
    padding: 0;
    margin: 0;
}
.hp-venue {
    display: flex;
    gap: 14px;
    margin-bottom: 22px;
    align-items: flex-start;
}
.hp-venue__dot {
    width: 12px;
    height: 12px;
    border-radius: 3px;
    flex-shrink: 0;
    margin-top: 5px;
}
.hp-venue__name {
    font-weight: 700;
    font-size: 0.95rem;
    color: var(--hp-navy);
    margin-bottom: 4px;
}
.hp-venue__addr {
    font-size: 0.82rem;
    color: #6b7780;
    line-height: 1.45;
}
.hp-loc-map {
    position: relative;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 12px 40px rgba(26, 45, 66, 0.12);
    min-height: 380px;
    background: #e8ecf0 url('https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&w=1400&q=70') center/cover no-repeat;
}
.hp-loc-map__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.25) 0%, rgba(26, 45, 66, 0.15) 100%);
    pointer-events: none;
}
.hp-loc-legend {
    position: absolute;
    top: 14px;
    left: 14px;
    background: rgba(255, 255, 255, 0.92);
    backdrop-filter: blur(8px);
    border-radius: 8px;
    padding: 12px 14px;
    font-size: 0.72rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}
.hp-loc-legend div {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 6px;
}
.hp-loc-legend div:last-child { margin-bottom: 0; }
.hp-loc-legend span:first-child {
    width: 10px;
    height: 10px;
    border-radius: 2px;
    flex-shrink: 0;
}

/* ----- Final CTA ----- */
.hp-cta-bar {
    background: var(--hp-navy);
    padding: 36px 20px;
    text-align: center;
}
.hp-cta-bar p {
    color: rgba(255, 255, 255, 0.75);
    font-size: 0.92rem;
    margin-bottom: 16px;
    max-width: 520px;
    margin-left: auto;
    margin-right: auto;
}
</style>

<section class="hp">
<!-- Hero -->
<section class="hp-hero" aria-label="Festival hero">
    <div class="hp-hero__bg" role="presentation"></div>
    <div class="hp-hero__inner">
        <p class="hp-hero__eyebrow">A Haarlem Festival Experience</p>
        <h1 class="hp-hero__title">The Haarlem Festival</h1>
        <p class="hp-hero__date">Thursday 23 &ndash; Sunday 26 July 2026 &nbsp;|&nbsp; Haarlem, Netherlands</p>
        <p class="hp-hero__desc"><?= htmlspecialchars($heroBlurb, ENT_QUOTES, 'UTF-8') ?></p>
        <div class="hp-hero__filters">
            <a class="hp-filter-pill hp-filter-pill--jazz" href="/events/jazz"><i class="bi bi-music-note-beamed fi"></i> Jazz</a>
            <a class="hp-filter-pill hp-filter-pill--yummy" href="/events/yummy"><i class="bi bi-cup-straw fi"></i> Yummy!</a>
            <a class="hp-filter-pill hp-filter-pill--history" href="/events/history"><i class="bi bi-bank fi"></i> History</a>
            <a class="hp-filter-pill hp-filter-pill--stories" href="/events/stories"><i class="bi bi-book fi"></i> Storytelling</a>
        </div>
        <div class="hp-hero__ctas">
            <a href="/tickets" class="hp-btn-gold">Purchase Tickets</a>
            <a href="#schedule" class="hp-btn-outline-light">View Schedule</a>
        </div>
    </div>
    <a href="#welcome" class="hp-hero__scroll" aria-label="Scroll to content"><i class="bi bi-chevron-down"></i></a>
</section>

<!-- Welcome -->
<section class="hp-welcome" id="welcome">
    <div class="container">
        <h2>Welcome to The Haarlem Festival</h2>
        <p>
            Four days of live music, guided walks, culinary discovery, and storytelling in one of the Netherlands&rsquo; most beautiful cities.
            Plan your route, pick your passions, and experience Haarlem at its finest.
        </p>
    </div>
</section>

<!-- About -->
<section class="hp-about" id="about">
    <div class="hp-about__grid">
        <div class="hp-about__img">
            <img src="/img/about-haarlem-windmill-sunset.png" alt="De Adriaan windmill and the Spaarne at sunset, Haarlem" loading="lazy" width="1024" height="685">
        </div>
        <div class="hp-about__copy">
            <p class="hp-label">About the Festival</p>
            <h2 class="hp-about__title">Four Days, <em>One City</em></h2>
            <p class="hp-about__text">
                From intimate jazz rooms to open-air tastings and lantern-lit tales by the canals, the festival weaves together
                the best of Haarlem&rsquo;s culture. Wander between venues, meet artists and chefs, and discover why locals are proud to call this city home.
            </p>
            <div class="hp-stats">
                <div class="hp-stat">
                    <div class="hp-stat__icon"><i class="bi bi-calendar3"></i></div>
                    <div class="hp-stat__num">4</div>
                    <div class="hp-stat__lbl">Days</div>
                </div>
                <div class="hp-stat">
                    <div class="hp-stat__icon"><i class="bi bi-stars"></i></div>
                    <div class="hp-stat__num">50+</div>
                    <div class="hp-stat__lbl">Events</div>
                </div>
                <div class="hp-stat">
                    <div class="hp-stat__icon"><i class="bi bi-geo-alt"></i></div>
                    <div class="hp-stat__num">12+</div>
                    <div class="hp-stat__lbl">Locations</div>
                </div>
                <div class="hp-stat">
                    <div class="hp-stat__icon"><i class="bi bi-heart"></i></div>
                    <div class="hp-stat__num">1</div>
                    <div class="hp-stat__lbl">City</div>
                </div>
            </div>
            <div class="hp-about__btns">
                <a href="#events" class="hp-btn-navy">Explore Events</a>
                <a href="https://www.visithaarlem.com/" class="hp-btn-ghost" target="_blank" rel="noopener noreferrer">Visit the City</a>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="hp-features" aria-label="Why attend">
    <div class="hp-features__head">
        <h2>Where Every Moment Becomes <em>a Memory</em></h2>
    </div>
    <div class="hp-feature-cards">
        <article class="hp-fcard">
            <div class="hp-fcard__icon"><i class="bi bi-music-note-beamed"></i></div>
            <h3>Intimate Venues</h3>
            <p>Historic churches, courtyards, and club stages bring you close to the music and the moment.</p>
        </article>
        <article class="hp-fcard">
            <div class="hp-fcard__icon"><i class="bi bi-cup-straw"></i></div>
            <h3>Culinary Journeys</h3>
            <p>Festival menus, local chefs, and street flavours turn every break into a discovery.</p>
        </article>
        <article class="hp-fcard">
            <div class="hp-fcard__icon"><i class="bi bi-signpost-split"></i></div>
            <h3>Stories in the Streets</h3>
            <p>Guided history walks and live narration connect you to Haarlem&rsquo;s past and present.</p>
        </article>
    </div>
</section>

<!-- Events grid -->
<section class="hp-events" id="events">
    <div class="hp-events__head">
        <h2>The Festival <em>Events</em></h2>
    </div>
    <div class="hp-event-grid">
        <article class="hp-ecard">
            <div class="hp-ecard__media hp-ecard__media--jazz-promo">
                <img src="/img/event-jazz-homepage.png" alt="Haarlem Jazz &amp; more — live outdoor performance at night" loading="lazy" width="1024" height="576">
                <span class="hp-ecard__tag hp-ecard__tag--jazz">Jazz</span>
            </div>
            <div class="hp-ecard__body">
                <h3>Haarlem Jazz</h3>
                <p>Soul, swing, and late-night sessions across the city&rsquo;s finest stages and hidden rooms.</p>
                <div class="hp-ecard__meta">
                    <span><i class="bi bi-geo-alt"></i> Multiple venues</span>
                    <span><i class="bi bi-clock"></i> Jul 23&ndash;26</span>
                </div>
                <a class="hp-ecard__link" href="/events/jazz">View Details &gt;</a>
            </div>
        </article>
        <article class="hp-ecard">
            <div class="hp-ecard__media">
                <img src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=800&q=80" alt="Food festival" loading="lazy" width="600" height="400">
                <span class="hp-ecard__tag hp-ecard__tag--yummy">Yummy!</span>
            </div>
            <div class="hp-ecard__body">
                <h3>Yummy!</h3>
                <p>Curated restaurants and street food celebrating Haarlem&rsquo;s diverse culinary scene.</p>
                <div class="hp-ecard__meta">
                    <span><i class="bi bi-geo-alt"></i> City centre</span>
                    <span><i class="bi bi-clock"></i> Daily 12:00&ndash;22:00</span>
                </div>
                <a class="hp-ecard__link" href="/events/yummy">View Details &gt;</a>
            </div>
        </article>
        <article class="hp-ecard">
            <div class="hp-ecard__media">
                <img src="https://images.unsplash.com/photo-1467269204594-9661b134dd2b?auto=format&fit=crop&w=800&q=80" alt="Historic architecture" loading="lazy" width="600" height="400">
                <span class="hp-ecard__tag hp-ecard__tag--history">History</span>
            </div>
            <div class="hp-ecard__body">
                <h3>Stroll Through History</h3>
                <p>Expert guides reveal monuments, courtyards, and stories behind Haarlem&rsquo;s golden age.</p>
                <div class="hp-ecard__meta">
                    <span><i class="bi bi-geo-alt"></i> Walking routes</span>
                    <span><i class="bi bi-clock"></i> Morning tours</span>
                </div>
                <a class="hp-ecard__link" href="/events/history">View Details &gt;</a>
            </div>
        </article>
        <article class="hp-ecard">
            <div class="hp-ecard__media">
                <img src="https://images.unsplash.com/photo-1519682337058-a94d519337bc?auto=format&fit=crop&w=800&q=80" alt="Storytelling" loading="lazy" width="600" height="400">
                <span class="hp-ecard__tag hp-ecard__tag--stories">Storytelling</span>
            </div>
            <div class="hp-ecard__body">
                <h3>Live Storytelling</h3>
                <p>Evening tales in atmospheric settings: myths, local legends, and voices that captivate.</p>
                <div class="hp-ecard__meta">
                    <span><i class="bi bi-geo-alt"></i> Theatres &amp; outdoor</span>
                    <span><i class="bi bi-clock"></i> Evenings</span>
                </div>
                <a class="hp-ecard__link" href="/events/stories">View Details &gt;</a>
            </div>
        </article>
    </div>
</section>

<!-- Schedule matrix -->
<section class="hp-schedule" id="schedule">
    <div class="hp-schedule__head">
        <h2>Full <em>Schedule</em></h2>
        <p class="hp-schedule__sub">A quick overview of festival hours by day. Times are indicative; see each event page for full line-ups.</p>
    </div>
    <div class="hp-matrix-wrap">
        <div class="hp-matrix" role="grid" aria-label="Festival schedule overview">
            <div class="hp-matrix__corner"></div>
            <div class="hp-matrix__day">Thu 23</div>
            <div class="hp-matrix__day">Fri 24</div>
            <div class="hp-matrix__day">Sat 25</div>
            <div class="hp-matrix__day">Sun 26</div>

            <div class="hp-matrix__row-label hp-matrix__row-label--jazz">Jazz</div>
            <div class="hp-matrix__cell"><div class="hp-slot hp-slot--jazz">18:00 &ndash; 20:00</div></div>
            <div class="hp-matrix__cell"><div class="hp-slot hp-slot--jazz">18:00 &ndash; 21:00</div></div>
            <div class="hp-matrix__cell"><div class="hp-slot hp-slot--jazz">17:00 &ndash; 20:00</div></div>
            <div class="hp-matrix__cell"><div class="hp-slot hp-slot--jazz">15:00 &ndash; 18:00</div></div>

            <div class="hp-matrix__row-label hp-matrix__row-label--yummy">Yummy!</div>
            <div class="hp-matrix__cell"><div class="hp-slot hp-slot--yummy">12:00 &ndash; 22:00</div></div>
            <div class="hp-matrix__cell"><div class="hp-slot hp-slot--yummy">12:00 &ndash; 22:00</div></div>
            <div class="hp-matrix__cell"><div class="hp-slot hp-slot--yummy">12:00 &ndash; 22:00</div></div>
            <div class="hp-matrix__cell"><div class="hp-slot hp-slot--yummy">12:00 &ndash; 20:00</div></div>

            <div class="hp-matrix__row-label hp-matrix__row-label--history">History</div>
            <div class="hp-matrix__cell"><div class="hp-slot hp-slot--history">10:00 &ndash; 12:00</div></div>
            <div class="hp-matrix__cell"><div class="hp-slot hp-slot--history">10:30 &ndash; 12:30</div></div>
            <div class="hp-matrix__cell"><div class="hp-slot hp-slot--history">10:00 &ndash; 13:00</div></div>
            <div class="hp-matrix__cell"><div class="hp-slot hp-slot--history">10:00 &ndash; 12:00</div></div>

            <div class="hp-matrix__row-label hp-matrix__row-label--stories">Storytelling</div>
            <div class="hp-matrix__cell"><div class="hp-slot hp-slot--stories">19:00 &ndash; 21:00</div></div>
            <div class="hp-matrix__cell"><div class="hp-slot hp-slot--stories">19:30 &ndash; 21:30</div></div>
            <div class="hp-matrix__cell"><div class="hp-slot hp-slot--stories">20:00 &ndash; 22:00</div></div>
            <div class="hp-matrix__cell"><div class="hp-slot hp-slot--stories">18:00 &ndash; 20:00</div></div>
        </div>
    </div>
</section>

<!-- Locations -->
<section class="hp-locations" aria-label="Event locations">
    <h2 class="hp-locations__title">Overview of event locations</h2>
    <div class="hp-loc-grid">
        <ul class="hp-venue-list">
            <li class="hp-venue">
                <span class="hp-venue__dot" style="background:var(--hp-jazz);"></span>
                <div>
                    <div class="hp-venue__name">De Patronaat</div>
                    <div class="hp-venue__addr">Zijlsingel 2, 2013 DN Haarlem</div>
                </div>
            </li>
            <li class="hp-venue">
                <span class="hp-venue__dot" style="background:var(--hp-yummy);"></span>
                <div>
                    <div class="hp-venue__name">Grote Markt</div>
                    <div class="hp-venue__addr">Grote Markt, Haarlem</div>
                </div>
            </li>
            <li class="hp-venue">
                <span class="hp-venue__dot" style="background:var(--hp-history);"></span>
                <div>
                    <div class="hp-venue__name">Frans Hals Museum</div>
                    <div class="hp-venue__addr">Groot Heiligland 62, 2011 ES Haarlem</div>
                </div>
            </li>
            <li class="hp-venue">
                <span class="hp-venue__dot" style="background:var(--hp-stories);"></span>
                <div>
                    <div class="hp-venue__name">Windmill De Adriaan</div>
                    <div class="hp-venue__addr">Papentorenvest 1, 2011 AV Haarlem</div>
                </div>
            </li>
        </ul>
        <div class="hp-loc-map" role="img" aria-label="Stylised map of the Haarlem area">
            <div class="hp-loc-map__overlay"></div>
            <div class="hp-loc-legend">
                <div><span style="background:var(--hp-jazz);"></span> Jazz</div>
                <div><span style="background:var(--hp-yummy);"></span> Yummy!</div>
                <div><span style="background:var(--hp-history);"></span> History</div>
                <div><span style="background:var(--hp-stories);"></span> Storytelling</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="hp-cta-bar" aria-label="Tickets">
    <p>Ready to build your festival weekend? Grab your tickets and add events to My Program.</p>
    <a href="/tickets" class="hp-btn-gold">Purchase Tickets</a>
</section>

</section>

<?php require __DIR__ . '/../partials/footer.php'; ?>
