<?php

/**
 * Footer partial. Links and social URLs are loaded from backend ($footerLinks, $footerSocialUrls).
 * Variables: $footerLinks (array of [label, url]), $footerSocialUrls (array of [name, url, icon])
 */
$footerLinks = $footerLinks ?? [];
$footerSocialUrls = $footerSocialUrls ?? [];
?>
<footer class="bg-dark text-light py-4 mt-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4 text-center text-md-start mb-3 mb-md-0">
                <span class="fw-bold">THE FESTIVAL</span>
                <span class="text-secondary ms-2">© <?php echo date('Y'); ?></span>
            </div>
            <div class="col-md-4 text-center mb-3 mb-md-0">
                <?php foreach ($footerSocialUrls as $social): ?>
                    <a href="<?php echo htmlspecialchars($social['url']); ?>" class="text-light me-3" target="_blank" rel="noopener" aria-label="<?php echo htmlspecialchars($social['name']); ?>">
                        <span class="bi bi-<?php echo htmlspecialchars($social['icon']); ?>"></span>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="col-md-4 text-center text-md-end">
                <?php foreach ($footerLinks as $link): ?>
                    <a href="<?php echo htmlspecialchars($link['url']); ?>" class="text-secondary text-decoration-none me-2"><?php echo htmlspecialchars($link['label']); ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</footer>
