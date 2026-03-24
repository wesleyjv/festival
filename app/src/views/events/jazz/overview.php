<?php
/** @var \App\Models\JazzEvent[] $jazzArtists */
/** @var string $dayFilter */
/** @var array<string,string> $jazzContent */
$extraStylesheets = ['/css/jazz/jazz-public.css'];
require __DIR__ . '/../../partials/header.php';

$days = ['all' => 'All', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday'];
$heroBg = $jazzContent['hero_background_image'] ?? '';
?>

<main>


<section class="jazz-hero"<?= $heroBg !== '' ? ' style="background-image:url(\'' . htmlspecialchars($heroBg, ENT_QUOTES) . '\');background-size:cover;background-position:center;"' : '' ?>>
    <?php if ($heroBg === ''): ?>
    <div class="jazz-hero__placeholder"><i class="bi bi-music-note-beamed"></i></div>
    <?php endif; ?>
    <div class="jazz-hero__overlay"></div>
    <div class="jazz-hero__content">
        <h1><?= nl2br(htmlspecialchars($jazzContent['hero_title'] ?? '')) ?></h1>
    </div>
</section>

<section class="jazz-intro">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-md-6">
                <h2 class="fw-bold mb-1" style="font-size:1.65rem;"><?= strip_tags($jazzContent['intro_heading'] ?? '') ?></h2>
                <h3 class="fw-normal mb-3" style="font-size:1.25rem; color:#444;"><?= strip_tags($jazzContent['intro_sub'] ?? '') ?></h3>
                <div style="color:#555; line-height:1.75; font-size:0.93rem;">
                    <?= $jazzContent['intro_text'] ?? '' ?>
                </div>
            </div>
            <div class="col-md-6">
                <?php $imgVal = $jazzContent['intro_image'] ?? ''; ?>
                <?php if ($imgVal): ?>
                    <img src="<?= htmlspecialchars($imgVal) ?>" class="img-fluid rounded shadow" alt="Jazz Intro" style="width:100%; min-height:260px; object-fit:cover;">
                <?php else: ?>
                    <div class="jazz-intro__img-placeholder">
                        <i class="bi bi-image"></i>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>


<section class="jazz-artists">
    <div class="container">
        <h2 class="jazz-artists__heading text-center"><?= htmlspecialchars($jazzContent['artists_heading'] ?? '') ?></h2>

        <nav class="day-filters" aria-label="Filter by day">
            <?php foreach ($days as $key => $label): ?>
                <button class="day-filter-btn <?= ($dayFilter === $key) ? 'active' : '' ?>"
                        data-filter="<?= $key ?>">
                    <?= htmlspecialchars($label) ?>
                </button>
            <?php endforeach; ?>
        </nav>

        <p class="jazz-artists__sub text-center"><?= htmlspecialchars($jazzContent['artists_sub'] ?? '') ?></p>

        <?php if (empty($jazzArtists)): ?>
            <p class="text-muted">No artists scheduled for this day yet.</p>
        <?php else: ?>
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3" id="artist-grid">
                <?php foreach ($jazzArtists as $artist): ?>
                    <?php $artistDay = $artist->startTime ? strtolower(date('l', strtotime($artist->startTime))) : ''; ?>
                    <div class="col artist-col" data-day="<?= htmlspecialchars($artistDay) ?>">
                        <?php require __DIR__ . '/../../partials/artist-card.php'; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>


<section class="jazz-locations">
    <div class="container">
        <h2 class="jazz-locations__heading"><?= htmlspecialchars($jazzContent['locations_heading'] ?? '') ?></h2>
        <p class="jazz-locations__sub"><?= htmlspecialchars($jazzContent['locations_sub'] ?? '') ?></p>
        <div class="row">
            <div class="col-md-4 location-list-cms">
                <?= $jazzContent['locations_text'] ?? '' ?>
            </div>
            <div class="col-md-8">
                <?php $mapVal = $jazzContent['locations_image'] ?? ''; ?>
                <?php if ($mapVal): ?>
                    <img src="<?= htmlspecialchars($mapVal) ?>" class="img-fluid rounded shadow" alt="Festival Map" style="width:100%; object-fit:cover; margin-top:2rem;">
                <?php else: ?>
                    <div class="map-placeholder">
                        <i class="bi bi-map"></i>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

</main>

<script>
(function () {
    const FADE_MS = 220;

    const btns  = document.querySelectorAll('.day-filter-btn');
    const grid  = document.getElementById('artist-grid');
    const cols  = grid ? [...grid.querySelectorAll('.artist-col')] : [];

    function filterTo(day) {
        // fade everything out
        cols.forEach(col => {
            col.style.transition = `opacity ${FADE_MS}ms ease, transform ${FADE_MS}ms ease`;
            col.style.opacity    = '0';
            col.style.transform  = 'translateY(10px)';
        });

        setTimeout(() => {
            //  hide/show, then fade visible ones back in
            cols.forEach(col => {
                const match = day === 'all' || col.dataset.day === day;
                col.style.display = match ? '' : 'none';
            });

            // Allow one paint cycle before fading in
            requestAnimationFrame(() => requestAnimationFrame(() => {
                cols.forEach(col => {
                    if (col.style.display !== 'none') {
                        col.style.opacity   = '1';
                        col.style.transform = 'translateY(0)';
                    }
                });
            }));
        }, FADE_MS);
    }

    btns.forEach(btn => {
        btn.addEventListener('click', function () {
            btns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const filter = this.dataset.filter;
            const url = filter === 'all' ? '/events/jazz' : '/events/jazz?day=' + filter;
            history.replaceState(null, '', url);

            filterTo(filter);
        });
    });

    // Apply the initial filter from the server-rendered active state on load
    const activeBtn = document.querySelector('.day-filter-btn.active');
    if (activeBtn && activeBtn.dataset.filter !== 'all') {
        // Instant hide on first load (no animation needed)
        cols.forEach(col => {
            const match = col.dataset.day === activeBtn.dataset.filter;
            if (!match) col.style.display = 'none';
        });
    }
}());
</script>


<?php require __DIR__ . '/../../partials/footer.php'; ?>
