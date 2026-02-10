<?php

namespace App\Controllers;

class HomeController
{
    public function home($vars = [])
    {
        // Render the homepage view
        require __DIR__ . '/../views/main/homepage.php';
    }
}
