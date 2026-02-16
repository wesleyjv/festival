<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <h2 class="text-center mb-4">Login</h2>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success" role="alert">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="/login" novalidate>
            <div class="mb-3">
                <label for="identity" class="form-label">Username or Email</label>
                <input type="text"
                       class="form-control"
                       id="identity"
                       name="identity"
                       value="<?= htmlspecialchars($old['identity'] ?? '') ?>"
                       required
                       autocomplete="username"
                       placeholder="Enter your username or email">
                <small id="identityHint" class="form-text text-muted"></small>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password"
                       class="form-control"
                       id="password"
                       name="password"
                       required
                       autocomplete="current-password"
                       placeholder="Enter your password">
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn btn-dark w-100">Login</button>
        </form>

        <p class="text-center mt-3">
            Don't have an account? <a href="/register">Register here</a>
        </p>
    </div>
</div>

<script>
    const identityInput = document.getElementById('identity');
    const identityHint = document.getElementById('identityHint');

    identityInput.addEventListener('input', function () {
        const val = this.value.trim();
        if (val.length === 0) {
            identityHint.textContent = '';
        } else if (val.includes('@')) {
            identityHint.textContent = 'Detected: email address';
        } else {
            identityHint.textContent = 'Detected: username';
        }
    });
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
