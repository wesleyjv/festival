<?php
/** @var \App\Models\JazzEvent[] $jazzArtists */
/** @var \App\Models\JazzEvent[] $jazzSchedule */
/** @var array<int,int> $jazzTicketIds */
/** @var string $dayFilter */
/** @var array<string,string> $jazzContent */
$jazzSchedule = $jazzSchedule ?? [];
$jazzTicketIds = $jazzTicketIds ?? [];
$bodyClass = 'page-jazz';
$extraStylesheets = [
    'https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap',
    '/css/jazz/jazz-public.css?v=20260324',
];
require __DIR__ . '/../../partials/header.php';

$dayFilter = $dayFilter ?? 'thursday';
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

<section class="jazz-schedule" aria-labelledby="jazz-schedule-heading">
    <div class="container">
        <h2 id="jazz-schedule-heading" class="jazz-schedule__heading text-center">Schedule</h2>
        <p class="jazz-schedule__sub text-center">Choose a day to see that day&rsquo;s programme only, or search by artist. Expand a row for more info.</p>

        <div class="jazz-schedule__filters-card">
            <div class="jazz-schedule__filters">
                <label class="jazz-schedule__filter">
                    <span class="jazz-schedule__filter-label">Artist</span>
                    <input type="search" id="schedule-artist-filter" class="form-control jazz-schedule__search" placeholder="Search by name" autocomplete="off" aria-label="Filter schedule by artist name">
                </label>
                <label class="jazz-schedule__filter">
                    <span class="jazz-schedule__filter-label">Day</span>
                    <select id="schedule-day-filter" class="form-select jazz-schedule__select" aria-label="Show schedule for one day">
                        <?php foreach ($days as $key => $label): ?>
                            <option value="<?= htmlspecialchars($key) ?>" <?= ($dayFilter === $key) ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>
        </div>

        <?php if (empty($jazzSchedule)): ?>
            <p class="text-muted text-center mb-0">No performances match the current filters.</p>
        <?php else: ?>
            <?php
            $scheduleByDate = [];
            foreach ($jazzSchedule as $ev) {
                if (!$ev->startTime) {
                    continue;
                }
                $dk = date('Y-m-d', strtotime($ev->startTime));
                $scheduleByDate[$dk][] = $ev;
            }
            ksort($scheduleByDate);
            ?>
            <div class="jazz-schedule__list" id="jazz-schedule-list">
                <?php foreach ($scheduleByDate as $dateKey => $dayEvents): ?>
                    <?php
                    $first = $dayEvents[0];
                    $dayName = $first->startTime ? date('l', strtotime($first->startTime)) : '';
                    $dayDate = $first->startTime ? date('j F Y', strtotime($first->startTime)) : '';
                    ?>
                    <div class="jazz-schedule__day-block">
                        <div class="jazz-schedule__table-wrap">
                            <table class="jazz-schedule__table">
                                <caption class="jazz-schedule__caption">
                                    <span class="jazz-schedule__caption-day"><?= htmlspecialchars($dayName) ?></span>
                                    <span class="jazz-schedule__caption-date"><?= htmlspecialchars($dayDate) ?></span>
                                </caption>
                                <colgroup>
                                    <col class="jazz-schedule__col-time" span="1">
                                    <col class="jazz-schedule__col-show" span="1">
                                    <col class="jazz-schedule__col-price" span="1">
                                    <col class="jazz-schedule__col-cart" span="1">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th scope="col" class="jazz-schedule__th jazz-schedule__th-time">Time</th>
                                        <th scope="col" class="jazz-schedule__th jazz-schedule__th-show">Performance</th>
                                        <th scope="col" class="jazz-schedule__th jazz-schedule__th-price">Price</th>
                                        <th scope="col" class="jazz-schedule__th jazz-schedule__th-cart">Tickets</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dayEvents as $ev): ?>
                                        <?php
                                        $ticketId = $jazzTicketIds[$ev->eventId] ?? null;
                                        $dayKey = $ev->startTime ? strtolower(date('l', strtotime($ev->startTime))) : '';
                                        $artistLower = strtolower($ev->artist);
                                        $timeFrom = $ev->startTime ? date('H:i', strtotime($ev->startTime)) : '';
                                        $timeTo = $ev->endTime ? date('H:i', strtotime($ev->endTime)) : '';
                                        $maxQty = 99;
                                        if ($ev->seats !== null && $ev->seats > 0) {
                                            $maxQty = min(99, $ev->seats);
                                        }
                                        $descPlain = trim(strip_tags($ev->description ?? ''));
                                        $descShort = strlen($descPlain) > 220 ? substr($descPlain, 0, 217) . '…' : $descPlain;
                                        $p = $ev->price !== null ? (float) $ev->price : null;
                                        $priceLabel = $p === null ? '—' : ($p <= 0 ? 'Free' : '€' . number_format($p, fmod($p, 1.0) < 0.005 ? 0 : 2));
                                        ?>
                                        <tr class="jazz-schedule__main-row jazz-schedule__row"
                                            data-day="<?= htmlspecialchars($dayKey) ?>"
                                            data-artist="<?= htmlspecialchars($artistLower, ENT_QUOTES) ?>">
                                            <td class="jazz-schedule__cell jazz-schedule__cell-time">
                                                <div class="jazz-schedule__time-inner">
                                                    <button type="button" class="jazz-schedule__toggle" aria-expanded="false" aria-label="Show details for <?= htmlspecialchars($ev->artist, ENT_QUOTES) ?>">
                                                        <i class="bi bi-chevron-down jazz-schedule__chev" aria-hidden="true"></i>
                                                    </button>
                                                    <span class="jazz-schedule__time-range"><?= htmlspecialchars($timeFrom) ?><?= $timeTo ? '–' . htmlspecialchars($timeTo) : '' ?></span>
                                                </div>
                                            </td>
                                            <td class="jazz-schedule__cell jazz-schedule__cell-show">
                                                <div class="jazz-schedule__show-stack">
                                                    <span class="jazz-schedule__artist"><?= htmlspecialchars($ev->artist) ?></span>
                                                    <?php if ($ev->location): ?>
                                                        <span class="jazz-schedule__loc"><?= htmlspecialchars($ev->location) ?></span>
                                                    <?php endif; ?>
                                                    <?php if ($ev->style !== ''): ?>
                                                        <span class="jazz-schedule__style"><?= htmlspecialchars($ev->style) ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="jazz-schedule__cell jazz-schedule__cell-price"><?= htmlspecialchars($priceLabel) ?></td>
                                            <td class="jazz-schedule__cell jazz-schedule__cell-cart">
                                                <?php if ($ticketId !== null): ?>
                                                    <form action="/cart/add" method="post" class="jazz-schedule__cart-form">
                                                        <?= \App\Security\Csrf::field() ?>
                                                        <input type="hidden" name="ticket_id" value="<?= (int) $ticketId ?>">
                                                        <label class="jazz-schedule__qty-label"><span class="visually-hidden">Quantity</span>
                                                            <input type="number" name="quantity" class="form-control form-control-sm jazz-schedule__qty" value="1" min="1" max="<?= (int) $maxQty ?>" aria-label="Ticket quantity for <?= htmlspecialchars($ev->artist, ENT_QUOTES) ?>">
                                                        </label>
                                                        <button type="submit" class="btn btn-sm btn-dark jazz-schedule__add">Add</button>
                                                    </form>
                                                <?php else: ?>
                                                    <span class="jazz-schedule__na text-muted small">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr class="jazz-schedule__detail-row" hidden>
                                            <td class="jazz-schedule__cell jazz-schedule__cell-detail" colspan="4">
                                                <div class="jazz-schedule__expand">
                                                    <?php if ($descShort !== ''): ?>
                                                        <p class="jazz-schedule__desc"><?= nl2br(htmlspecialchars($descShort)) ?></p>
                                                    <?php endif; ?>
                                                    <a href="/events/jazz/<?= (int) $ev->eventId ?>" class="jazz-schedule__detail-link">Full artist page <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="jazz-artists">
    <div class="container">
        <h2 class="jazz-artists__heading text-center"><?= htmlspecialchars($jazzContent['artists_heading'] ?? '') ?></h2>

        <nav class="day-filters" aria-label="Filter by day">
            <?php foreach ($days as $key => $label): ?>
                <button type="button"
                        class="day-filter-btn <?= ($dayFilter === $key) ? 'active' : '' ?>"
                        data-filter="<?= htmlspecialchars($key) ?>">
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
    const btns = document.querySelectorAll('.day-filter-btn');
    const grid = document.getElementById('artist-grid');
    const cols = grid ? [...grid.querySelectorAll('.artist-col')] : [];
    const scheduleRows = [...document.querySelectorAll('.jazz-schedule__main-row')];
    const artistInput = document.getElementById('schedule-artist-filter');
    const daySelect = document.getElementById('schedule-day-filter');

    function setDayUrl(day) {
        const url = day === 'all' ? '/events/jazz' : '/events/jazz?day=' + encodeURIComponent(day);
        history.replaceState(null, '', url);
    }

    function syncDayControls(day) {
        if (daySelect) daySelect.value = day;
        btns.forEach(b => b.classList.toggle('active', b.dataset.filter === day));
    }

    function refreshSchedule() {
        const day = daySelect ? daySelect.value : 'thursday';
        const artistQ = (artistInput && artistInput.value ? artistInput.value : '').trim().toLowerCase();

        scheduleRows.forEach(row => {
            const dayOk = day === 'all' || row.dataset.day === day;
            const artistOk = !artistQ || (row.dataset.artist || '').includes(artistQ);
            const show = dayOk && artistOk;
            row.style.display = show ? '' : 'none';
            const detail = row.nextElementSibling;
            if (detail && detail.classList.contains('jazz-schedule__detail-row')) {
                const toggle = row.querySelector('.jazz-schedule__toggle');
                if (!show) {
                    detail.setAttribute('hidden', '');
                    detail.style.display = 'none';
                    if (toggle) {
                        toggle.setAttribute('aria-expanded', 'false');
                        toggle.classList.remove('is-open');
                    }
                } else {
                    detail.style.removeProperty('display');
                    detail.setAttribute('hidden', '');
                    if (toggle) {
                        toggle.setAttribute('aria-expanded', 'false');
                        toggle.classList.remove('is-open');
                    }
                }
            }
        });

        document.querySelectorAll('.jazz-schedule__day-block').forEach(block => {
            const mains = block.querySelectorAll('.jazz-schedule__main-row');
            const anyVisible = [...mains].some(r => r.style.display !== 'none');
            block.style.display = anyVisible ? '' : 'none';
        });
    }

    function filterArtistGrid(day) {
        cols.forEach(col => {
            const match = day === 'all' || col.dataset.day === day;
            col.style.display = match ? '' : 'none';
        });
    }

    function applyDay(day) {
        syncDayControls(day);
        setDayUrl(day);
        filterArtistGrid(day);
        refreshSchedule();
    }

    if (daySelect) {
        daySelect.addEventListener('change', function () {
            applyDay(this.value);
        });
    }

    btns.forEach(btn => {
        btn.addEventListener('click', function () {
            applyDay(this.dataset.filter);
        });
    });

    if (artistInput) {
        artistInput.addEventListener('input', refreshSchedule);
    }

    document.querySelectorAll('.jazz-schedule__toggle').forEach(btn => {
        btn.addEventListener('click', function () {
            const mainRow = this.closest('tr.jazz-schedule__main-row');
            if (!mainRow) return;
            const detailRow = mainRow.nextElementSibling;
            if (!detailRow || !detailRow.classList.contains('jazz-schedule__detail-row')) return;
            const willOpen = detailRow.hasAttribute('hidden');
            if (willOpen) {
                detailRow.removeAttribute('hidden');
                detailRow.style.removeProperty('display');
            } else {
                detailRow.setAttribute('hidden', '');
            }
            this.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
            this.classList.toggle('is-open', willOpen);
        });
    });

    applyDay(daySelect ? daySelect.value : 'thursday');
}());
</script>


<?php require __DIR__ . '/../../partials/footer.php'; ?>
