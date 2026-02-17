<?php
// app\src\views\main\homepage.php
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Haarlem Festival</title>
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
                    <a class="nav-link" href="/events/history"><i class="bi bi-bank me-1"></i>History</a>
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
        <p class="text-warning fw-semibold text-uppercase mb-3">July 24 – 30, 2025</p>
        <h1 class="display-3 fw-bold mb-4">Welcome to<br>The Haarlem Festival</h1>
        <p class="lead mx-auto mb-5 col-lg-6 opacity-75">
            Discover the best of food, music, stories, and history in the heart of Haarlem.
            Seven days of unforgettable experiences await you.
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="#events" class="btn btn-warning btn-lg px-4 fw-semibold">
                <i class="bi bi-calendar-event me-2"></i>Explore Events
            </a>
            <a href="/register" class="btn btn-outline-light btn-lg px-4">
                <i class="bi bi-person-plus me-2"></i>Get Tickets
            </a>
        </div>
    </div>
</section>

<!-- Events Section -->
<section id="events" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Our Events</h2>
            <p class="text-muted col-lg-6 mx-auto">
                Four unique experiences across the city — there's something for everyone.
            </p>
        </div>
        <div class="row g-4">
            <!-- Yummy -->
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 h-100 shadow-sm rounded-4">
                    <div class="card-body text-center p-4">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-3 d-inline-flex align-items-center justify-content-center fs-1 p-3 mb-3">
                            <i class="bi bi-egg-fried"></i>
                        </div>
                        <h5 class="fw-bold">Yummy</h5>
                        <p class="text-muted small">Taste Haarlem's finest restaurants and street food in a culinary adventure.</p>
                        <a href="/events/yummy" class="btn btn-outline-warning btn-sm mt-2">Discover</a>
                    </div>
                </div>
            </div>
            <!-- Stories -->
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 h-100 shadow-sm rounded-4">
                    <div class="card-body text-center p-4">
                        <div class="bg-info bg-opacity-10 text-info rounded-3 d-inline-flex align-items-center justify-content-center fs-1 p-3 mb-3">
                            <i class="bi bi-book"></i>
                        </div>
                        <h5 class="fw-bold">Stories</h5>
                        <p class="text-muted small">Immerse yourself in captivating tales told throughout the historic city.</p>
                        <a href="/events/stories" class="btn btn-outline-info btn-sm mt-2">Discover</a>
                    </div>
                </div>
            </div>
            <!-- Jazz -->
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 h-100 shadow-sm rounded-4">
                    <div class="card-body text-center p-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-inline-flex align-items-center justify-content-center fs-1 p-3 mb-3">
                            <i class="bi bi-vinyl"></i>
                        </div>
                        <h5 class="fw-bold">Jazz</h5>
                        <p class="text-muted small">Enjoy world-class jazz performances at stunning venues across Haarlem.</p>
                        <a href="/events/jazz" class="btn btn-outline-primary btn-sm mt-2">Discover</a>
                    </div>
                </div>
            </div>
            <!-- History -->
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 h-100 shadow-sm rounded-4">
                    <div class="card-body text-center p-4">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-3 d-inline-flex align-items-center justify-content-center fs-1 p-3 mb-3">
                            <i class="bi bi-bank"></i>
                        </div>
                        <h5 class="fw-bold">History</h5>
                        <p class="text-muted small">Walk through centuries of history with expert guides in multiple languages.</p>
                        <a href="/events/history" class="btn btn-outline-danger btn-sm mt-2">Discover</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="bg-dark text-white py-5">
    <div class="container text-center py-4">
        <h3 class="fw-bold mb-3">Ready to experience Haarlem?</h3>
        <p class="mb-4 opacity-75">Secure your spot at the festival's most popular events before they sell out.</p>
        <a href="/register" class="btn btn-warning btn-lg px-5 fw-semibold">Get Your Tickets</a>
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
