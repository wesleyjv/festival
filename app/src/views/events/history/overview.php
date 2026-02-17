<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historic Haarlem — Haarlem Festival</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">
            <i class="bi bi-music-note-beamed me-1"></i> Haarlem Festival
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/events/yummy"><i class="bi bi-egg-fried me-1"></i>Yummy</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/events/stories"><i class="bi bi-book me-1"></i>Stories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/events/jazz"><i class="bi bi-vinyl me-1"></i>Jazz</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="/events/history"><i class="bi bi-bank me-1"></i>History</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/cart"><i class="bi bi-cart3"></i> Cart</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-light btn-sm ms-2 my-1" href="/login">Login</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-warning btn-sm ms-2 my-1 fw-semibold" href="/register">Register</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero -->
<section class="bg-dark text-white py-5">
    <div class="container text-center py-5">
        <nav aria-label="breadcrumb" class="d-flex justify-content-center mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/" class="text-warning text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active text-white-50" aria-current="page">History</li>
            </ol>
        </nav>
        <h1 class="display-4 fw-bold mb-3"><i class="bi bi-bank me-2"></i>Historic Haarlem</h1>
        <p class="lead mx-auto col-lg-6 opacity-75">
            Walk through centuries of rich history with expert guides. Explore Haarlem's most iconic landmarks and hidden gems.
        </p>
    </div>
</section>

<!-- Events List -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Available Tours</h3>
                <p class="text-muted mb-0"><?= count($events) ?> guided tour<?= count($events) !== 1 ? 's' : '' ?> available</p>
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
    </div>
</section>

<!-- CTA -->
<section class="bg-dark text-white py-5">
    <div class="container text-center py-3">
        <h4 class="fw-bold mb-3">Want to explore more of Haarlem?</h4>
        <p class="mb-4 opacity-75">Check out our other events — from jazz to food and stories.</p>
        <a href="/" class="btn btn-warning px-4 fw-semibold">View All Events</a>
    </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-white border-top border-secondary py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4 mb-3 mb-md-0">
                <h6 class="fw-bold"><i class="bi bi-music-note-beamed me-1"></i> Haarlem Festival</h6>
                <small class="text-white-50">&copy; <?= date('Y') ?> All rights reserved.</small>
            </div>
            <div class="col-md-4 text-center mb-3 mb-md-0">
                <a href="/events/yummy" class="link-secondary text-decoration-none me-3">Yummy</a>
                <a href="/events/stories" class="link-secondary text-decoration-none me-3">Stories</a>
                <a href="/events/jazz" class="link-secondary text-decoration-none me-3">Jazz</a>
                <a href="/events/history" class="link-secondary text-decoration-none">History</a>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="#" class="link-secondary text-decoration-none me-2"><i class="bi bi-facebook"></i></a>
                <a href="#" class="link-secondary text-decoration-none me-2"><i class="bi bi-instagram"></i></a>
                <a href="#" class="link-secondary text-decoration-none"><i class="bi bi-twitter-x"></i></a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>