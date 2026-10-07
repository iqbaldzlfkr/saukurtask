<?php

namespace App\Models;

/**
 * User — Simple data model (value object).
 * No database logic here — see UserRepository.
 */
class User
{
    public function __construct(
        public readonly int    $id,
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $role,
        public readonly bool   $isActive,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    ) {}

    /**
     * Create a User instance from a database row array.
     */
    public static function fromArray(array $row): self
    {
        return new self(
            id:        (int)  $row['id'],
            name:            $row['name'],
            email:           $row['email'],
            password:        $row['password'],
            role:            $row['role'],
            isActive:  (bool) $row['is_active'],
            createdAt:       $row['created_at'],
            updatedAt:       $row['updated_at'],
        );
    }

    public function isAdmin(): bool
    {
        return $this->role === 'Admin';
    }

    public function isMember(): bool
    {
        return $this->role === 'Member';
    }
}
