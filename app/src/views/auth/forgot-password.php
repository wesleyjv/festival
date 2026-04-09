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
    }

    .register-footer a:hover {
        color: #1a252f;
        text-decoration: underline;
    }
</style>

<div class="register-wrapper">
    <div class="register-card">
        <div class="register-header">
            <h2><i class="bi bi-envelope"></i> Forgot password</h2>
            <p>Enter your email and we will send you a reset link</p>
        </div>

        <div class="register-body">
            <?php if (!empty($success)): ?>
                <div class="alert alert-success py-2 px-3 mb-4" role="alert" style="border-radius: 10px; border: none; background: #f0faf0; color: #27ae60;">
                    <div style="display: flex; align-items: flex-start; gap: 8px; font-size: 0.85rem;">
                        <i class="bi bi-check-circle-fill flex-shrink-0 mt-1"></i>
                        <span><?= htmlspecialchars($success) ?></span>
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

            <?php if (!empty($smtpConsoleLog) && is_array($smtpConsoleLog)): ?>
                <script>
                (function () {
                    var detail = <?= json_encode($smtpConsoleLog, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE) ?>;
                    console.error('[Forgot password] SMTP failure (APP_DEBUG)', detail);
                    if (detail && detail.phpmailerError) {
                        console.error('[Forgot password] PHPMailer / SMTP message:', detail.phpmailerError);
                    }
                    if (detail && detail.exceptionThrownIn) {
                        console.error('[Forgot password] Exception thrown in (file:line):', detail.exceptionThrownIn);
                    }
                    if (detail && detail.handledInMailService) {
                        console.info('[Forgot password] Caught/handled in MailService (file:line):', detail.handledInMailService);
                    }
                    if (detail && detail.calledFrom) {
                        console.info('[Forgot password] Your app called send from:', detail.calledFrom);
                    }
                })();
                </script>
            <?php endif; ?>

            <form method="POST" action="/forgot-password" novalidate>
                <?= \App\Security\Csrf::field() ?>
                <div class="form-group-custom">
                    <label for="email"><i class="bi bi-envelope"></i> Email address</label>
                    <input type="email"
                           class="form-control"
                           id="email"
                           name="email"
                           value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                           required
                           autocomplete="email"
                           placeholder="you@example.com">
                </div>

                <button type="submit" class="btn btn-register">
                    <i class="bi bi-send"></i> Send reset link
                </button>
            </form>
        </div>

        <div class="register-footer">
            <p><a href="/login">Back to login</a></p>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
