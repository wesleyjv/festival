<?php require __DIR__ . '/../../partials/header.php'; ?>

<?php
/** @var \App\Models\JazzEvent[] $jazzArtists */
/** @var string $dayFilter */
$days = ['all' => 'All', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday'];
?>

<style>
/* ?? Hero ?????????????????????????????????????????????????????? */
.jazz-hero {
    position: relative;
    height: 420px;
    background: #1a1a2e;
    overflow: hidden;
    display: flex;
    align-items: flex-end;
}
.jazz-hero__placeholder {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #1a1a2e 0%, #2d2d5e 50%, #3a2a1a 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255,255,255,0.08);
    font-size: 5rem;
}
.jazz-hero__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.75) 40%, transparent 100%);
}
.jazz-hero__content {
    position: relative;
    z-index: 2;
    padding: 0 2.5rem 2.5rem;
    color: #fff;
}
.jazz-hero__content h1 {
    font-size: clamp(2.4rem, 5vw, 3.6rem);
    font-weight: 800;
    line-height: 1.1;
    margin: 0;
    text-shadow: 0 2px 12px rgba(0,0,0,0.5);
}

/* ?? Intro section ????????????????????????????????????????????? */
.jazz-intro {
    background: #fdf6ee;
    padding: 3.5rem 0;
}
.jazz-intro__img-placeholder {
    background: #e8e0d5;
    border-radius: 12px;
    height: 260px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(0,0,0,0.2);
    font-size: 3.5rem;
}

/* ?? Artist grid section ??????????????????????????????????????? */
.jazz-artists {
    background: #fff;
    padding: 3.5rem 0 4rem;
}
.jazz-artists__heading {
    font-size: 1.55rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}
.jazz-artists__sub {
    color: #888;
    font-size: 0.85rem;
    margin-bottom: 1.5rem;
}

/* Day-filter pills */
.day-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 2rem;
    justify-content: center;
}
.day-filter-btn {
    display: inline-block;
    padding: 0.45rem 1.15rem;
    border-radius: 999px;
    font-size: 0.82rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    border: none;
    cursor: pointer;
    background: #f0f0f0;
    color: #333;
    transition: background 0.2s, color 0.2s;
}
.day-filter-btn.active,
.day-filter-btn:hover {
    background: #1a1a2e;
    color: #fff;
}

/* Artist card */
.artist-card {
    background: #fafafa;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
    height: 100%;
}
.artist-card__cover {
    aspect-ratio: 4 / 3;
    overflow: hidden;
    background: #ddd;
}
.artist-card__cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.35s ease;
}
.artist-card:hover .artist-card__cover img {
    transform: scale(1.04);
}
.artist-card__cover-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #d0cac2;
    color: rgba(255,255,255,0.55);
    font-size: 3rem;
}
.artist-card__body {
    padding: 1rem 1.1rem 1.25rem;
    display: flex;
    flex-direction: column;
    flex: 1;
}
.artist-card__name {
    font-size: 1.15rem;
    font-weight: 800;
    margin: 0 0 0.4rem;
}
.artist-card__day {
    font-size: 0.88rem;
    color: #444;
    margin: 0 0 0.25rem;
}
.artist-card__time {
    font-size: 0.95rem;
    color: #222;
    margin: 0 0 0.6rem;
    display: flex;
    align-items: center;
    gap: 0.35rem;
}
.artist-card__time i {
    color: #888;
}
.artist-card__blurb {
    font-size: 0.84rem;
    color: #666;
    flex: 1;
    margin: 0 0 1rem;
    line-height: 1.5;
}
.artist-card__btn {
    display: block;
    text-align: center;
    background: #1a1a2e;
    color: #fff;
    text-decoration: none;
    padding: 0.6rem 1rem;
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    transition: background 0.2s;
}
.artist-card__btn:hover {
    background: #2e2e5e;
    color: #fff;
}

/* ?? Locations section ????????????????????????????????????????? */
.jazz-locations {
    background: #fdf6ee;
    padding: 3.5rem 0 0;
}
.jazz-locations__heading {
    font-size: 1.55rem;
    font-weight: 700;
    margin-bottom: 0.2rem;
}
.jazz-locations__sub {
    color: #888;
    font-size: 0.88rem;
    margin-bottom: 2rem;
}
.location-list__item {
    margin-bottom: 1.5rem;
}
.location-list__name {
    font-weight: 700;
    font-size: 1rem;
    margin-bottom: 0.1rem;
}
.location-list__addr {
    font-size: 0.83rem;
    color: #666;
    line-height: 1.5;
}
.map-placeholder {
    background: #d8d3cb;
    height: 360px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(0,0,0,0.25);
    font-size: 4rem;
    margin-top: 2rem;
}
</style>

<main>

<!-- ?? Hero ????????????????????????????????????????????????????? -->
<section class="jazz-hero">
    <div class="jazz-hero__placeholder"><i class="bi bi-music-note-beamed"></i></div>
    <div class="jazz-hero__overlay"></div>
    <div class="jazz-hero__content">
        <h1>Jazz<br>performed live<br>Haarlem</h1>
    </div>
</section>

<!-- ?? Intro ???????????????????????????????????????????????????? -->
<section class="jazz-intro">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-md-6">
                <h2 class="fw-bold mb-1" style="font-size:1.65rem;">Jazz in Haarlem</h2>
                <h3 class="fw-normal mb-3" style="font-size:1.25rem; color:#444;">Where the city listens</h3>
                <p style="color:#555; line-height:1.75; font-size:0.93rem;">
                    During the festival, jazz takes over Haarlem's historic streets, from de Grote Markt to small
                    courtyards tucked between canals. Open-air concerts spill into the city during the day, while
                    intimate late-night sessions unfold in clubs, churches, and unexpected corners. For a few days,
                    Haarlem itself becomes the stage; a place where improvisation, movement, and sound are woven
                    directly into the urban fabric.
                </p>
                <p style="color:#555; font-size:0.93rem;">
                    From grand squares to quiet corners, jazz becomes part of the city's pulse.
                </p>
            </div>
            <div class="col-md-6">
                <div class="jazz-intro__img-placeholder">
                    <i class="bi bi-image"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ?? Artist grid ?????????????????????????????????????????????? -->
<section class="jazz-artists">
    <div class="container">
        <h2 class="jazz-artists__heading text-center">Participating Jazz Artists</h2>

        <!-- Day filter -->
        <nav class="day-filters" aria-label="Filter by day">
            <?php foreach ($days as $key => $label): ?>
                <button class="day-filter-btn <?= ($dayFilter === $key) ? 'active' : '' ?>"
                        data-filter="<?= $key ?>">
                    <?= htmlspecialchars($label) ?>
                </button>
            <?php endforeach; ?>
        </nav>

        <p class="jazz-artists__sub text-center">Tickets are available per artist per performance</p>

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

<!-- ?? Festival Locations ??????????????????????????????????????? -->
<section class="jazz-locations">
    <div class="container">
        <h2 class="jazz-locations__heading">Festival Locations</h2>
        <p class="jazz-locations__sub">Two venues and a central access point across the city of Haarlem</p>
        <div class="row">
            <div class="col-md-4">
                <div class="location-list__item">
                    <div class="location-list__name">De Patronaat</div>
                    <div class="location-list__addr">Zijlsingel 2, 2013 DN<br>Haarlem</div>
                </div>
                <div class="location-list__item">
                    <div class="location-list__name">Grote Markt</div>
                    <div class="location-list__addr">Grote Markt<br>Haarlem</div>
                </div>
                <div class="location-list__item">
                    <div class="location-list__name">Station Haarlem</div>
                    <div class="location-list__addr">Stationsplein 1IL<br>2011 LR Haarlem</div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="map-placeholder">
                    <i class="bi bi-map"></i>
                </div>
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
        // Step 1 – fade everything out
        cols.forEach(col => {
            col.style.transition = `opacity ${FADE_MS}ms ease, transform ${FADE_MS}ms ease`;
            col.style.opacity    = '0';
            col.style.transform  = 'translateY(10px)';
        });

        setTimeout(() => {
            // Step 2 – hide/show, then fade visible ones back in
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
