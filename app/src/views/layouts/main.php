<?php

/**
 * Main layout: full HTML document with navbar, main content, footer.
 * Expects (set by controller): $data, $currentRoute, $pageTitle, $mainView
 */
$footerLinks = $data->footerLinks ?? [];
$footerSocialUrls = $data->footerSocialUrls ?? [];
$pageTitle = $pageTitle ?? 'The Festival';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { padding-top: 56px; }
        .hero-section { min-height: 70vh; background-size: cover; background-position: center; }
        .hero-overlay { background: rgba(0,0,0,.45); min-height: 70vh; }
    </style>
</head>
<body>
<?php require __DIR__ . '/../partials/navbar.php'; ?>
<main>
<?php require $mainView; ?>
</main>
<?php require __DIR__ . '/../partials/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
