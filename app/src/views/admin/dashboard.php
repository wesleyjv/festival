<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="row justify-content-center mt-5">
    <div class="col-md-8 text-center">
        <h1>Admin Dashboard</h1>
        <p class="text-muted">Welcome, <?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>. This is the admin area.</p>
        <p class="text-muted">Dashboard functionality coming soon.</p>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
