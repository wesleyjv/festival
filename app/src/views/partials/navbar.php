<?php

/**
 * Reusable navbar partial. Active link is set from $currentRoute (e.g. home, events, about, contact).
 * Variables: $currentRoute, $pageTitle (optional)
 */
$currentRoute = $currentRoute ?? 'home';
$navItems = [
    ['route' => 'home', 'label' => 'Home', 'url' => '/'],
    ['route' => 'events', 'label' => 'Events', 'url' => '/events'],
    ['route' => 'about', 'label' => 'About', 'url' => '/about'],
    ['route' => 'contact', 'label' => 'Contact', 'url' => '/contact'],
];
?>
<header>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">THE FESTIVAL</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav ms-auto">
                    <?php foreach ($navItems as $item): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentRoute === $item['route']) ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($item['url']); ?>">
                                <?php echo htmlspecialchars($item['label']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
