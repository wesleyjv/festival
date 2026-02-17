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

    public function findById(int $id): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new User($row['id'], $row['name'], $row['email'], $row['password_hash'], $row['role']);
    }

    public function saveRememberToken(int $userId, string $tokenHash): void
    {
        $stmt = $this->db->prepare('UPDATE users SET remember_token = :token WHERE id = :id');
        $stmt->execute(['token' => $tokenHash, 'id' => $userId]);
    }

    public function clearRememberToken(int $userId): void
    {
        $stmt = $this->db->prepare('UPDATE users SET remember_token = NULL WHERE id = :id');
        $stmt->execute(['id' => $userId]);
    }

    public function findByRememberToken(string $tokenHash): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE remember_token = :token LIMIT 1');
        $stmt->execute(['token' => $tokenHash]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new User($row['id'], $row['name'], $row['email'], $row['password_hash'], $row['role']);
    }

    public function getRememberToken(int $userId): ?string
    {
        $stmt = $this->db->prepare('SELECT remember_token FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $userId]);
        $token = $stmt->fetchColumn();

        return $token ?: null;
    }
}
