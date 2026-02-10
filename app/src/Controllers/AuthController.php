<?php

namespace App\Controllers;

class AuthController
{
    public function login($vars = [])
    {
        require __DIR__ . '/../views/auth/login.php';
    }

    public function register($vars = [])
    {
        require __DIR__ . '/../views/auth/register.php';
    }
}