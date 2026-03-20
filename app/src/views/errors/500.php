<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container text-center py-5">
    <h1 class="display-1 fw-bold">500</h1>
    <p class="lead"><?= htmlspecialchars($message ?? 'Something went wrong. Please try again later.') ?></p>
    <a href="/" class="btn btn-dark mt-3">Back to home</a>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
