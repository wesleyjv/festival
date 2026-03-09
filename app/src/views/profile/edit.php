<?php require __DIR__ . '/../partials/header.php'; ?>

<style>
    .profile-wrapper {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: 48px 16px;
    }

    .profile-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.10);
        border: none;
        overflow: hidden;
        width: 100%;
        max-width: 520px;
    }

    .profile-header {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: #fff;
        padding: 32px 36px;
        text-align: center;
    }

    .profile-avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255,255,255,0.4);
        margin-bottom: 14px;
    }

    .profile-avatar-placeholder {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: rgba(255,255,255,0.15);
        border: 3px solid rgba(255,255,255,0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 14px;
        font-size: 2.2rem;
        color: #fff;
    }

    .profile-header h2 {
        margin: 0;
        font-weight: 700;
        font-size: 1.5rem;
    }

    .profile-header p {
        margin: 6px 0 0;
        font-size: 0.85rem;
        opacity: 0.7;
    }

    .profile-body {
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

    .password-toggle:hover { color: #2c3e50; }

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

    .password-hint i { color: #aaa; font-size: 0.85rem; margin-top: 1px; flex-shrink: 0; }
    .password-hint span { font-size: 0.78rem; color: #888; line-height: 1.5; }

    .section-divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 28px 0 20px;
        color: #ccc;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .section-divider::before,
    .section-divider::after {
        content: '';
        flex: 1;
        border-top: 1px solid #eee;
    }

    .btn-save {
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

    .btn-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(44, 62, 80, 0.3);
        color: #fff;
    }

    .btn-save:active { transform: translateY(0); }

    .profile-footer {
        padding: 0 36px 32px;
        text-align: center;
    }

    .profile-footer p { font-size: 0.88rem; color: #999; margin: 0; }
    .profile-footer a { color: #2c3e50; font-weight: 700; text-decoration: none; }
    .profile-footer a:hover { text-decoration: underline; }

    .preview-img {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #dee2e6;
        display: none;
        margin-top: 10px;
    }
</style>

<div class="profile-wrapper">
    <div class="profile-card">
        <div class="profile-header">
            <?php if (!empty($user->profileImage)): ?>
                <img src="<?= htmlspecialchars($user->profileImage, ENT_QUOTES, 'UTF-8') ?>" alt="Profile picture" class="profile-avatar">
            <?php else: ?>
                <div class="profile-avatar-placeholder"><i class="bi bi-person-fill"></i></div>
            <?php endif; ?>
            <h2><?= htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8') ?></h2>
            <p><?= htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8') ?></p>
        </div>

        <div class="profile-body">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger rounded-3 mb-4" role="alert">
                    <ul class="mb-0 ps-3">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success rounded-3 mb-4" role="alert">
                    <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/profile/update" enctype="multipart/form-data" novalidate>
                <div class="form-group-custom">
                    <label for="name"><i class="bi bi-person"></i> Name</label>
                    <input
                        type="text"
                        class="form-control"
                        id="name"
                        name="name"
                        value="<?= htmlspecialchars($old['name'] ?? $user->name, ENT_QUOTES, 'UTF-8') ?>"
                        placeholder="Your display name"
                        required
                    >
                </div>

                <div class="form-group-custom">
                    <label for="email"><i class="bi bi-envelope"></i> Email</label>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        value="<?= htmlspecialchars($old['email'] ?? $user->email, ENT_QUOTES, 'UTF-8') ?>"
                        placeholder="your@email.com"
                        required
                    >
                </div>

                <div class="form-group-custom">
                    <label for="profile_image"><i class="bi bi-image"></i> Profile Picture</label>
                    <input
                        type="file"
                        class="form-control"
                        id="profile_image"
                        name="profile_image"
                        accept="image/jpeg,image/png,image/gif,image/webp"
                    >
                    <img id="imagePreview" class="preview-img" src="#" alt="Preview">
                    <small class="text-muted d-block mt-1">JPEG, PNG, GIF or WebP � max 2 MB � leave empty to keep current</small>
                </div>

                <div class="section-divider">Change Password</div>

                <div class="form-group-custom">
                    <label for="password"><i class="bi bi-lock"></i> New Password <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#aaa;">(optional)</span></label>
                    <div class="password-wrapper">
                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            placeholder="Leave blank to keep current password"
                            autocomplete="new-password"
                        >
                        <button type="button" class="password-toggle" data-target="password" aria-label="Toggle password visibility">
                            <i class="bi bi-eye" id="toggle-icon-password"></i>
                        </button>
                    </div>
                    <div class="password-hint">
                        <i class="bi bi-info-circle"></i>
                        <span>Min 10 characters � uppercase &amp; lowercase � digit � special character (!@#$%^&amp;*)</span>
                    </div>
                </div>

                <div class="form-group-custom">
                    <label for="password_confirmation"><i class="bi bi-lock-fill"></i> Confirm New Password</label>
                    <div class="password-wrapper">
                        <input
                            type="password"
                            class="form-control"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Repeat new password"
                            autocomplete="new-password"
                        >
                        <button type="button" class="password-toggle" data-target="password_confirmation" aria-label="Toggle password visibility">
                            <i class="bi bi-eye" id="toggle-icon-password_confirmation"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-save mt-2">
                    <i class="bi bi-check-circle me-2"></i> Save Changes
                </button>
            </form>
        </div>

        <div class="profile-footer">
            <p><a href="/"><i class="bi bi-arrow-left"></i> Back to home</a></p>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.password-toggle').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var target = this.dataset.target;
        var input = document.getElementById(target);
        var icon = document.getElementById('toggle-icon-' + target);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    });
});

document.getElementById('profile_image').addEventListener('change', function() {
    var preview = document.getElementById('imagePreview');
    if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(this.files[0]);
    } else {
        preview.style.display = 'none';
    }
});
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
