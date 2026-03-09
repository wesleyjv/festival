<?php
// User.php
namespace App\Models;

/**
 * Base user of the system.
 */
class User
{
    public int $id;
    public string $name;
    public string $email;
    public string $passwordHash;
    public string $role;
    public ?string $profileImage;

    public function __construct(int $id, string $name, string $email, string $passwordHash, string $role = 'customer', ?string $profileImage = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->role = $role;
        $this->profileImage = $profileImage;
    }

    public function login(): void
    {
        // Authentication handled elsewhere; this is a domain placeholder.
    }

    public function logout(): void
    {
        // Session termination handled by infrastructure.
    }
}

