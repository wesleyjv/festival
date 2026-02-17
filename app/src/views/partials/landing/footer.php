<?php
/**
 * Landing footer. Expects: $footerLinks, $footerSocialUrls (from HomepageData), $logoText, $copyright
 */
$logoText = $logoText ?? 'THE FESTIVAL';
$footerLinks = $footerLinks ?? [];
$footerSocialUrls = $footerSocialUrls ?? [];
$copyright = $copyright ?? '© ' . date('Y') . ' The Festival';
?>
<footer class="landing-footer">
  <div class="landing-footer__inner">
    <a href="/" class="landing-footer__logo"><?php echo htmlspecialchars($logoText); ?></a>
    <ul class="landing-footer__nav">
      <?php foreach ($footerLinks as $link): ?>
        <li><a href="<?php echo htmlspecialchars($link['url']); ?>"><?php echo htmlspecialchars($link['label']); ?></a></li>
      <?php endforeach; ?>
    </ul>
    <div class="landing-footer__social">
      <?php foreach ($footerSocialUrls as $social): ?>
        <a href="<?php echo htmlspecialchars($social['url']); ?>" target="_blank" rel="noopener" aria-label="<?php echo htmlspecialchars($social['name']); ?>"><span class="bi bi-<?php echo htmlspecialchars($social['icon']); ?>"></span></a>
      <?php endforeach; ?>
    </div>
    <p class="landing-footer__copy"><?php echo htmlspecialchars($copyright); ?></p>
  </div>
</footer>
