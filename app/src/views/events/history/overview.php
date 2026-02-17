    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    
    <?php require __DIR__ . '/../../partials/header.php'; ?>

    <div class="container py-5">

        <!-- Hero -->
        <div class="text-center mb-5">
            <div class="bg-light rounded-4 p-4 p-md-5 shadow-sm">
                <h1 class="fw-bold mb-2">
                    <i class="bi bi-bank me-2"></i>Historic Haarlem
                </h1>
                <p class="text-muted mb-0">
                    Walk through centuries of rich history with expert guides. Explore Haarlem's most iconic landmarks and hidden gems.
                </p>
            </div>
        </div>

        <!-- Info -->
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

                                <!-- Header -->
                                <div class="mb-2">
                                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($event->title) ?></h5>
                                    <span class="badge bg-primary-subtle text-primary">
                                        <i class="bi bi-translate me-1"></i><?= htmlspecialchars($event->language) ?>
                                    </span>
                                </div>

                                <!-- Description -->
                                <p class="text-muted small flex-grow-1">
                                    <?= htmlspecialchars($event->description) ?>
                                </p>

                                <!-- Guide -->
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center p-2 me-2">
                                        <i class="bi bi-person-badge"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Guide</small>
                                        <span class="fw-semibold small"><?= htmlspecialchars($event->guide) ?></span>
                                    </div>
                                </div>

                                <!-- Button -->
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

    <?php require __DIR__ . '/../../partials/footer.php'; ?>
