<?php

namespace App\Controllers;

class AuthController
{
    use HandlesControllerErrors;

    public function login($vars = [])
    {
        try {
            require __DIR__ . '/../views/auth/login.php';
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    public function register($vars = [])
    {
        try {
            require __DIR__ . '/../views/auth/register.php';
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }
}