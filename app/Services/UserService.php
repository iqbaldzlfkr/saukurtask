<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Validators\UserValidator;

/**
 * UserService — Business logic for user management.
 */
class UserService
{
    private UserRepository $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
    }

    public function getAll(string $search = ''): array
    {
        return $this->userRepo->findAll($search);
    }

    public function getById(int $id): ?array
    {
        return $this->userRepo->findById($id);
    }

    /**
     * Create a new user with validation.
     *
     * @return array ['success' => bool, 'errors' => array, 'id' => int]
     */
    public function create(array $data): array
    {
        $emailExists = $this->userRepo->emailExists($data['email'] ?? '');
        $errors      = UserValidator::validateCreate($data, $emailExists);

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $id = $this->userRepo->create([
            'name'      => trim($data['name']),
            'email'     => strtolower(trim($data['email'])),
            'password'  => password_hash($data['password'], PASSWORD_BCRYPT),
            'role'      => $data['role'],
            'is_active' => 1,
        ]);

        return ['success' => true, 'errors' => [], 'id' => $id];
    }

    /**
     * Update a user with validation.
     *
     * @return array ['success' => bool, 'errors' => array]
     */
    public function update(int $id, array $data): array
    {
        $emailExists = $this->userRepo->emailExists($data['email'] ?? '', $id);
        $errors      = UserValidator::validateUpdate($data, $emailExists);

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $updateData = [
            'name'      => trim($data['name']),
            'email'     => strtolower(trim($data['email'])),
            'role'      => $data['role'],
            'is_active' => (int) ($data['is_active'] ?? 1),
            'password'  => !empty($data['password'])
                           ? password_hash($data['password'], PASSWORD_BCRYPT)
                           : '',
        ];

        $this->userRepo->update($id, $updateData);

        return ['success' => true, 'errors' => []];
    }

    /**
     * Toggle the active status of a user.
     */
    public function toggleActive(int $id): bool
    {
        return $this->userRepo->toggleActive($id);
    }
}
