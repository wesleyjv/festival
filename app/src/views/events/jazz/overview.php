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
    '/css/jazz/jazz-public.css?v=20260410',
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
        <h2 id="jazz-schedule-heading" class="jazz-schedule__title-bar">
            <span class="jazz-schedule__title-icon" aria-hidden="true"></span>
            <span>Jazz Schedule</span>
        </h2>
        <p class="jazz-schedule__sub text-center">Tap a day to see that programme, or search by artist. Expand a row for more info.</p>

        <?php if (empty($jazzSchedule)): ?>
            <p class="text-muted text-center mb-0">No performances scheduled yet.</p>
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

            $weekdayKeys = ['thursday', 'friday', 'saturday', 'sunday'];
            $scheduleByWeekday = array_fill_keys($weekdayKeys, []);
            foreach ($scheduleByDate as $dateKey => $dayEvents) {
                $wk = strtolower(date('l', strtotime($dateKey)));
                if (isset($scheduleByWeekday[$wk])) {
                    foreach ($dayEvents as $ev) {
                        $scheduleByWeekday[$wk][] = $ev;
                    }
                }
            }
            foreach ($weekdayKeys as $wk) {
                usort($scheduleByWeekday[$wk], static function ($a, $b) {
                    return strtotime($a->startTime) <=> strtotime($b->startTime);
                });
            }

            $scheduleDayTabs = ['thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday'];
            $initialScheduleDay = in_array($dayFilter, $weekdayKeys, true) ? $dayFilter : 'thursday';
            ?>
            <div class="jazz-schedule__shell<?= $dayFilter === 'all' ? ' jazz-schedule__shell--all-days' : '' ?>" id="jazz-schedule-shell">
                <div class="jazz-schedule__filters-card jazz-schedule__filters-card--tabs">
                    <label class="jazz-schedule__filter jazz-schedule__filter--full">
                        <span class="jazz-schedule__filter-label">Artist</span>
                        <input type="search" id="schedule-artist-filter" class="form-control jazz-schedule__search" placeholder="Search by name" autocomplete="off" aria-label="Filter schedule by artist name">
                    </label>
                </div>

                <div class="jazz-sched-tabs" role="tablist" aria-label="Festival day">
                    <?php foreach ($scheduleDayTabs as $wk => $tabLabel): ?>
                        <?php
                        $tabSelected = ($dayFilter !== 'all' && $initialScheduleDay === $wk);
                        ?>
                        <button type="button"
                                class="jazz-sched-tabs__btn"
                                id="jazz-tab-<?= htmlspecialchars($wk) ?>"
                                role="tab"
                                aria-selected="<?= $tabSelected ? 'true' : 'false' ?>"
                                aria-controls="jazz-panel-<?= htmlspecialchars($wk) ?>"
                                tabindex="<?= ($dayFilter === 'all') ? ($wk === 'thursday' ? '0' : '-1') : ($tabSelected ? '0' : '-1') ?>"
                                data-day="<?= htmlspecialchars($wk) ?>">
                            <?= htmlspecialchars(strtoupper($tabLabel)) ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <div class="jazz-sched-panels">
                    <?php foreach ($scheduleDayTabs as $wk => $tabLabel): ?>
                        <?php
                        $dayEvents = $scheduleByWeekday[$wk];
                        $panelHidden = ($dayFilter === 'all') ? false : ($initialScheduleDay !== $wk);
                        $firstEv = $dayEvents[0] ?? null;
                        $panelDateLabel = $firstEv && $firstEv->startTime
                            ? date('l, j F Y', strtotime($firstEv->startTime))
                            : ucfirst($tabLabel);
                        ?>
                        <div class="jazz-sched-tabpanel"
                             id="jazz-panel-<?= htmlspecialchars($wk) ?>"
                             role="tabpanel"
                             aria-labelledby="jazz-tab-<?= htmlspecialchars($wk) ?>"
                             data-day="<?= htmlspecialchars($wk) ?>"
                             <?= $panelHidden ? 'hidden' : '' ?>>
                            <?php if (empty($dayEvents)): ?>
                                <div class="jazz-schedule__empty-day">
                                    <p class="mb-0 text-muted">No performances on <?= htmlspecialchars($tabLabel) ?> yet.</p>
                                </div>
                            <?php else: ?>
                                <p class="jazz-schedule__panel-date"><?= htmlspecialchars($panelDateLabel) ?></p>
                                <div class="jazz-schedule__table-wrap jazz-schedule__table-wrap--tabbed">
                                    <table class="jazz-schedule__table jazz-schedule__table--tabbed">
                                        <colgroup>
                                            <col class="jazz-schedule__col-thumb" span="1">
                                            <col class="jazz-schedule__col-time" span="1">
                                            <col class="jazz-schedule__col-show" span="1">
                                            <col class="jazz-schedule__col-price" span="1">
                                            <col class="jazz-schedule__col-cart" span="1">
                                        </colgroup>
                                        <thead>
                                            <tr>
                                                <th scope="col" class="jazz-schedule__th jazz-schedule__th-thumb"><span class="visually-hidden">Artist</span></th>
                                                <th scope="col" class="jazz-schedule__th jazz-schedule__th-time">Time</th>
                                                <th scope="col" class="jazz-schedule__th jazz-schedule__th-show">Performance</th>
                                                <th scope="col" class="jazz-schedule__th jazz-schedule__th-price">Price</th>
                                                <th scope="col" class="jazz-schedule__th jazz-schedule__th-cart">Tickets</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($dayEvents as $rowIdx => $ev): ?>
                                                <?php
                                                $ticketId = $jazzTicketIds[$ev->eventId] ?? null;
                                                $dayKey = $ev->startTime ? strtolower(date('l', strtotime($ev->startTime))) : '';
                                                $artistLower = strtolower($ev->artist);
                                                $timeFrom = $ev->startTime ? date('H:i', strtotime($ev->startTime)) : '';
                                                $timeTo = $ev->endTime ? date('H:i', strtotime($ev->endTime)) : '';
                                                $timeHuman = htmlspecialchars($timeFrom) . ($timeTo ? ' - ' . htmlspecialchars($timeTo) : '');
                                                $maxQty = 99;
                                                if ($ev->seats !== null && $ev->seats > 0) {
                                                    $maxQty = min(99, $ev->seats);
                                                }
                                                $descPlain = trim(strip_tags($ev->description ?? ''));
                                                $descShort = strlen($descPlain) > 220 ? substr($descPlain, 0, 217) . '…' : $descPlain;
                                                $p = $ev->price !== null ? (float) $ev->price : null;
                                                $priceLabel = $p === null ? '—' : ($p <= 0 ? 'Free' : '€' . number_format($p, fmod($p, 1.0) < 0.005 ? 0 : 2));
                                                $stripeClass = ($rowIdx % 2 === 1) ? ' jazz-schedule__main-row--stripe' : '';
                                                $imgUrl = $ev->homepageImage ?? $ev->profileImage ?? '';
                                                ?>
                                                <tr class="jazz-schedule__main-row jazz-schedule__row<?= $stripeClass ?>"
                                                    data-day="<?= htmlspecialchars($dayKey) ?>"
                                                    data-artist="<?= htmlspecialchars($artistLower, ENT_QUOTES) ?>">
                                                    <td class="jazz-schedule__cell jazz-schedule__cell-thumb">
                                                        <?php if ($imgUrl !== ''): ?>
                                                            <img src="<?= htmlspecialchars($imgUrl) ?>" alt="" class="jazz-schedule__thumb" width="48" height="48" loading="lazy">
                                                        <?php else: ?>
                                                            <span class="jazz-schedule__thumb jazz-schedule__thumb--placeholder" aria-hidden="true"><i class="bi bi-person-fill"></i></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="jazz-schedule__cell jazz-schedule__cell-time">
                                                        <div class="jazz-schedule__time-inner">
                                                            <button type="button" class="jazz-schedule__toggle" aria-expanded="false" aria-label="Show details for <?= htmlspecialchars($ev->artist, ENT_QUOTES) ?>">
                                                                <i class="bi bi-chevron-down jazz-schedule__chev" aria-hidden="true"></i>
                                                            </button>
                                                            <span class="jazz-schedule__time-range"><?= $timeHuman ?></span>
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
                                                            <form action="/cart/add" method="post" class="jazz-schedule__cart-form js-cart-add-form">
                                                                <?= \App\Security\Csrf::field() ?>
                                                                <input type="hidden" name="ticket_id" value="<?= (int) $ticketId ?>">
                                                                <div class="jazz-sched-qty-stepper" data-max="<?= (int) $maxQty ?>">
                                                                    <button type="button" class="jazz-sched-qty-stepper__btn" data-step="-1" aria-label="Decrease quantity">−</button>
                                                                    <input type="number" name="quantity" class="jazz-schedule__qty jazz-sched-qty-stepper__input" value="1" min="1" max="<?= (int) $maxQty ?>" aria-label="Ticket quantity for <?= htmlspecialchars($ev->artist, ENT_QUOTES) ?>">
                                                                    <button type="button" class="jazz-sched-qty-stepper__btn" data-step="1" aria-label="Increase quantity">+</button>
                                                                </div>
                                                                <button type="submit" class="jazz-schedule__add">Add to program</button>
                                                            </form>
                                                        <?php else: ?>
                                                            <span class="jazz-schedule__na text-muted small">N/A</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <tr class="jazz-schedule__detail-row" hidden>
                                                    <td class="jazz-schedule__cell jazz-schedule__cell-detail" colspan="5">
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
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
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
                    <div>
                        <iframe width="100%" height="550px" src="https://api.mapbox.com/styles/v1/arctifice/cmku9dtol005c01sb9uqc939n.html?title=false&access_token=pk.eyJ1IjoiYXJjdGlmaWNlIiwiYSI6ImNtbmQ3ZzNqaTExOG8yeHNoN200eTl2cXEifQ.Jz2jGGD4DxRxonZbH_0Bqg&zoomwheel=false#14.2/52.3855/4.634" title="Festival Map" class="w-100 rounded shadow" style="border:none; min-height: 550px;"></iframe>
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
    const shell = document.getElementById('jazz-schedule-shell');
    const scheduleRows = shell ? [...shell.querySelectorAll('.jazz-schedule__main-row')] : [];
    const artistInput = document.getElementById('schedule-artist-filter');
    const tabBtns = shell ? [...shell.querySelectorAll('.jazz-sched-tabs__btn')] : [];
    const tabPanels = shell ? [...shell.querySelectorAll('.jazz-sched-tabpanel')] : [];

    function setDayUrl(day) {
        const url = day === 'all' ? '/events/jazz?day=all' : '/events/jazz?day=' + encodeURIComponent(day);
        history.replaceState(null, '', url);
    }

    function syncArtistButtons(day) {
        btns.forEach(b => b.classList.toggle('active', b.dataset.filter === day));
    }

    function setScheduleAllMode(showAll) {
        if (!shell || !tabPanels.length) return;
        shell.classList.toggle('jazz-schedule__shell--all-days', !!showAll);
        tabPanels.forEach(p => {
            if (showAll) {
                p.removeAttribute('hidden');
            }
        });
        if (showAll) {
            tabBtns.forEach(b => {
                b.setAttribute('aria-selected', 'false');
                b.setAttribute('tabindex', '-1');
            });
            if (tabBtns[0]) tabBtns[0].setAttribute('tabindex', '0');
        }
    }

    function activateScheduleTab(day) {
        if (!shell || !tabPanels.length) return;
        setScheduleAllMode(false);
        tabBtns.forEach(b => {
            const on = b.dataset.day === day;
            b.setAttribute('aria-selected', on ? 'true' : 'false');
            b.setAttribute('tabindex', on ? '0' : '-1');
        });
        tabPanels.forEach(p => {
            if (p.dataset.day === day) {
                p.removeAttribute('hidden');
            } else {
                p.setAttribute('hidden', '');
            }
        });
    }

    function refreshSchedule() {
        const artistQ = (artistInput && artistInput.value ? artistInput.value : '').trim().toLowerCase();

        scheduleRows.forEach(row => {
            const artistOk = !artistQ || (row.dataset.artist || '').includes(artistQ);
            const show = artistOk;
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
    }

    function filterArtistGrid(day) {
        cols.forEach(col => {
            const match = day === 'all' || col.dataset.day === day;
            col.style.display = match ? '' : 'none';
        });
    }

    function applyDay(day) {
        syncArtistButtons(day);
        setDayUrl(day);
        filterArtistGrid(day);
        if (shell && tabPanels.length) {
            if (day === 'all') {
                setScheduleAllMode(true);
            } else {
                activateScheduleTab(day);
            }
        }
        refreshSchedule();
    }

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            applyDay(this.dataset.day);
        });
    });

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

    document.querySelectorAll('.jazz-sched-qty-stepper').forEach(function (wrap) {
        const input = wrap.querySelector('.jazz-sched-qty-stepper__input');
        const max = parseInt(wrap.getAttribute('data-max') || '99', 10) || 99;
        if (!input) return;
        wrap.querySelectorAll('.jazz-sched-qty-stepper__btn').forEach(function (b) {
            b.addEventListener('click', function () {
                const step = parseInt(b.getAttribute('data-step') || '0', 10);
                let v = parseInt(input.value, 10);
                if (Number.isNaN(v)) v = 1;
                v += step;
                v = Math.max(1, Math.min(max, v));
                input.value = String(v);
            });
        });
    });

    const initial = <?= json_encode($dayFilter) ?>;
    applyDay(initial);
}());
</script>


<?php require __DIR__ . '/../../partials/footer.php'; ?>
