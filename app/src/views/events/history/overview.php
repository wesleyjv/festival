<?php 
$extraStylesheets = ['/css/history/history-overview.css'];
require __DIR__ . '/../../partials/header.php'; 
?>

<div class="history-hero sans-text">
    <div class="container hero-inner">
        <div class="row">
            <div class="col-md-9 col-lg-7">
                <h1 class="history-title mb-2"><?= $historyContent['hero_title'] ?? 'Haarlem<br>History' ?></h1>
                <p class="history-sub mb-0"><?= htmlspecialchars($historyContent['hero_subtitle'] ?? 'Walking Tour & Highlights') ?></p>
                <div class="hero-info-box">
                    <p class="tour-desc mb-2"><?= htmlspecialchars($historyContent['hero_description'] ?? 'Guided tours from Thursday till Sunday') ?></p>
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
                    <h2 class="editorial-heading"><?= htmlspecialchars($historyContent['editorial_1_title'] ?? 'Haarlems History') ?></h2>
                    <div class="editorial-body"><?= $historyContent['editorial_1_text'] ?? 'Haarlem is one of the oldest cities in the Netherlands, with a recorded history dating back over 800 years.' ?></div>
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
                    <h2 class="editorial-heading"><?= htmlspecialchars($historyContent['editorial_2_title'] ?? 'Trade, Canals, and Daily Life') ?></h2>
                    <div class="editorial-body"><?= $historyContent['editorial_2_text'] ?? 'Haarlem\'s growth was shaped not only by major historical events, but also by everyday life along its canals and streets.' ?></div>
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