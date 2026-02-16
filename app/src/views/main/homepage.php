<?php
// app\src\views\main\homepage.php
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Homepage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <!-- Left: Logo / Title -->
        <a class="navbar-brand fw-bold" href="/">
            🎪 Festival
        </a>

        <!-- Mobile toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Center + Right -->
        <div class="collapse navbar-collapse" id="mainNavbar">
            <!-- Center links -->
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/events">Yummy</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/tickets">Stories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/orders">Jazz</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/contact">History</a>
                </li>
            </ul>

            <!-- Right links -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/cart">🛒 Cart</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-light ms-2" href="/login">Login</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-warning ms-2" href="/register">Register</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
