<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Services\Validator;

class UserController
{
    private const RECAPTCHA_SITE_KEY = '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI';
    private const RECAPTCHA_SECRET_KEY = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';

    public function register($vars = [])
    {
        $errors = $_SESSION['register_errors'] ?? [];
        $old = $_SESSION['register_old'] ?? [];
        unset($_SESSION['register_errors'], $_SESSION['register_old']);

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
        // Stub — will be made functional in the next step
        header('Location: /login');
        exit;
    }

    public function logout($vars = [])
    {
        // Stub — will be made functional in the next step
        header('Location: /');
        exit;
    }
}
