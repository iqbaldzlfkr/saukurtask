<?php

namespace App\Validators;

/**
 * UserValidator — Validates user input for user management.
 *
 * Returns an array of error messages (empty = valid).
 */
class UserValidator
{
    public static function validateCreate(array $data, bool $emailExists): array
    {
        $errors = [];

        if (empty(trim($data['name'] ?? ''))) {
            $errors['name'] = 'Name is required.';
        } elseif (strlen($data['name']) > 100) {
            $errors['name'] = 'Name must not exceed 100 characters.';
        }

        if (empty(trim($data['email'] ?? ''))) {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        } elseif ($emailExists) {
            $errors['email'] = 'This email address is already in use.';
        }

        if (empty($data['password'] ?? '')) {
            $errors['password'] = 'Password is required.';
        } elseif (strlen($data['password']) < 8) {
            $errors['password'] = 'Password must be at least 8 characters.';
        }

        if (!in_array($data['role'] ?? '', ['Admin', 'Member'], true)) {
            $errors['role'] = 'Role must be Admin or Member.';
        }

        return $errors;
    }

    public static function validateUpdate(array $data, bool $emailExists): array
    {
        $errors = [];

        if (empty(trim($data['name'] ?? ''))) {
            $errors['name'] = 'Name is required.';
        } elseif (strlen($data['name']) > 100) {
            $errors['name'] = 'Name must not exceed 100 characters.';
        }

        if (empty(trim($data['email'] ?? ''))) {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        } elseif ($emailExists) {
            $errors['email'] = 'This email address is already in use.';
        }

        // Password is optional on update — only validate if provided
        if (!empty($data['password'])) {
            if (strlen($data['password']) < 8) {
                $errors['password'] = 'Password must be at least 8 characters.';
            }
        }

        if (!in_array($data['role'] ?? '', ['Admin', 'Member'], true)) {
            $errors['role'] = 'Role must be Admin or Member.';
        }

        return $errors;
    }
}
