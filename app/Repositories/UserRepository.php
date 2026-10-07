<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\User;
use PDO;

/**
 * UserRepository — All database operations for users.
 * Uses PDO prepared statements exclusively.
 */
class UserRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Find a user by email (for login).
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM users WHERE email = :email LIMIT 1'
        );
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Find a user by ID.
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM users WHERE id = :id LIMIT 1'
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Get all users (for Admin user list).
     * Supports optional search by name.
     */
    public function findAll(string $search = ''): array
    {
        if ($search !== '') {
            $stmt = $this->db->prepare(
                'SELECT * FROM users WHERE name LIKE :search ORDER BY created_at DESC'
            );
            $stmt->execute([':search' => '%' . $search . '%']);
        } else {
            $stmt = $this->db->prepare(
                'SELECT * FROM users ORDER BY created_at DESC'
            );
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }

    /**
     * Get all active Members (for task assignment dropdown).
     */
    public function findActiveMembers(): array
    {
        $stmt = $this->db->prepare(
            "SELECT id, name, email FROM users
             WHERE role = 'Member' AND is_active = 1
             ORDER BY name ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Check if an email already exists (for uniqueness validation).
     * Exclude a specific user ID to allow updating own email.
     */
    public function emailExists(string $email, int $excludeId = 0): bool
    {
        $stmt = $this->db->prepare(
            'SELECT id FROM users WHERE email = :email AND id != :exclude LIMIT 1'
        );
        $stmt->execute([':email' => $email, ':exclude' => $excludeId]);
        return (bool) $stmt->fetch();
    }

    /**
     * Create a new user.
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (name, email, password, role, is_active)
             VALUES (:name, :email, :password, :role, :is_active)'
        );
        $stmt->execute([
            ':name'      => $data['name'],
            ':email'     => $data['email'],
            ':password'  => $data['password'],
            ':role'      => $data['role'],
            ':is_active' => $data['is_active'] ?? 1,
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Update an existing user.
     */
    public function update(int $id, array $data): bool
    {
        $fields = ['name = :name', 'email = :email', 'role = :role', 'is_active = :is_active'];
        $params = [
            ':name'      => $data['name'],
            ':email'     => $data['email'],
            ':role'      => $data['role'],
            ':is_active' => $data['is_active'],
            ':id'        => $id,
        ];

        // Only update password if provided
        if (!empty($data['password'])) {
            $fields[] = 'password = :password';
            $params[':password'] = $data['password'];
        }

        $sql  = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Toggle user active status.
     */
    public function toggleActive(int $id): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE users SET is_active = NOT is_active WHERE id = :id'
        );
        return $stmt->execute([':id' => $id]);
    }
}
