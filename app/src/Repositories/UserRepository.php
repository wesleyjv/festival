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

        return new User($row['id'], $row['name'], $row['email'], $row['password_hash'], $row['role'], $row['profile_image'] ?? null, $row['created_at'] ?? null);
    }

    public function findByName(string $name): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE name = :name LIMIT 1');
        $stmt->execute(['name' => $name]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new User($row['id'], $row['name'], $row['email'], $row['password_hash'], $row['role'], $row['profile_image'] ?? null, $row['created_at'] ?? null);
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

    public function create(string $name, string $email, string $password): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (name, email, password_hash, role) VALUES (:name, :email, :password_hash, :role)'
        );
        $stmt->execute([
            'name'          => $name,
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]),
            'role'          => 'customer',
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

        return new User($row['id'], $row['name'], $row['email'], $row['password_hash'], $row['role'], $row['profile_image'] ?? null, $row['created_at'] ?? null);
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

        return new User($row['id'], $row['name'], $row['email'], $row['password_hash'], $row['role'], $row['profile_image'] ?? null, $row['created_at'] ?? null);
    }

    public function getRememberToken(int $userId): ?string
    {
        $stmt = $this->db->prepare('SELECT remember_token FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $userId]);
        $token = $stmt->fetchColumn();

        return $token ?: null;
    }

    public function updateProfile(int $id, string $name, string $email, ?string $password, ?string $profileImage): void
    {
        $fields = ['name = :name', 'email = :email'];
        $params = ['name' => $name, 'email' => $email, 'id' => $id];

        if ($password !== null) {
            $fields[]                = 'password_hash = :password_hash';
            $params['password_hash'] = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        }

        if ($profileImage !== null) {
            $fields[]               = 'profile_image = :profile_image';
            $params['profile_image'] = $profileImage;
        }

        $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $this->db->prepare($sql)->execute($params);
    }

    public function emailExistsForOtherUser(string $email, int $excludeId): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE email = :email AND id != :id');
        $stmt->execute(['email' => $email, 'id' => $excludeId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function nameExistsForOtherUser(string $name, int $excludeId): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE name = :name AND id != :id');
        $stmt->execute(['name' => $name, 'id' => $excludeId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function countAll(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }

    public function getAllUsers(string $search = '', string $role = '', string $sort = 'id', string $dir = 'ASC'): array
    {
        $allowedSorts = ['id', 'name', 'email', 'role', 'created_at'];
        $sort = in_array($sort, $allowedSorts, true) ? $sort : 'id';
        $dir  = strtoupper($dir) === 'DESC' ? 'DESC' : 'ASC';

        $where  = [];
        $params = [];

        if ($search !== '') {
            $where[]             = '(name LIKE :search_name OR email LIKE :search_email)';
            $params['search_name']  = '%' . $search . '%';
            $params['search_email'] = '%' . $search . '%';
        }

        if ($role !== '') {
            $where[]       = 'role = :role';
            $params['role'] = $role;
        }

        $sql = 'SELECT * FROM users';
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= " ORDER BY {$sort} {$dir}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        return array_map(fn($row) => new User(
            $row['id'],
            $row['name'],
            $row['email'],
            $row['password_hash'],
            $row['role'],
            $row['profile_image'] ?? null,
            $row['created_at'] ?? null
        ), $rows);
    }

    public function deleteById(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function adminCreateUser(string $name, string $email, string $password, string $role): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (name, email, password_hash, role) VALUES (:name, :email, :password_hash, :role)'
        );
        $stmt->execute([
            'name'          => $name,
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]),
            'role'          => $role,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function adminUpdateUser(int $id, string $name, string $email, string $role): void
    {
        $stmt = $this->db->prepare(
            'UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id'
        );
        $stmt->execute(['name' => $name, 'email' => $email, 'role' => $role, 'id' => $id]);
    }

    public function setPasswordReset(int $userId, string $tokenHash, string $expiresAt): void
    {
        $stmt = $this->db->prepare(
            'UPDATE users SET password_reset_token = :token, password_reset_expires_at = :expires WHERE id = :id'
        );
        $stmt->execute(['token' => $tokenHash, 'expires' => $expiresAt, 'id' => $userId]);
    }

    public function clearPasswordReset(int $userId): void
    {
        $stmt = $this->db->prepare(
            'UPDATE users SET password_reset_token = NULL, password_reset_expires_at = NULL WHERE id = :id'
        );
        $stmt->execute(['id' => $userId]);
    }

    public function findByValidPasswordResetToken(string $tokenHash): ?User
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM users WHERE password_reset_token = :token
             AND password_reset_expires_at IS NOT NULL
             AND password_reset_expires_at > NOW()
             LIMIT 1'
        );
        $stmt->execute(['token' => $tokenHash]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new User($row['id'], $row['name'], $row['email'], $row['password_hash'], $row['role'], $row['profile_image'] ?? null, $row['created_at'] ?? null);
    }

    public function updatePasswordHashAndClearReset(int $userId, string $passwordHash): void
    {
        $stmt = $this->db->prepare(
            'UPDATE users SET password_hash = :password_hash, password_reset_token = NULL, password_reset_expires_at = NULL WHERE id = :id'
        );
        $stmt->execute(['password_hash' => $passwordHash, 'id' => $userId]);
    }
}
