<?php require __DIR__ . '/../partials/header.php'; ?>

<style>
    .register-wrapper {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 16px;
    }

    .register-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.10);
        border: none;
        overflow: hidden;
        width: 100%;
        max-width: 460px;
    }

    .register-header {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: #fff;
        padding: 32px 36px;
        text-align: center;
    }

    .register-header h2 {
        margin: 0;
        font-weight: 700;
        font-size: 1.6rem;
        letter-spacing: 0.3px;
    }

    .register-header p {
        margin: 8px 0 0;
        font-size: 0.88rem;
        opacity: 0.7;
        font-weight: 400;
    }

    .register-body {
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

    .password-hint {
        display: flex;
        gap: 6px;
        align-items: flex-start;
        margin-top: 10px;
        padding: 10px 12px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #eee;
    }

    .password-hint i {
        color: #aaa;
        font-size: 0.85rem;
        margin-top: 1px;
        flex-shrink: 0;
    }

    .password-hint span {
        font-size: 0.78rem;
        color: #888;
        line-height: 1.5;
    }

    .recaptcha-wrapper {
        display: flex;
        justify-content: center;
        margin: 24px 0;
    }

    .btn-register {
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

    .btn-register:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(44, 62, 80, 0.3);
        color: #fff;
    }

    .btn-register:active {
        transform: translateY(0);
    }

    .register-footer {
        padding: 0 36px 32px;
        text-align: center;
    }

    .register-footer p {
        font-size: 0.88rem;
        color: #999;
        margin: 0;
    }

    .register-footer a {
        color: #2c3e50;
        font-weight: 700;
        text-decoration: none;
        transition: color 0.2s;
    }

    .register-footer a:hover {
        color: #1a252f;
        text-decoration: underline;
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
</style>

<div class="register-wrapper">
    <div class="register-card">
        <div class="register-header">
            <h2><i class="bi bi-person-plus"></i> Create an Account</h2>
            <p>Join The Haarlem Festival community</p>
        </div>

        <div class="register-body">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger py-2 px-3 mb-4" role="alert" style="border-radius: 10px; border: none; background: #fff5f5; color: #c0392b;">
                    <ul class="mb-0 ps-3" style="font-size: 0.85rem; line-height: 1.7;">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="/register" novalidate>
                <div class="form-group-custom">
                    <label for="name"><i class="bi bi-person"></i> Username</label>
                    <input type="text"
                           class="form-control"
                           id="name"
                           name="name"
                           value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                           required
                           minlength="2"
                           maxlength="255"
                           autocomplete="username"
                           placeholder="Choose a username">
                </div>

                <div class="form-group-custom">
                    <label for="email"><i class="bi bi-envelope"></i> Email Address</label>
                    <input type="email"
                           class="form-control"
                           id="email"
                           name="email"
                           value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                           required
                           autocomplete="email"
                           placeholder="you@example.com">
                </div>

                <div class="form-group-custom">
                    <label for="password"><i class="bi bi-lock"></i> Password</label>
                    <div class="password-wrapper">
                        <input type="password"
                               class="form-control"
                               id="password"
                               name="password"
                               required
                               minlength="10"
                               autocomplete="new-password"
                               placeholder="Min. 10 characters">
                        <button type="button" class="password-toggle" onclick="togglePassword('password', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="password-hint">
                        <i class="bi bi-shield-check"></i>
                        <span>At least 10 characters, one uppercase, one lowercase, one digit, and one special character.</span>
                    </div>
                </div>

                <div class="form-group-custom">
                    <label for="password_confirmation"><i class="bi bi-lock-fill"></i> Confirm Password</label>
                    <div class="password-wrapper">
                        <input type="password"
                               class="form-control"
                               id="password_confirmation"
                               name="password_confirmation"
                               required
                               autocomplete="new-password"
                               placeholder="Repeat your password">
                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="recaptcha-wrapper">
                    <div class="g-recaptcha" data-sitekey="<?= htmlspecialchars($recaptchaSiteKey) ?>"></div>
                </div>

                <button type="submit" class="btn btn-register">
                    <i class="bi bi-check-circle"></i> Create Account
                </button>
            </form>
        </div>

        <div class="divider-text" style="margin: 0 36px;"></div>

        <div class="register-footer">
            <p>Already have an account? <a href="/login">Log in</a></p>
        </div>
    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
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
