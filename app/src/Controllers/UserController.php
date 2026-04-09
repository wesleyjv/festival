<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Security\Csrf;
use App\Services\ImageUploadService;
use App\Services\MailService;
use App\Services\Validator;

class UserController
{
    private const RECAPTCHA_SITE_KEY   = '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI';
    private const RECAPTCHA_SECRET_KEY = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';

    private UserRepository $users;

    public function __construct()
    {
        $this->users = new UserRepository();
    }

    // -------------------------------------------------------------------------
    // Registration
    // -------------------------------------------------------------------------

    public function register($vars = [])
    {
        $errors           = $_SESSION['register_errors'] ?? [];
        $old              = $_SESSION['register_old']    ?? [];
        $recaptchaSiteKey = self::RECAPTCHA_SITE_KEY;

        unset($_SESSION['register_errors'], $_SESSION['register_old']);

        require __DIR__ . '/../views/auth/register.php';
    }

    public function handleRegister($vars = [])
    {
        if (!Csrf::validateRequest()) {
            $_SESSION['register_errors'] = ['Invalid session. Please try again.'];
            header('Location: /register');
            exit;
        }

        $name                 = trim($_POST['name']                 ?? '');
        $email                = trim($_POST['email']                ?? '');
        $password             =      $_POST['password']             ?? '';
        $passwordConfirmation =      $_POST['password_confirmation'] ?? '';
        $recaptchaResponse    =      $_POST['g-recaptcha-response'] ?? '';

        $validator = new Validator();
        $validator
            ->validateRequired($name, 'Name')
            ->validateNameLength($name)
            ->validateRequired($email, 'Email')
            ->validateEmail($email)
            ->validateRequired($password, 'Password')
            ->validatePasswordStrength($password)
            ->validatePasswordConfirmation($password, $passwordConfirmation)
            ->validateRecaptcha($recaptchaResponse, self::RECAPTCHA_SECRET_KEY);

        if (!$validator->hasErrors()) {
            if ($this->users->emailExists($email)) {
                $validator->addError('An account with this email address already exists.');
            }
            if ($this->users->nameExists($name)) {
                $validator->addError('This username is already taken.');
            }
        }

        if ($validator->hasErrors()) {
            $_SESSION['register_errors'] = $validator->getErrors();
            $_SESSION['register_old']    = ['name' => $name, 'email' => $email];
            header('Location: /register');
            exit;
        }

        $this->users->create($name, $email, $password);

        $_SESSION['register_success'] = 'Account created successfully! You can now log in.';
        header('Location: /login');
        exit;
    }

    // -------------------------------------------------------------------------
    // Login / Logout
    // -------------------------------------------------------------------------

    public function login($vars = [])
    {
        $errors  = $_SESSION['login_errors']     ?? [];
        $old     = $_SESSION['login_old']        ?? [];
        $success = $_SESSION['register_success'] ?? $_SESSION['login_success'] ?? '';

        unset($_SESSION['login_errors'], $_SESSION['login_old'], $_SESSION['register_success'], $_SESSION['login_success']);

        require __DIR__ . '/../views/auth/login.php';
    }

    public function handleLogin($vars = [])
    {
        if (!Csrf::validateRequest()) {
            $_SESSION['login_errors'] = ['Invalid session. Please try again.'];
            header('Location: /login');
            exit;
        }

        $identity = trim($_POST['identity'] ?? '');
        $password =      $_POST['password'] ?? '';

        $validator = new Validator();
        $validator
            ->validateRequired($identity, 'Username or Email')
            ->validateRequired($password, 'Password');

        if ($validator->hasErrors()) {
            $_SESSION['login_errors'] = $validator->getErrors();
            $_SESSION['login_old']    = ['identity' => $identity];
            header('Location: /login');
            exit;
        }

        $user  = str_contains($identity, '@')
            ? $this->users->findByEmail($identity)
            : $this->users->findByName($identity);

        if (!$user || !password_verify($password, $user->passwordHash)) {
            $_SESSION['login_errors'] = ['Invalid username/email or password.'];
            $_SESSION['login_old']    = ['identity' => $identity];
            header('Location: /login');
            exit;
        }

        session_regenerate_id(true);
        unset($_SESSION['cart']);

        $_SESSION['user_id']            = $user->id;
        $_SESSION['user_name']          = $user->name;
        $_SESSION['user_email']         = $user->email;
        $_SESSION['user_role']          = $user->role;
        $_SESSION['user_profile_image'] = $user->profileImage;

        if (!empty($_POST['remember'])) {
            $this->setRememberMeCookies($user->id, $this->users);
        }

        header('Location: ' . ($user->isAdmin() ? '/admin' : '/'));
        exit;
    }

    public function logout($vars = [])
    {
        if (!empty($_SESSION['user_id'])) {
            $this->users->clearRememberToken($_SESSION['user_id']);
        }

        $this->clearRememberMeCookies();

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'], $params['secure'], $params['httponly']
            );
        }

        session_destroy();

        header('Location: /');
        exit;
    }

    // -------------------------------------------------------------------------
    // Password reset (email link)
    // -------------------------------------------------------------------------

    public function forgotPassword($vars = [])
    {
        $errors  = $_SESSION['forgot_password_errors']  ?? [];
        $success = $_SESSION['forgot_password_success'] ?? '';
        $old     = $_SESSION['forgot_password_old']     ?? [];
        $smtpConsoleLog = $_SESSION['forgot_password_smtp_console'] ?? null;

        unset(
            $_SESSION['forgot_password_errors'],
            $_SESSION['forgot_password_success'],
            $_SESSION['forgot_password_old'],
            $_SESSION['forgot_password_smtp_console'],
        );

        require __DIR__ . '/../views/auth/forgot-password.php';
    }

    public function handleForgotPassword($vars = [])
    {
        if (!Csrf::validateRequest()) {
            $_SESSION['forgot_password_errors'] = ['Invalid session. Please try again.'];
            header('Location: /forgot-password');
            exit;
        }

        $email = trim($_POST['email'] ?? '');

        $validator = new Validator();
        $validator
            ->validateRequired($email, 'Email')
            ->validateEmail($email);

        if ($validator->hasErrors()) {
            $_SESSION['forgot_password_errors'] = $validator->getErrors();
            $_SESSION['forgot_password_old']    = ['email' => $email];
            header('Location: /forgot-password');
            exit;
        }

        $user = $this->users->findByEmail($email);
        if ($user) {
            $token     = bin2hex(random_bytes(32));
            $tokenHash = hash('sha256', $token);
            $expires   = date('Y-m-d H:i:s', time() + 3600);
            $this->users->setPasswordReset($user->id, $tokenHash, $expires);

            $resetUrl    = $this->appBaseUrl() . '/reset-password?token=' . rawurlencode($token);
            $mailService = new MailService();
            $sent        = $mailService->sendPasswordResetEmail($user->email, $user->name, $resetUrl);

            if (!$sent) {
                $this->users->clearPasswordReset($user->id);
                $errors = ['We could not send the email. Please try again later.'];
                if ($this->isAppDebug()) {
                    $detail = $mailService->getLastSendError();
                    $dbg    = $mailService->getLastSendDebug();
                    if ($detail !== null && $detail !== '') {
                        $tech = 'Technical detail (APP_DEBUG): ' . $detail;
                        if (!empty($dbg['exceptionThrownIn'])) {
                            $tech .= ' [exception thrown in ' . $dbg['exceptionThrownIn'] . ']';
                        }
                        if (!empty($dbg['handledInMailService'])) {
                            $tech .= ' [handled in ' . $dbg['handledInMailService'] . ']';
                        }
                        if (!empty($dbg['calledFrom'])) {
                            $tech .= ' [app caller ' . $dbg['calledFrom'] . ']';
                        }
                        $errors[] = $tech;
                    }
                    $encEnv = trim((string) (getenv('MAIL_ENCRYPTION') ?: ''));
                    $_SESSION['forgot_password_smtp_console'] = array_merge(
                        [
                            'MAIL_HOST'         => getenv('MAIL_HOST') ?: '(default smtp.gmail.com)',
                            'MAIL_PORT'         => getenv('MAIL_PORT') ?: '(default 587)',
                            'MAIL_ENCRYPTION'   => $encEnv !== '' ? $encEnv : '(auto by port)',
                            'MAIL_USERNAME_set' => trim((string) getenv('MAIL_USERNAME')) !== '',
                        ],
                        $dbg ?? []
                    );
                }
                $_SESSION['forgot_password_errors'] = $errors;
                $_SESSION['forgot_password_old']    = ['email' => $email];
                header('Location: /forgot-password');
                exit;
            }
        }

        $_SESSION['forgot_password_success'] =
            'If an account exists for that email address, we sent a link to reset your password. Check your inbox.';
        header('Location: /forgot-password');
        exit;
    }

    public function resetPassword($vars = [])
    {
        $token  = trim($_GET['token'] ?? '');
        $errors = $_SESSION['reset_password_errors'] ?? [];
        unset($_SESSION['reset_password_errors']);

        $resetToken = null;
        if ($token !== '') {
            $user = $this->users->findByValidPasswordResetToken(hash('sha256', $token));
            if ($user) {
                $resetToken = $token;
            } else {
                $errors[] = 'This reset link is invalid or has expired. Please request a new one.';
            }
        }

        require __DIR__ . '/../views/auth/reset-password.php';
    }

    public function handleResetPassword($vars = [])
    {
        if (!Csrf::validateRequest()) {
            $_SESSION['reset_password_errors'] = ['Invalid session. Please try again.'];
            header('Location: /reset-password');
            exit;
        }

        $token                = trim($_POST['token'] ?? '');
        $password             = $_POST['password'] ?? '';
        $passwordConfirmation = $_POST['password_confirmation'] ?? '';

        $validator = new Validator();
        $validator
            ->validateRequired($token, 'Reset link')
            ->validateRequired($password, 'Password')
            ->validatePasswordStrength($password)
            ->validatePasswordConfirmation($password, $passwordConfirmation);

        if ($validator->hasErrors()) {
            $_SESSION['reset_password_errors'] = $validator->getErrors();
            header('Location: /reset-password?token=' . rawurlencode($token));
            exit;
        }

        $user = $this->users->findByValidPasswordResetToken(hash('sha256', $token));
        if (!$user) {
            $_SESSION['reset_password_errors'] = ['This reset link is invalid or has expired. Please request a new one.'];
            header('Location: /reset-password');
            exit;
        }

        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $this->users->updatePasswordHashAndClearReset($user->id, $hash);
        $this->users->clearRememberToken($user->id);

        $_SESSION['login_success'] = 'Your password has been reset. You can log in with your new password.';
        header('Location: /login');
        exit;
    }

    // -------------------------------------------------------------------------
    // Profile
    // -------------------------------------------------------------------------

    public function profile($vars = [])
    {
        $this->requireAuth();

        $user = $this->users->findById((int) $_SESSION['user_id']);

        $_SESSION['user_profile_image'] = $user->profileImage;

        $errors  = $_SESSION['profile_errors']  ?? [];
        $success = $_SESSION['profile_success'] ?? '';
        $old     = $_SESSION['profile_old']     ?? [];

        unset($_SESSION['profile_errors'], $_SESSION['profile_success'], $_SESSION['profile_old']);

        require __DIR__ . '/../views/profile/edit.php';
    }

    public function handleUpdateProfile($vars = [])
    {
        $this->requireAuth();

        if (!Csrf::validateRequest()) {
            $_SESSION['profile_errors'] = ['Invalid session. Please try again.'];
            header('Location: /profile');
            exit;
        }

        $userId               = (int) $_SESSION['user_id'];
        $name                 = trim($_POST['name']                  ?? '');
        $email                = trim($_POST['email']                 ?? '');
        $password             =      $_POST['password']              ?? '';
        $passwordConfirmation =      $_POST['password_confirmation'] ?? '';

        $validator = new Validator();
        $validator
            ->validateRequired($name, 'Name')
            ->validateNameLength($name)
            ->validateRequired($email, 'Email')
            ->validateEmail($email);

        if (!empty($password)) {
            $validator
                ->validatePasswordStrength($password)
                ->validatePasswordConfirmation($password, $passwordConfirmation);
        }

        if (!$validator->hasErrors()) {
            if ($this->users->emailExistsForOtherUser($email, $userId)) {
                $validator->addError('An account with this email address already exists.');
            }
            if ($this->users->nameExistsForOtherUser($name, $userId)) {
                $validator->addError('This username is already taken.');
            }
        }

        $profileImagePath = null;
        if (!empty($_FILES['profile_image']['name'])) {
            try {
                $profileImagePath = (new ImageUploadService())->upload($_FILES['profile_image'], 'profile_' . $userId);
            } catch (\Exception $e) {
                $validator->addError($e->getMessage());
            }
        }

        if ($validator->hasErrors()) {
            $_SESSION['profile_errors'] = $validator->getErrors();
            $_SESSION['profile_old']    = ['name' => $name, 'email' => $email];
            header('Location: /profile');
            exit;
        }

        $this->users->updateProfile($userId, $name, $email, !empty($password) ? $password : null, $profileImagePath);
        //Make sure to verify upload content (for security)
        $_SESSION['user_name']  = $name;
        $_SESSION['user_email'] = $email;
        if ($profileImagePath !== null) {
            $_SESSION['user_profile_image'] = $profileImagePath;
        }

        (new MailService())->sendProfileUpdateEmail($email, $name);

        $_SESSION['profile_success'] = 'Your profile has been updated successfully.';
        header('Location: /profile');
        exit;
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    private function requireAuth(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    private function appBaseUrl(): string
    {
        $fromEnv = getenv('APP_URL');
        if (is_string($fromEnv) && $fromEnv !== '') {
            return rtrim($fromEnv, '/');
        }

        $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        $host  = $_SERVER['HTTP_HOST'] ?? 'localhost';

        return ($https ? 'https://' : 'http://') . $host;
    }

    private function isAppDebug(): bool
    {
        $v = strtolower((string) getenv('APP_DEBUG'));

        return in_array($v, ['1', 'true', 'yes'], true);
    }

    private function setRememberMeCookies(int $userId, UserRepository $users): void
    {
        $token     = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);

        $users->saveRememberToken($userId, $tokenHash);

        $options = [
            'expires'  => time() + (30 * 24 * 60 * 60), // 30 days
            'path'     => '/',
            'domain'   => '',
            'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
            'httponly' => true,
            'samesite' => 'Lax',
        ];

        setcookie('remember_token', $token,           $options);
        setcookie('remember_user',  (string) $userId, $options);
    }

    private function clearRememberMeCookies(): void
    {
        $options = [
            'expires'  => time() - 42000,
            'path'     => '/',
            'domain'   => '',
            'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
            'httponly' => true,
            'samesite' => 'Lax',
        ];

        setcookie('remember_token', '', $options);
        setcookie('remember_user',  '', $options);
    }
}
