<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Services\Validator;
use App\Services\MailService;

class UserController
{
    private const RECAPTCHA_SITE_KEY = '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI';
    private const RECAPTCHA_SECRET_KEY = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';

    public function register($vars = [])
    {
        $errors = $_SESSION['register_errors'] ?? [];
        $old = $_SESSION['register_old'] ?? [];
        unset($_SESSION['register_errors'], $_SESSION['register_old']);
        //Make utility of this
        $recaptchaSiteKey = self::RECAPTCHA_SITE_KEY;
        require __DIR__ . '/../views/auth/register.php';
    }

    public function handleRegister($vars = [])
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirmation = $_POST['password_confirmation'] ?? '';
        $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

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

        $userRepository = new UserRepository();

        if (!$validator->hasErrors()) {
            if ($userRepository->emailExists($email)) {
                $validator->addError('An account with this email address already exists.');
            }
            if ($userRepository->nameExists($name)) {
                $validator->addError('This username is already taken.');
            }
        }

        if ($validator->hasErrors()) {
            $_SESSION['register_errors'] = $validator->getErrors();
            $_SESSION['register_old'] = ['name' => $name, 'email' => $email];
            header('Location: /register');
            exit;
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $userRepository->create($name, $email, $passwordHash);

        $_SESSION['register_success'] = 'Account created successfully! You can now log in.';
        header('Location: /login');
        exit;
    }

    public function login($vars = [])
    {
        $errors = $_SESSION['login_errors'] ?? [];
        $old = $_SESSION['login_old'] ?? [];
        $success = $_SESSION['register_success'] ?? '';
        unset($_SESSION['login_errors'], $_SESSION['login_old'], $_SESSION['register_success']);

        require __DIR__ . '/../views/auth/login.php';
    }

    public function handleLogin($vars = [])
    {
        $identity = trim($_POST['identity'] ?? '');
        $password = $_POST['password'] ?? '';

        $validator = new Validator();
        $validator
            ->validateRequired($identity, 'Username or Email')
            ->validateRequired($password, 'Password');

        if ($validator->hasErrors()) {
            $_SESSION['login_errors'] = $validator->getErrors();
            $_SESSION['login_old'] = ['identity' => $identity];
            header('Location: /login');
            exit;
        }
        //Use PHP hashing
        $userRepository = new UserRepository();

        // Determine if the identity is an email or username
        if (str_contains($identity, '@')) {
            $user = $userRepository->findByEmail($identity);
        } else {
            $user = $userRepository->findByName($identity);
        }

        if (!$user || !password_verify($password, $user->passwordHash)) {
            $_SESSION['login_errors'] = ['Invalid username/email or password.'];
            $_SESSION['login_old'] = ['identity' => $identity];
            header('Location: /login');
            exit;
        }

        // Regenerate session ID to prevent session fixation attacks
        session_regenerate_id(true);

        // Clear cart from previous session so it doesn't carry over
        unset($_SESSION['cart']);

        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_role'] = $user->role;

        // Handle "Remember me" cookie
        $remember = $_POST['remember'] ?? '';
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $tokenHash = hash('sha256', $token);
            $userRepository->saveRememberToken($user->id, $tokenHash);

            $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
            setcookie('remember_token', $token, [
                'expires' => time() + (30 * 24 * 60 * 60), // 30 days
                'path' => '/',
                'domain' => '',
                'secure' => $isSecure,
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            setcookie('remember_user', (string) $user->id, [
                'expires' => time() + (30 * 24 * 60 * 60),
                'path' => '/',
                'domain' => '',
                'secure' => $isSecure,
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
        }

        // Redirect admins to the admin dashboard, everyone else to homepage
        if ($user->role === 'admin') {
            header('Location: /admin');
        } else {
            header('Location: /');
        }
        exit;
    }

    public function logout($vars = [])
    {
        // Clear remember me token from database
        if (!empty($_SESSION['user_id'])) {
            $userRepository = new UserRepository();
            $userRepository->clearRememberToken($_SESSION['user_id']);
        }

        // Clear remember me cookies
        $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
        setcookie('remember_token', '', [
            'expires' => time() - 42000,
            'path' => '/',
            'domain' => '',
            'secure' => $isSecure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        setcookie('remember_user', '', [
            'expires' => time() - 42000,
            'path' => '/',
            'domain' => '',
            'secure' => $isSecure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        // Clear all session data
        $_SESSION = [];

        // Delete the session cookie
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        header('Location: /');
        exit;
    }

    public function profile($vars = [])
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userRepository = new UserRepository();
        $user = $userRepository->findById((int) $_SESSION['user_id']);

        $errors = $_SESSION['profile_errors'] ?? [];
        $success = $_SESSION['profile_success'] ?? '';
        $old = $_SESSION['profile_old'] ?? [];
        unset($_SESSION['profile_errors'], $_SESSION['profile_success'], $_SESSION['profile_old']);

        require __DIR__ . '/../views/profile/edit.php';
    }

    public function handleUpdateProfile($vars = [])
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userId = (int) $_SESSION['user_id'];
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirmation = $_POST['password_confirmation'] ?? '';

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

        $userRepository = new UserRepository();

        if (!$validator->hasErrors()) {
            if ($userRepository->emailExistsForOtherUser($email, $userId)) {
                $validator->addError('An account with this email address already exists.');
            }
            if ($userRepository->nameExistsForOtherUser($name, $userId)) {
                $validator->addError('This username is already taken.');
            }
        }

        // Handle profile image upload
        $profileImagePath = null;
        if (!empty($_FILES['profile_image']['name'])) {
            $file = $_FILES['profile_image'];
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($file['tmp_name']);

            if (!in_array($mimeType, $allowedMimes, true)) {
                $validator->addError('Profile picture must be a JPEG, PNG, GIF, or WebP image.');
            } elseif ($file['size'] > 2 * 1024 * 1024) {
                $validator->addError('Profile picture must be smaller than 2MB.');
            } else {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'profile_' . $userId . '_' . time() . '.' . $ext;
                $uploadDir = __DIR__ . '/../../public/uploads/profiles/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                    $profileImagePath = '/uploads/profiles/' . $filename;
                } else {
                    $validator->addError('Failed to upload profile picture. Please try again.');
                }
            }
        }

        if ($validator->hasErrors()) {
            $_SESSION['profile_errors'] = $validator->getErrors();
            $_SESSION['profile_old'] = ['name' => $name, 'email' => $email];
            header('Location: /profile');
            exit;
        }

        $passwordHash = !empty($password) ? password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]) : null;
        $userRepository->updateProfile($userId, $name, $email, $passwordHash, $profileImagePath);

        // Refresh session data
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;

        // Send confirmation email (best-effort)
        $mailService = new MailService();
        $mailService->sendProfileUpdateEmail($email, $name);

        $_SESSION['profile_success'] = 'Your profile has been updated successfully.';
        header('Location: /profile');
        exit;
    }
}
