<?php

namespace App\Models;

class User
{
    public int $id;
    public string $name;
    public string $email;
    public string $passwordHash;
    public string $role;
    public ?string $profileImage;
    public ?string $createdAt;

    public function __construct(
        int $id,
        string $name,
        string $email,
        string $passwordHash,
        string $role = 'customer',
        ?string $profileImage = null,
        ?string $createdAt = null
    ) {
        $this->id           = $id;
        $this->name         = $name;
        $this->email        = $email;
        $this->passwordHash = $passwordHash;
        $this->role         = $role;
        $this->profileImage = $profileImage;
        $this->createdAt    = $createdAt;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}

