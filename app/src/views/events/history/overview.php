<?php require __DIR__ . '/../../partials/header.php'; ?>

<h1 class="fw-bold mb-3"><i class="bi bi-bank me-2"></i>Historic Haarlem</h1>
<p class="text-muted mb-4">Walk through centuries of rich history with expert guides. Explore Haarlem's most iconic landmarks and hidden gems.</p>

<h3 class="fw-bold mb-1">Available Tours</h3>
<p class="text-muted mb-4"><?= count($events) ?> guided tour<?= count($events) !== 1 ? 's' : '' ?> available</p>

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
                <div class="card border-0 h-100 shadow-sm rounded-4 overflow-hidden">
                    <?php if (!empty($event->image)): ?>
                        <img src="<?= htmlspecialchars($event->image) ?>" class="card-img-top" alt="<?= htmlspecialchars($event->name) ?>">
                    <?php else: ?>
                        <div class="bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center py-5">
                            <i class="bi bi-bank text-secondary fs-1 opacity-25"></i>
                        </div>
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="fw-bold mb-0"><?= htmlspecialchars($event->name) ?></h5>
                            <span class="badge bg-primary bg-opacity-10 text-primary ms-2">
                                <i class="bi bi-translate me-1"></i><?= htmlspecialchars($event->language) ?>
                            </span>
                        </div>
                        <p class="text-muted small flex-grow-1"><?= htmlspecialchars($event->description) ?></p>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center p-2 me-2">
                                <i class="bi bi-person-badge text-dark"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block lh-1">Guide</small>
                                <span class="fw-semibold small"><?= htmlspecialchars($event->guide) ?></span>
                            </div>
                        </div>
                        <a href="/tickets?event_id=<?= $event->id ?>" class="btn btn-danger btn-sm w-100">
                            <i class="bi bi-ticket-perforated me-1"></i>View Tickets
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../../partials/footer.php'; ?>