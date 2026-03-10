<?php require __DIR__ . '/../../partials/header.php'; ?>

<?php
/** @var array<string,string> $historyContent */
$heroTitle    = $historyContent['hero_title']       ?? 'Historic Haarlem';
$heroDesc     = $historyContent['hero_description'] ?? "Walk through centuries of rich history with expert guides. Explore Haarlem's most iconic landmarks and hidden gems.";
$heroSub      = $historyContent['hero_subtitle']    ?? 'Walking Tour & Highlights';
$heroDuration = $historyContent['hero_duration']    ?? '2.5 hours';
$heroDist     = $historyContent['hero_distance']    ?? '3.2 km';
$heroMax      = $historyContent['hero_max_people']  ?? '12 people';
$heroDays     = $historyContent['hero_days']        ?? 'Thu – Sunday';

$s1Title = $historyContent['section1_title'] ?? "Haarlem's History";
$s1Body1 = $historyContent['section1_body1'] ?? "Haarlem is one of the oldest cities in the Netherlands, with a recorded history dating back over 800 years. It received city rights in 1245 and quickly developed into an important medieval trading and cultural center. During the Dutch Golden Age, Haarlem flourished as a hub for art, printing, and industry, attracting renowned painters such as Frans Hals.";
$s1Body2 = $historyContent['section1_body2'] ?? "The city's historic center still reflects this rich past. Medieval churches like the Grote Kerk dominate the skyline, while narrow streets and hidden hofjes recall daily life in earlier centuries. Haarlem was also home to the country's first museum, Teylers Museum, founded in 1778.";

$s2Title = $historyContent['section2_title'] ?? 'Trade, Canals, and Daily Life';
$s2Body1 = $historyContent['section2_body1'] ?? "Haarlem's growth was shaped not only by major historical events, but also by everyday life along its canals and streets. Industries such as brewing, textiles, and shipping played a central role in the local economy, attracting workers, merchants, and artisans from across the region.";
$s2Body2 = $historyContent['section2_body2'] ?? "The canals functioned as vital transport routes, allowing goods to move efficiently through the city. Many of these historic structures still line Haarlem's waterways today, offering a visible reminder of how trade, community, and design together defined the city's character.";

$msTitle = $historyContent['milestones_title'] ?? 'Historic Milestones';
$msSub   = $historyContent['milestones_sub']   ?? 'Every historical location you will visit during this tour';

$startAddr = $historyContent['start_address'] ?? 'Grote Markt 23, 2011 RC Haarlem';
$startNote = $historyContent['start_note']    ?? 'Look for a guide holding the Haarlem Festival sign';

$stops = $historyContent['stops'] ?? [
    ['name' => 'Church of St. Bavo',   'type' => 'Starting Point'],
    ['name' => 'Grote Markt',          'type' => 'Central Square'],
    ['name' => 'De Hallen',            'type' => 'Art & Culture'],
    ['name' => 'Proveniershof',        'type' => 'Historic Courtyard'],
    ['name' => 'Jaopenkerk',           'type' => 'Great Location'],
    ['name' => 'Waalse Kerk Haarlem',  'type' => 'Religious Heritage'],
    ['name' => 'Molen de Adriaan',     'type' => 'Historic Windmill'],
    ['name' => 'Amsterdamse Poort',    'type' => 'City Gate'],
    ['name' => 'Hof van Bakenes',      'type' => 'Historic Housing'],
];
?>

<div class="container py-5">

    <!-- Hero -->
    <div class="text-center mb-5">
        <div class="bg-light rounded-4 p-4 p-md-5 shadow-sm">
            <h1 class="fw-bold mb-2">
                <i class="bi bi-bank me-2"></i><?= htmlspecialchars($heroTitle) ?>
            </h1>
            <p class="text-muted mb-3"><?= htmlspecialchars($heroDesc) ?></p>
            <div class="d-flex justify-content-center flex-wrap gap-3 text-muted small">
                <span><i class="bi bi-clock me-1"></i><?= htmlspecialchars($heroDuration) ?></span>
                <span><i class="bi bi-signpost-2 me-1"></i><?= htmlspecialchars($heroDist) ?></span>
                <span><i class="bi bi-people me-1"></i><?= htmlspecialchars($heroMax) ?></span>
                <span><i class="bi bi-calendar3 me-1"></i><?= htmlspecialchars($heroDays) ?></span>
            </div>
        </div>
    </div>

    <!-- Section 1 -->
    <div class="row align-items-center g-4 mb-5">
        <div class="col-md-5">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Molen_De_Adriaan.jpg/800px-Molen_De_Adriaan.jpg"
                 alt="Molen de Adriaan" class="img-fluid rounded-3 shadow-sm">
        </div>
        <div class="col-md-7">
            <h2 class="fw-bold mb-3"><?= htmlspecialchars($s1Title) ?></h2>
            <p class="text-muted"><?= htmlspecialchars($s1Body1) ?></p>
            <p class="text-muted mb-0"><?= htmlspecialchars($s1Body2) ?></p>
        </div>
    </div>

    <!-- Section 2 -->
    <div class="row align-items-center g-4 mb-5 flex-md-row-reverse">
        <div class="col-md-5">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/4e/Grote_Markt_Haarlem.jpg/800px-Grote_Markt_Haarlem.jpg"
                 alt="Grote Markt Haarlem" class="img-fluid rounded-3 shadow-sm">
        </div>
        <div class="col-md-7">
            <h2 class="fw-bold mb-3"><?= htmlspecialchars($s2Title) ?></h2>
            <p class="text-muted"><?= htmlspecialchars($s2Body1) ?></p>
            <p class="text-muted mb-0"><?= htmlspecialchars($s2Body2) ?></p>
        </div>
    </div>

    <!-- Milestones -->
    <div class="mb-5">
        <div class="text-center mb-4">
            <h2 class="fw-bold mb-1"><?= htmlspecialchars($msTitle) ?></h2>
            <p class="text-muted"><?= htmlspecialchars($msSub) ?></p>
            <div class="d-flex justify-content-center gap-2 mt-3">
                <a href="#tours" class="btn btn-dark btn-sm">
                    <i class="bi bi-ticket-perforated me-1"></i>Book Tickets
                </a>
                <a href="#practical" class="btn btn-outline-dark btn-sm">
                    <i class="bi bi-info-circle me-1"></i>Additional Info
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-5">
                <div class="list-group shadow-sm">
                    <?php foreach ($stops as $i => $stop): ?>
                        <a href="#" class="list-group-item list-group-item-action d-flex align-items-center gap-3">
                            <span class="badge bg-dark rounded-pill"><?= $i + 1 ?></span>
                            <div>
                                <div class="fw-semibold small"><?= htmlspecialchars($stop['name']) ?></div>
                                <div class="text-muted" style="font-size:.75rem"><?= htmlspecialchars($stop['type']) ?></div>
                            </div>
                            <i class="bi bi-arrow-right ms-auto text-muted"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-md-7">
                <div class="rounded-3 overflow-hidden shadow-sm" style="height:100%;min-height:400px">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d9825.8!2d4.6372!3d52.3808!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c5ef6c26b2b5af%3A0x45fc5b5f4b8b7b0e!2sHaarlem!5e0!3m2!1sen!2snl!4v1680000000000"
                        width="100%" height="100%" style="min-height:400px;border:0"
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Tour map">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Tours -->
    <div class="mb-5" id="tours">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Available Tours</h3>
                <p class="text-muted mb-0">
                    <?= count($events) ?> guided tour<?= count($events) !== 1 ? 's' : '' ?> available
                </p>
            </div>
        </div>

        <?php if (empty($events)): ?>
            <div class="text-center py-5">
                <i class="bi bi-calendar-x text-muted fs-1"></i>
                <h5 class="text-muted mt-3">No tours available at the moment</h5>
                <p class="text-muted">Check back soon for upcoming history tours.</p>
                <a href="/" class="btn btn-outline-dark mt-2">Back to Home</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($events as $event): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 h-100 shadow-sm rounded-4">
                            <div class="card-body d-flex flex-column p-4">
                                <div class="mb-2">
                                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($event->title) ?></h5>
                                    <span class="badge bg-primary-subtle text-primary">
                                        <i class="bi bi-translate me-1"></i><?= htmlspecialchars($event->language) ?>
                                    </span>
                                </div>
                                <p class="text-muted small flex-grow-1">
                                    <?= htmlspecialchars($event->description) ?>
                                </p>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center p-2 me-2">
                                        <i class="bi bi-person-badge"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Guide</small>
                                        <span class="fw-semibold small"><?= htmlspecialchars($event->guide) ?></span>
                                    </div>
                                </div>
                                <a href="/tickets?event_id=<?= $event->id ?>" class="btn btn-danger w-100">
                                    <i class="bi bi-ticket-perforated me-1"></i> View Tickets
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Practical Info -->
    <div class="mb-5" id="practical">
        <h3 class="fw-bold mb-4">Practical Information</h3>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card border-0 bg-light rounded-4 h-100 p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-bag me-2"></i>What to Bring</h6>
                    <ul class="text-muted small mb-0 ps-3">
                        <li>Comfortable walking shoes</li>
                        <li>Weather-appropriate clothing</li>
                        <li>Camera for capturing highlights</li>
                        <li>Water bottle (just in case)</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 bg-light rounded-4 h-100 p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-universal-access me-2"></i>Accessibility</h6>
                    <ul class="text-muted small mb-0 ps-3">
                        <li>Cobblestone street awareness</li>
                        <li>Not wheelchair-accessible in full</li>
                        <li>Family friendly</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 bg-light rounded-4 h-100 p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-chat-dots me-2"></i>Languages</h6>
                    <ul class="text-muted small mb-0 ps-3">
                        <li>English (daily)</li>
                        <li>Chinese (Saturday)</li>
                        <li>Haarlem dialect (Saturday &amp; Sunday)</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 bg-light rounded-4 h-100 p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-tag me-2"></i>Pricing &amp; Fees</h6>
                    <ul class="text-muted small mb-0 ps-3">
                        <li>Regular price: €12.50</li>
                        <li>Family ticket (max 4 people): €42.00</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Starting Point -->
    <div class="bg-light rounded-4 p-4 text-center shadow-sm mb-2">
        <i class="bi bi-geo-alt-fill fs-3 text-dark mb-2 d-block"></i>
        <h5 class="fw-bold mb-1">Starting Point</h5>
        <p class="text-muted small mb-3">
            <?= htmlspecialchars($startAddr) ?><br>
            <?= htmlspecialchars($startNote) ?>
        </p>
        <a href="https://maps.google.com/?q=<?= urlencode($startAddr) ?>" target="_blank" class="btn btn-dark btn-sm">
            <i class="bi bi-map me-1"></i>Get Directions
        </a>
    </div>

</div>

<?php require __DIR__ . '/../../partials/footer.php'; ?>