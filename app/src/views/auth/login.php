<?php require __DIR__ . '/../partials/header.php'; ?>

<style>
    .login-wrapper {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 16px;
    }

    .login-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.10);
        border: none;
        overflow: hidden;
        width: 100%;
        max-width: 460px;
    }

    .login-header {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: #fff;
        padding: 32px 36px;
        text-align: center;
    }

    .login-header h2 {
        margin: 0;
        font-weight: 700;
        font-size: 1.6rem;
        letter-spacing: 0.3px;
    }

    .login-header p {
        margin: 8px 0 0;
        font-size: 0.88rem;
        opacity: 0.7;
        font-weight: 400;
    }

    .login-body {
        padding: 36px 36px 24px;
    }

    .form-group-custom {
        margin-bottom: 22px;
    }

    .form-group-custom label {
        font-weight: 600;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #666;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-group-custom label i {
        font-size: 0.85rem;
        color: #999;
    }

    .form-group-custom .form-control {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 0.92rem;
        transition: border-color 0.2s, box-shadow 0.2s;
        background: #fafafa;
    }

    .form-group-custom .form-control:focus {
        border-color: #2c3e50;
        box-shadow: 0 0 0 3px rgba(44, 62, 80, 0.08);
        background: #fff;
    }

    .form-group-custom .form-control::placeholder {
        color: #b0b0b0;
    }

    .identity-hint {
        display: block;
        margin-top: 6px;
        font-size: 0.75rem;
        color: #999;
        min-height: 1.1em;
        transition: color 0.2s;
    }

    .identity-hint.detected-email {
        color: #2980b9;
    }

    .identity-hint.detected-username {
        color: #27ae60;
    }

    .password-wrapper {
        position: relative;
    }

    .password-wrapper .form-control {
        padding-right: 48px;
    }

    .password-toggle {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #999;
        cursor: pointer;
        padding: 0;
        font-size: 1.1rem;
        transition: color 0.2s;
    }

    .password-toggle:hover {
        color: #2c3e50;
    }

    .remember-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .remember-check {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .remember-check input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: #2c3e50;
        cursor: pointer;
    }

    .remember-check label {
        font-size: 0.82rem;
        color: #666;
        cursor: pointer;
        margin: 0;
        user-select: none;
    }

    .forgot-password-link {
        font-size: 0.82rem;
        font-weight: 600;
        color: #2c3e50;
        text-decoration: none;
    }

    .forgot-password-link:hover {
        color: #1a252f;
        text-decoration: underline;
    }

    .btn-login {
        background: linear-gradient(135deg, #2c3e50, #34495e);
        border: none;
        color: #fff;
        font-weight: 700;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        padding: 14px;
        border-radius: 10px;
        transition: transform 0.15s, box-shadow 0.15s;
        width: 100%;
    }

    .btn-login:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(44, 62, 80, 0.3);
        color: #fff;
    }

    .btn-login:active {
        transform: translateY(0);
    }

    .divider-text {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 20px 0;
        color: #ccc;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .divider-text::before,
    .divider-text::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e9ecef;
    }

    .login-footer {
        padding: 0 36px 32px;
        text-align: center;
    }

    .login-footer p {
        font-size: 0.88rem;
        color: #999;
        margin: 0;
    }

    .login-footer a {
        color: #2c3e50;
        font-weight: 700;
        text-decoration: none;
        transition: color 0.2s;
    }

    .login-footer a:hover {
        color: #1a252f;
        text-decoration: underline;
    }
</style>

<div class="login-wrapper">
    <div class="login-card">
        <div class="login-header">
            <h2><i class="bi bi-box-arrow-in-right"></i> Welcome Back</h2>
            <p>Log in to your Haarlem Festival account</p>
        </div>

        <div class="login-body">
            <?php if (!empty($success)): ?>
                <div class="alert alert-success py-2 px-3 mb-4" role="alert" style="border-radius: 10px; border: none; background: #f0faf0; color: #27ae60;">
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem;">
                        <i class="bi bi-check-circle-fill"></i>
                        <?= htmlspecialchars($success) ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger py-2 px-3 mb-4" role="alert" style="border-radius: 10px; border: none; background: #fff5f5; color: #c0392b;">
                    <ul class="mb-0 ps-3" style="font-size: 0.85rem; line-height: 1.7;">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="/login" novalidate>
                <?= \App\Security\Csrf::field() ?>
                <div class="form-group-custom">
                    <label for="identity"><i class="bi bi-person"></i> Username or Email</label>
                    <input type="text"
                           class="form-control"
                           id="identity"
                           name="identity"
                           value="<?= htmlspecialchars($old['identity'] ?? '') ?>"
                           required
                           autocomplete="username"
                           placeholder="Enter your username or email">
                    <small id="identityHint" class="identity-hint"></small>
                </div>

                <div class="form-group-custom">
                    <label for="password"><i class="bi bi-lock"></i> Password</label>
                    <div class="password-wrapper">
                        <input type="password"
                               class="form-control"
                               id="password"
                               name="password"
                               required
                               autocomplete="current-password"
                               placeholder="Enter your password">
                        <button type="button" class="password-toggle" onclick="togglePassword('password', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="remember-row">
                    <div class="remember-check">
                        <input type="checkbox" id="remember" name="remember" value="1">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="/forgot-password" class="forgot-password-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> Log In
                </button>
            </form>
        </div>

        <div class="divider-text" style="margin: 0 36px;"></div>

        <div class="login-footer">
            <p>Don't have an account? <a href="/register">Create one</a></p>
        </div>
    </div>
</div>

<script>
const identityInput = document.getElementById('identity');
const identityHint = document.getElementById('identityHint');

identityInput.addEventListener('input', function () {
    const val = this.value.trim();
    identityHint.classList.remove('detected-email', 'detected-username');
    if (val.length === 0) {
        identityHint.textContent = '';
    } else if (val.includes('@')) {
        identityHint.textContent = '\u2709 Detected: email address';
        identityHint.classList.add('detected-email');
    } else {
        identityHint.textContent = '\uD83D\uDC64 Detected: username';
        identityHint.classList.add('detected-username');
    }
});

function togglePassword(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
