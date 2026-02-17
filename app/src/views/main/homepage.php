<?php require __DIR__ . '/../partials/header.php'; ?>

<style>
    .hero-home {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: #fff;
        border-radius: 16px;
        padding: 60px 24px;
        text-align: center;
        margin-bottom: 40px;
    }
    .hero-home .hero-date {
        color: #ffc107;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-size: 0.85rem;
        margin-bottom: 12px;
    }
    .hero-home h1 {
        font-weight: 800;
        font-size: 2.4rem;
        margin-bottom: 16px;
    }
    .hero-home .hero-sub {
        max-width: 500px;
        margin: 0 auto 28px;
        opacity: 0.75;
        font-size: 1rem;
        line-height: 1.7;
    }
    .event-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        padding: 28px 20px;
        text-align: center;
        height: 100%;
    }
    .event-icon {
        width: 56px;
        height: 56px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 14px;
    }
    .cta-home {
        background: #2c3e50;
        color: #fff;
        border-radius: 16px;
        padding: 48px 24px;
        text-align: center;
        margin-top: 40px;
    }
    .cta-home p { opacity: 0.65; }
</style>

<!-- Hero -->
<div class="hero-home">
    <p class="hero-date">July 23 - 26, 2026</p>
    <h1>Welcome to<br>The Haarlem Festival</h1>
    <p class="hero-sub">
        Discover the best of food, music, stories, and history in the heart of Haarlem.
    </p>
    <a href="#events" class="btn btn-warning px-4 fw-semibold me-2">
        <i class="bi bi-calendar-event me-1"></i>Explore Events
    </a>
    <a href="/register" class="btn btn-outline-light px-4">
        <i class="bi bi-person-plus me-1"></i>Get Tickets
    </a>
</div>

<!-- Events -->
<div id="events">
    <h2 class="fw-bold text-center mb-2">Our Events</h2>
    <p class="text-muted text-center mb-4">Four unique experiences — something for everyone.</p>
    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="event-card">
                <div class="event-icon" style="background: rgba(255,193,7,0.1); color: #ffc107;">
                    <i class="bi bi-egg-fried"></i>
                </div>
                <h5 class="fw-bold">Yummy</h5>
                <p class="text-muted small">Taste Haarlem's finest restaurants and street food.</p>
                <a href="/events/yummy" class="btn btn-outline-warning btn-sm">Discover</a>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="event-card">
                <div class="event-icon" style="background: rgba(13,202,240,0.1); color: #0dcaf0;">
                    <i class="bi bi-book"></i>
                </div>
                <h5 class="fw-bold">Stories</h5>
                <p class="text-muted small">Captivating tales told throughout the historic city.</p>
                <a href="/events/stories" class="btn btn-outline-info btn-sm">Discover</a>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="event-card">
                <div class="event-icon" style="background: rgba(13,110,253,0.1); color: #0d6efd;">
                    <i class="bi bi-vinyl"></i>
                </div>
                <h5 class="fw-bold">Jazz</h5>
                <p class="text-muted small">World-class jazz at stunning venues across Haarlem.</p>
                <a href="/events/jazz" class="btn btn-outline-primary btn-sm">Discover</a>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="event-card">
                <div class="event-icon" style="background: rgba(220,53,69,0.1); color: #dc3545;">
                    <i class="bi bi-bank"></i>
                </div>
                <h5 class="fw-bold">History</h5>
                <p class="text-muted small">Walk through centuries of history with expert guides.</p>
                <a href="/events/history" class="btn btn-outline-danger btn-sm">Discover</a>
            </div>
        </div>
    </div>
</div>

<!-- CTA -->
<div class="cta-home">
    <h3 class="fw-bold mb-3">Ready to experience Haarlem?</h3>
    <p class="mb-4">Secure your spot before events sell out.</p>
    <a href="/register" class="btn btn-warning px-5 fw-semibold">Get Your Tickets</a>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
