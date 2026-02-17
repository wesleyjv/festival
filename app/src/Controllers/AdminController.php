<?php

namespace App\Controllers;

class AdminController
{
    public function dashboard($vars = [])
    {
        if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            header('Location: /login');
            exit;
        }

        require __DIR__ . '/../views/admin/dashboard.php';
    }
}
