<?php

namespace App\Repositories;

use App\Database;
use App\Models\User;
use PDO;

class UserRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new User($row['id'], $row['name'], $row['email'], $row['password_hash'], $row['role']);
    }

    public function findByName(string $name): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE name = :name LIMIT 1');
        $stmt->execute(['name' => $name]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new User($row['id'], $row['name'], $row['email'], $row['password_hash'], $row['role']);
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function nameExists(string $name): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE name = :name');
        $stmt->execute(['name' => $name]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function create(string $name, string $email, string $passwordHash): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (name, email, password_hash, role) VALUES (:name, :email, :password_hash, :role)'
        );
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password_hash' => $passwordHash,
            'role' => 'customer',
        ]);

        return (int) $this->db->lastInsertId();
    }
}
