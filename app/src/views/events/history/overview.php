<?php require __DIR__ . '/../../partials/header.php'; ?>

<style>
/* Font imports for a premium, historical feel */
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,800;1,600&family=Inter:wght@400;500;600&display=swap');

/* Typography Overrides */
.history-title, .milestones-title, .serif-heading { 
    font-family: 'Playfair Display', serif; 
}
.sans-text {
    font-family: 'Inter', sans-serif;
}

/* Hero Section */
.history-hero {
    background: linear-gradient(to right, rgba(20, 15, 10, 0.55) 0%, rgba(20, 15, 10, 0.3) 100%), 
                url('/assets/history/images/hero.png');
    background-size: cover;
    background-position: center;
    color: #fff;
    padding: 140px 0 80px;
    position: relative;
    display: flex;
    align-items: flex-end;
}
.history-title { 
    font-family: 'Inter', sans-serif;
    font-size: 7rem; 
    font-weight: 900; 
    line-height: 0.9; 
    letter-spacing: -2px; 
    color: #ffffff;
    text-transform: uppercase;
}
.history-sub { 
    font-size: 1.4rem; 
    font-weight: 800; 
    color: #ffffff;
    text-transform: uppercase;
    letter-spacing: 1px;
}
.hero-info-box {
    display: inline-block;
    background: rgba(30, 25, 20, 0.75);
    backdrop-filter: blur(6px);
    border-radius: 12px;
    padding: 18px 24px;
    margin-top: 20px;
}
.hero-info-box .tour-desc {
    font-size: 1.05rem;
    color: #fff;
    margin-bottom: 10px;
}
.hero-info-box .badge-row {
    display: flex;
    gap: 20px;
    align-items: center;
    color: #fff;
    font-size: 0.95rem;
}
.hero-info-box .badge-row i {
    color: #e0a42a;
    margin-right: 4px;
}
.hero-learn-more {
    display: inline-block;
    margin-top: 24px;
    font-size: 1.1rem;
    font-weight: 800;
    color: #e0a42a;
    text-transform: uppercase;
    letter-spacing: 1px;
    text-decoration: none;
}
.hero-learn-more:hover {
    color: #f3b93a;
}
.accent-line {
    width: 60px;
    height: 4px;
    background: #e0a42a;
    margin: 24px 0;
    border-radius: 2px;
}

/* =============================================
   EDITORIAL ALTERNATING SECTIONS (screenshot)
   ============================================= */
.editorial-section {
    padding: 60px 0 20px;
}

/* Image wrapper with decorative corner brackets */
.editorial-img-wrap {
    position: relative;
    padding: 18px;
}
.editorial-img-wrap.editorial-img-left {
    padding-right: 32px;
}
.editorial-img-wrap.editorial-img-right {
    padding-left: 32px;
}
.editorial-img {
    width: 100%;
    height: 420px;
    object-fit: cover;
    display: block;
    border: 2px solid #c9a84c;
}

/* Gold corner bracket decorations */
.editorial-corner {
    position: absolute;
    width: 22px;
    height: 22px;
    background: #c9a84c;
    border-radius: 2px;
    z-index: 2;
}
.editorial-corner-tl {
    top: 6px;
    left: 6px;
}
.editorial-corner-br {
    bottom: 6px;
    right: 6px;
}

/* Small expand icon top-left of image area */
.editorial-img-wrap::before {
    content: '⤢';
    position: absolute;
    top: -4px;
    left: -4px;
    font-size: 1.1rem;
    color: #8a7a60;
    z-index: 3;
    line-height: 1;
}

/* Decorative line + lock icon top-right */
.editorial-img-wrap::after {
    content: '🔒';
    position: absolute;
    top: -6px;
    right: 20px;
    font-size: 0.85rem;
    color: #8a7a60;
    z-index: 3;
}

/* Text area */
.editorial-text {
    padding-top: 16px;
    padding-bottom: 16px;
}

.editorial-heading {
    font-family: 'Playfair Display', serif;
    font-size: 2.4rem;
    font-weight: 800;
    color: #1a1209;
    margin-bottom: 20px;
    line-height: 1.15;
}

.editorial-body {
    font-family: 'Inter', sans-serif;
    font-size: 0.97rem;
    line-height: 1.75;
    color: #4a3f32;
    margin-bottom: 16px;
}

/* Diamond divider between sections */
.editorial-divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 16px 0 8px;
    padding: 0 0;
}
.editorial-divider-line {
    flex: 1;
    height: 1px;
    background: #c9a84c;
    opacity: 0.5;
}
.editorial-divider-diamond {
    color: #c9a84c;
    font-size: 1.1rem;
    line-height: 1;
}

/* =============================================
   MILESTONES SECTION
   ============================================= */
.milestones-header { 
    text-align: center; 
    margin: 80px 0 48px; 
}
.milestones-title { 
    font-size: 2.8rem; 
    color: #a86f19; 
}
.btn-milestone { 
    display: flex; 
    align-items: center; 
    justify-content: space-between; 
    padding: 20px 24px; 
    margin-bottom: 12px; 
    border-radius: 12px; 
    background: #2b2016; 
    color: #f3d58a; 
    border: 1px solid rgba(243, 213, 138, 0.1); 
    transition: all 0.3s ease;
}
.btn-milestone:hover {
    transform: translateX(8px);
    background: #3d2e20;
    border-color: rgba(243, 213, 138, 0.3);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.btn-milestone .label { 
    display: flex; 
    gap: 16px; 
    align-items: center; 
    font-size: 1.1rem;
}
.btn-milestone .badge {
    background: #e0a42a !important;
    color: #1b120b;
    font-size: 1rem;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Map Frame */
.map-sticky-container {
    position: sticky;
    top: 24px;
}
.map-frame { 
    border-radius: 16px; 
    box-shadow: 0 20px 50px rgba(0,0,0,0.1); 
    overflow: hidden; 
    border: 4px solid #f3e2c8; 
    background: #f3e2c8;
}

/* Practical Info */
.practical-dark { 
    background: #15110d; 
    color: #f3d58a; 
    padding: 80px 0; 
    margin-top: 40px;
}
.practical-card { 
    background: rgba(255, 255, 255, 0.04); 
    border: 1px solid rgba(243, 213, 138, 0.08); 
    border-radius: 16px; 
    padding: 32px 24px; 
    height: 100%;
    transition: transform 0.3s ease, background 0.3s ease;
}
.practical-card:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.07);
}
.practical-card h6 {
    font-size: 1.1rem;
    color: #e0a42a;
    margin-bottom: 16px;
}
.practical-card ul {
    list-style-type: none;
    padding-left: 0;
}
.practical-card ul li {
    padding-left: 24px;
    position: relative;
    margin-bottom: 8px;
}
.practical-card ul li::before {
    content: "•";
    position: absolute;
    left: 0;
    color: #e0a42a;
}

/* Starting Point Box */
.starting-point { 
    background: linear-gradient(135deg, #e0a42a 0%, #b88319 100%); 
    color: #1b120b; 
    padding: 40px; 
    border-radius: 16px; 
    text-align: center; 
    box-shadow: 0 10px 30px rgba(224, 164, 42, 0.2);
}
.starting-point h5 {
    font-size: 1.5rem;
    font-weight: 800;
}
.starting-point .btn-dark {
    background: #1b120b;
    border: none;
    padding: 12px 32px;
    border-radius: 8px;
    font-weight: 600;
    transition: background 0.2s ease;
}
.starting-point .btn-dark:hover {
    background: #2b1f14;
}

/* Page background */
body {
    background-color: #faf5ed;
}
</style>

<div class="history-hero sans-text">
    <div class="container hero-inner">
        <div class="row">
            <div class="col-md-9 col-lg-7">
                <h1 class="history-title mb-2">Haarlem<br>History</h1>
                <p class="history-sub mb-0">Walking Tour &amp; Highlights</p>
                <div class="hero-info-box">
                    <p class="tour-desc mb-2">Guided tours from Thursday till Sunday</p>
                    <div class="badge-row">
                        <span><i class="bi bi-clock"></i>2.5 hours</span>
                        <span><i class="bi bi-geo-alt"></i>3.2 km</span>
                        <span><i class="bi bi-people"></i>12 people</span>
                    </div>
                </div>
                <div>
                    <a href="/events/history" class="hero-learn-more">Learn More</a>
                    <a href="/events/history/order" class="hero-learn-more" style="background:#e0a42a; color:#1b120b; margin-left:12px; padding:10px 16px; border-radius:8px;">Order Tickets</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container my-5 py-4 sans-text">

    <!-- Editorial Section 1: Image Left, Text Right -->
    <div class="editorial-section">
        <div class="row g-0 align-items-center">
            <div class="col-lg-6">
                <div class="editorial-img-wrap editorial-img-left">
                    <img src="/img/history-card.jpg" alt="Haarlem canals and windmill" class="editorial-img">
                    <div class="editorial-corner editorial-corner-tl"></div>
                    <div class="editorial-corner editorial-corner-br"></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="editorial-text ps-lg-5 pe-lg-2">
                    <h2 class="editorial-heading">Haarlems History</h2>
                    <p class="editorial-body">Haarlem is one of the oldest cities in the Netherlands, with a recorded history dating back over 800 years. It received city rights in 1245 and quickly developed into an important medieval trading and cultural center. During the Dutch Golden Age, Haarlem flourished as a hub for art, printing, and industry, attracting renowned painters such as Frans Hals and playing a key role in the development of Dutch culture.</p>
                    <p class="editorial-body">The city's historic center still reflects this rich past. Medieval churches like the Grote Kerk dominate the skyline, while narrow streets and hidden hofjes recall daily life in earlier centuries. Haarlem was also one of the first Dutch cities to embrace innovation, from early industrial mills to the country's first museum, Teylers Museum, founded in 1778. Together, these layers of history make Haarlem a living record of Dutch heritage.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Diamond Divider -->
    <div class="editorial-divider">
        <div class="editorial-divider-line"></div>
        <div class="editorial-divider-diamond">◇</div>
        <div class="editorial-divider-line"></div>
    </div>

    <!-- Editorial Section 2: Text Left, Image Right -->
    <div class="editorial-section mb-5">
        <div class="row g-0 align-items-center">
            <div class="col-lg-6">
                <div class="editorial-text pe-lg-5 ps-lg-2">
                    <h2 class="editorial-heading">Trade, Canals, and Daily Life</h2>
                    <p class="editorial-body">Haarlem's growth was shaped not only by major historical events, but also by everyday life along its canals and streets. During the medieval period and the centuries that followed, the city developed into an important center of trade and craftsmanship. Industries such as brewing, textiles, and shipping played a central role in the local economy, attracting workers, merchants, and artisans from across the region.</p>
                    <p class="editorial-body">The canals functioned as vital transport routes, allowing goods to move efficiently through the city while shaping its urban layout. Homes, warehouses, and workshops were built close to the water, forming compact neighborhoods where commerce and daily life were closely connected. Many of these historic structures still line Haarlem's waterways today, offering a visible reminder of how trade, community, and design together defined the city's character.</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="editorial-img-wrap editorial-img-right">
                    <img src="/img/history-church.jpg" alt="Grote Kerk Haarlem" class="editorial-img">
                    <div class="editorial-corner editorial-corner-tl"></div>
                    <div class="editorial-corner editorial-corner-br"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Milestones Section -->
    <div class="milestones-header">
        <h3 class="milestones-title serif-heading fw-bold">Historic Milestones</h3>
        <p class="text-muted fs-5">Every historical location you will visit during this tour</p>
    </div>

    <div class="row g-5 mb-5">
        <div class="col-lg-5">
            <div class="milestone-list pe-lg-3">
                <button class="btn-milestone w-100 text-start">
                    <div class="label"><span class="badge rounded-pill">1</span><strong>Church of St. Bavo</strong></div>
                    <i class="bi bi-arrow-right"></i>
                </button>
                <button class="btn-milestone w-100 text-start"><div class="label"><span class="badge rounded-pill">2</span><strong>Grote Markt</strong></div><i class="bi bi-arrow-right"></i></button>
                <button class="btn-milestone w-100 text-start"><div class="label"><span class="badge rounded-pill">3</span><strong>De Hallen</strong></div><i class="bi bi-arrow-right"></i></button>
                <button class="btn-milestone w-100 text-start"><div class="label"><span class="badge rounded-pill">4</span><strong>Proveniershof</strong></div><i class="bi bi-arrow-right"></i></button>
                <button class="btn-milestone w-100 text-start"><div class="label"><span class="badge rounded-pill">5</span><strong>Jopenkerk</strong></div><i class="bi bi-arrow-right"></i></button>
                <button class="btn-milestone w-100 text-start"><div class="label"><span class="badge rounded-pill">6</span><strong>Waalse Kerk Haarlem</strong></div><i class="bi bi-arrow-right"></i></button>
                <button class="btn-milestone w-100 text-start"><div class="label"><span class="badge rounded-pill">7</span><strong>Molen de Adriaan</strong></div><i class="bi bi-arrow-right"></i></button>
                <button class="btn-milestone w-100 text-start"><div class="label"><span class="badge rounded-pill">8</span><strong>Amsterdamse Poort</strong></div><i class="bi bi-arrow-right"></i></button>
                <button class="btn-milestone w-100 text-start"><div class="label"><span class="badge rounded-pill">9</span><strong>Hof van Bakenes</strong></div><i class="bi bi-arrow-right"></i></button>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="map-sticky-container">
                <div class="map-frame">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d9825.8!2d4.6372!3d52.3808!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c5ef6c26b2b5af%3A0x45fc5b5f4b8b7b0e!2sHaarlem!5e0!3m2!1sen!2snl!4v1680000000000" width="100%" height="700" style="border:0; display:block;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="practical-dark sans-text">
    <div class="container">
        <h3 class="text-center mb-5 serif-heading fw-bold" style="color:#f3d58a; font-size: 2.5rem;">Practical Information</h3>
        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-3">
                <div class="practical-card">
                    <h6><i class="bi bi-bag me-2 fs-5"></i>What to Bring</h6>
                    <ul class="mb-0" style="color:#e8d5b0">
                        <li>Comfortable shoes</li>
                        <li>Weather clothing</li>
                        <li>Camera</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="practical-card">
                    <h6><i class="bi bi-universal-access me-2 fs-5"></i>Accessibility</h6>
                    <ul class="mb-0" style="color:#e8d5b0">
                        <li>Moderate fitness</li>
                        <li>Not wheelchair accessible</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="practical-card">
                    <h6><i class="bi bi-chat-dots me-2 fs-5"></i>Languages</h6>
                    <ul class="mb-0" style="color:#e8d5b0">
                        <li>English (Daily)</li>
                        <li>Chinese (Saturday)</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="practical-card">
                    <h6><i class="bi bi-tag me-2 fs-5"></i>Pricing & Fees</h6>
                    <ul class="mb-0" style="color:#e8d5b0">
                        <li>Regular: €12.50</li>
                        <li>Family: €42.00</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="starting-point">
                    <h5 class="serif-heading mb-3">Starting Point</h5>
                    <p class="mb-1 fw-bold fs-5"><?= htmlspecialchars($startAddr ?? '') ?></p>
                    <p class="mb-4" style="opacity:0.85"><?= htmlspecialchars($startNote ?? '') ?></p>
                    <a class="btn btn-dark" href="https://maps.google.com/?q=<?= urlencode($startAddr ?? '') ?>" target="_blank">
                        <i class="bi bi-geo-alt-fill me-2"></i>Get Directions
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../../partials/footer.php'; ?>