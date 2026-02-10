<?php
// Admin.php

namespace App\Models;

class Admin extends User
{
    public function manageUsers(): void
    {
        // Admin-specific user management logic.
    }

    public function manageEvents(): void
    {
        // Admin-specific event management logic.
    }
}
