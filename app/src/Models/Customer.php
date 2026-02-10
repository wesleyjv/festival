<?php
// Customer.php

namespace App\Models;

/**
 * Customer that can place orders and hold a shopping cart.
 */
class Customer extends User
{
    public ?string $profilePicture = null;

    public function register(): void
    {
        // Registration logic is implemented in a service/repository.
    }

    public function updateProfile(): void
    {
        // Profile update logic placeholder.
    }

    public function getPersonalProgram(): array
    {
        // Returns sessions or events tailored to this customer.
        return [];
    }
}

