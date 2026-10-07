<?php

namespace App\Core;

/**
 * Auth — Server-side authorization guard.
 *
 * All authorization checks happen here, never trust the frontend.
 */
class Auth
{
    /**
     * Returns true if the user is currently logged in.
     */
    public static function check(): bool
    {
        return Session::has('user_id') && Session::has('user_role');
    }

    /**
     * Returns the currently logged-in user's ID, or null.
     */
    public static function id(): ?int
    {
        return Session::get('user_id');
    }

    /**
     * Returns the currently logged-in user's role, or null.
     */
    public static function role(): ?string
    {
        return Session::get('user_role');
    }

    /**
     * Returns true if the logged-in user is an Admin.
     */
    public static function isAdmin(): bool
    {
        return self::role() === 'Admin';
    }

    /**
     * Returns true if the logged-in user is a Member.
     */
    public static function isMember(): bool
    {
        return self::role() === 'Member';
    }

    /**
     * Require the user to be logged in.
     * If not, redirect to the login page.
     */
    public static function requireLogin(): void
    {
        if (!self::check()) {
            Session::flash('error', 'You must be logged in to access that page.');
            header('Location: ?page=auth&action=login');
            exit;
        }
    }

    /**
     * Require the user to be an Admin.
     * Shows a 403 page if not authorized.
     */
    public static function requireAdmin(): void
    {
        self::requireLogin();

        if (!self::isAdmin()) {
            http_response_code(403);
            require __DIR__ . '/../../views/errors/403.php';
            exit;
        }
    }

    /**
     * Get full user data stored in session.
     */
    public static function user(): array
    {
        return [
            'id'    => Session::get('user_id'),
            'name'  => Session::get('user_name'),
            'email' => Session::get('user_email'),
            'role'  => Session::get('user_role'),
        ];
    }

    /**
     * Store user data in session after login.
     */
    public static function login(array $user): void
    {
        Session::set('user_id',    (int) $user['id']);
        Session::set('user_name',  $user['name']);
        Session::set('user_email', $user['email']);
        Session::set('user_role',  $user['role']);
    }

    /**
     * Clear authentication data from session (logout).
     */
    public static function logout(): void
    {
        Session::destroy();
    }
}
