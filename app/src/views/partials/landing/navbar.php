<?php
/**
 * Small top nav for landing hero. Expects: $navLinks (array of { label, url }), $logoText, $logoUrl
 */
$logoText = $logoText ?? 'THE FESTIVAL';
$logoUrl = $logoUrl ?? '/';
$navLinks = $navLinks ?? [
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Events', 'url' => '/events'],
    ['label' => 'About', 'url' => '/about'],
    ['label' => 'Contact', 'url' => '/contact'],
];
?>
<nav class="landing-nav" aria-label="Main navigation">
  <div class="landing-nav__inner">
    <a href="<?php echo htmlspecialchars($logoUrl); ?>" class="landing-nav__logo"><?php echo htmlspecialchars($logoText); ?></a>
    <ul class="landing-nav__menu">
      <?php foreach ($navLinks as $link): ?>
        <li><a href="<?php echo htmlspecialchars($link['url']); ?>" class="landing-nav__link"><?php echo htmlspecialchars($link['label']); ?></a></li>
      <?php endforeach; ?>
    </ul>
  </div>
</nav>
