<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Haarlem Festival</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YcnS/1p0TQXB6w2+HlFz5sFpNDwfEBKQlYO" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/globals.css" />
    <link rel="stylesheet" href="/css/styleguide.css" />
    <link rel="stylesheet" href="/css/style.css" />
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding-top: 56px;
        }

        /* ?? Navbar wrapper ?? */
        .navbar-festival {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            background: rgba(80, 80, 80, 0.45);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.25);
        }

        /* Inner bar row � always 56px */
        .navbar-bar {
            display: flex;
            align-items: center;
            height: 56px;
            padding: 0 16px;
        }

        /* Brand */
        .navbar-brand-festival {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            font-weight: 800;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            line-height: 1.15;
            white-space: nowrap;
            text-decoration: none;
            flex-shrink: 0;
        }

        .navbar-brand-festival:hover { color: #fff; }

        .brand-icon {
            width: 38px;
            height: 38px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #fff;
        }

        /* Desktop nav row */
        .nav-center {
            display: flex;
            align-items: center;
            list-style: none;
            margin: 0 auto;
            padding: 0;
            gap: 0;
        }

        .nav-right {
            display: flex;
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 0;
            flex-shrink: 0;
        }

        .nav-center .nav-link-f,
        .nav-right .nav-link-f {
            color: #fff;
            font-weight: 700;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 1.8px;
            padding: 0 22px;
            height: 56px;
            display: flex;
            align-items: center;
            gap: 7px;
            transition: background 0.2s;
            white-space: nowrap;
            text-decoration: none;
        }

        .nav-center .nav-link-f:hover,
        .nav-right .nav-link-f:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Event icons */
        .nav-icon { font-size: 0.9rem; }
        .nav-icon-yummy { color: #e74c3c; }
        .nav-icon-jazz { color: #9b59b6; }
        .nav-icon-history { color: #27ae60; }
        .nav-icon-stories { color: #e67e22; }

        /* Language */
        .nav-lang { padding: 0 8px !important; }
        .lang-divider {
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.75rem;
            padding: 0 2px;
            user-select: none;
            display: flex;
            align-items: center;
            height: 56px;
        }

        /* Auth links */
        .nav-link-auth {
            font-size: 0.7rem !important;
            padding: 0 12px !important;
            letter-spacing: 1px !important;
        }

        /* My Program button */
        .btn-program {
            background-color: #1e2a38;
            color: #fff;
            border: none;
            font-weight: 700;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 8px 16px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background-color 0.2s;
            white-space: nowrap;
            text-decoration: none;
            margin-left: 8px;
        }

        .btn-program:hover {
            background-color: #2c3e50;
            color: #fff;
        }

        /* Toggler � hidden on desktop */
        .navbar-toggler-f {
            display: none;
            background: none;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 4px;
            padding: 6px 10px;
            cursor: pointer;
            margin-left: auto;
        }

        .navbar-toggler-f span {
            display: block;
            width: 20px;
            height: 2px;
            background: #fff;
            margin: 4px 0;
            transition: 0.3s;
        }

        /* Collapse panel � hidden on desktop */
        .navbar-collapse-f {
            display: none;
        }

        /* ?? Mobile ?? */
        @media (max-width: 991.98px) {
            .nav-center,
            .nav-right {
                display: none !important;
            }

            .navbar-toggler-f {
                display: block;
            }

            .navbar-collapse-f {
                display: none;
                background: rgba(30, 30, 30, 0.97);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                padding: 8px 0 16px;
                border-top: 1px solid rgba(255,255,255,0.06);
            }

            .navbar-collapse-f.show {
                display: block;
            }

            .navbar-collapse-f .mobile-nav {
                list-style: none;
                margin: 0;
                padding: 0;
            }

            .navbar-collapse-f .mobile-nav a {
                display: flex;
                align-items: center;
                gap: 10px;
                color: #fff;
                text-decoration: none;
                font-weight: 600;
                font-size: 0.8rem;
                text-transform: uppercase;
                letter-spacing: 1.5px;
                padding: 12px 20px;
                transition: background 0.2s;
            }

            .navbar-collapse-f .mobile-nav a:hover {
                background: rgba(255, 255, 255, 0.08);
            }

            .navbar-collapse-f .mobile-divider {
                border-top: 1px solid rgba(255,255,255,0.1);
                margin: 6px 16px;
            }

            .navbar-collapse-f .btn-program-mobile {
                display: flex;
                align-items: center;
                gap: 8px;
                background-color: #1e2a38;
                color: #fff;
                text-decoration: none;
                font-weight: 700;
                font-size: 0.78rem;
                text-transform: uppercase;
                letter-spacing: 1px;
                padding: 10px 20px;
                margin: 8px 16px 0;
                border-radius: 6px;
                transition: background 0.2s;
            }

            .navbar-collapse-f .btn-program-mobile:hover {
                background-color: #2c3e50;
            }
        }
    </style>
</head>
<body<?= isset($bodyClass) ? ' class="' . htmlspecialchars($bodyClass, ENT_QUOTES, 'UTF-8') . '"' : '' ?>>

<nav class="navbar-festival">
    <!-- Fixed 56px bar -->
    <div class="navbar-bar">
        <a class="navbar-brand-festival" href="/">
            <span class="brand-icon"><i class="bi bi-music-note-beamed"></i></span>
            <span>The Haarlem<br>Festival</span>
        </a>

        <!-- Desktop center links -->
        <ul class="nav-center">
            <li><a class="nav-link-f" href="/events/yummy"><i class="bi bi-cup-straw nav-icon nav-icon-yummy"></i> Yummy</a></li>
            <li><a class="nav-link-f" href="/events/jazz"><i class="bi bi-music-note nav-icon nav-icon-jazz"></i> Jazz</a></li>
            <li><a class="nav-link-f" href="/events/history"><i class="bi bi-bank nav-icon nav-icon-history"></i> History</a></li>
            <li><a class="nav-link-f" href="/events/stories"><i class="bi bi-book nav-icon nav-icon-stories"></i> Storytelling</a></li>
        </ul>

        <!-- Desktop right links -->
        <ul class="nav-right">
            <li><a class="nav-link-f nav-lang" href="#">EN</a></li>
            <li><span class="lang-divider">|</span></li>
            <li><a class="nav-link-f nav-lang" href="#">NL</a></li>
            <?php if (!empty($_SESSION['user_id'])): ?>
                <li><a class="nav-link-f nav-link-auth" href="#"><i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></a></li>
                <li><a class="nav-link-f nav-link-auth" href="/logout"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            <?php else: ?>
                <li><a class="nav-link-f nav-link-auth" href="/login"><i class="bi bi-person"></i> Login</a></li>
                <li><a class="nav-link-f nav-link-auth" href="/register"><i class="bi bi-person-plus"></i> Register</a></li>
            <?php endif; ?>
            <li><a class="btn-program" href="#"><i class="bi bi-calendar-event"></i> My Program</a></li>
        </ul>

        <!-- Mobile hamburger -->
        <button class="navbar-toggler-f" id="navToggler" aria-label="Toggle navigation">
            <span></span><span></span><span></span>
        </button>
    </div>

    <!-- Mobile collapse panel -->
    <div class="navbar-collapse-f" id="navCollapse">
        <ul class="mobile-nav">
            <li><a href="/events/yummy"><i class="bi bi-cup-straw nav-icon-yummy"></i> Yummy</a></li>
            <li><a href="/events/jazz"><i class="bi bi-music-note nav-icon-jazz"></i> Jazz</a></li>
            <li><a href="/events/history"><i class="bi bi-bank nav-icon-history"></i> History</a></li>
            <li><a href="/events/stories"><i class="bi bi-book nav-icon-stories"></i> Storytelling</a></li>
        </ul>
        <div class="mobile-divider"></div>
        <ul class="mobile-nav">
            <li><a href="#">EN</a></li>
            <li><a href="#">NL</a></li>
        </ul>
        <div class="mobile-divider"></div>
        <ul class="mobile-nav">
            <?php if (!empty($_SESSION['user_id'])): ?>
                <li><a href="#"><i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></a></li>
                <li><a href="/logout"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            <?php else: ?>
                <li><a href="/login"><i class="bi bi-person"></i> Login</a></li>
                <li><a href="/register"><i class="bi bi-person-plus"></i> Register</a></li>
            <?php endif; ?>
        </ul>
        <a class="btn-program-mobile" href="#"><i class="bi bi-calendar-event"></i> My Program</a>
    </div>
</nav>

<script>
document.getElementById('navToggler').addEventListener('click', function() {
    document.getElementById('navCollapse').classList.toggle('show');
});
</script>

<main class="<?= isset($mainClass) ? htmlspecialchars($mainClass, ENT_QUOTES, 'UTF-8') : 'container mt-4' ?>">
