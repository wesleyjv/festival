<?php

namespace App\Services;

class Validator
{
    private array $errors = [];

    public function validateRequired(string $value, string $fieldName): self
    {
        if (empty(trim($value))) {
            $this->errors[] = "$fieldName is required.";
        }
        return $this;
    }

    public function validateEmail(string $email): self
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Please enter a valid email address.";
        }
        return $this;
    }

    public function validatePasswordStrength(string $password): self
    {
        if (strlen($password) < 10) {
            $this->errors[] = "Password must be at least 10 characters long.";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $this->errors[] = "Password must contain at least one uppercase letter.";
        }
        if (!preg_match('/[a-z]/', $password)) {
            $this->errors[] = "Password must contain at least one lowercase letter.";
        }
        if (!preg_match('/[0-9]/', $password)) {
            $this->errors[] = "Password must contain at least one digit.";
        }
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $this->errors[] = "Password must contain at least one special character (!@#$%^&* etc.).";
        }
        if (preg_match('/(.)\1{2,}/', $password)) {
            $this->errors[] = "Password must not contain 3 or more repeating characters in a row.";
        }
        return $this;
    }

    public function validatePasswordConfirmation(string $password, string $confirmation): self
    {
        if ($password !== $confirmation) {
            $this->errors[] = "Passwords do not match.";
        }
        return $this;
    }

    public function validateRole(string $role, array $allowed): self
    {
        if (!in_array($role, $allowed, true)) {
            $this->errors[] = 'Invalid role selected.';
        }
        return $this;
    }

    public function validateNameLength(string $name, int $min = 2, int $max = 255): self
    {
        $length = strlen(trim($name));
        if ($length < $min || $length > $max) {
            $this->errors[] = "Name must be between $min and $max characters.";
        }
        return $this;
    }

    public function validateRecaptcha(string $recaptchaResponse, string $secretKey): self
    {
        if (empty($recaptchaResponse)) {
            $this->errors[] = "Please complete the CAPTCHA verification.";
            return $this;
        }

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => $secretKey,
            'response' => $recaptchaResponse,
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ],
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result === false) {
            $this->errors[] = "CAPTCHA verification failed. Please try again.";
            return $this;
        }

        $json = json_decode($result, true);
        if (empty($json['success'])) {
            $this->errors[] = "CAPTCHA verification failed. Please try again.";
        }

        return $this;
    }

    public function addError(string $message): self
    {
        $this->errors[] = $message;
        return $this;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
