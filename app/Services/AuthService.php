<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Core\Auth;
use App\Core\Session;

/**
 * AuthService — Handles login and logout business logic.
 */
class AuthService
{
    private UserRepository $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
    }

    /**
     * Attempt to log in with email and password.
     *
     * Returns true on success, false on failure.
     * Sets a generic error flash — never reveals which field is wrong.
     */
    public function attempt(string $email, string $password): bool
    {
        // Find user by email
        $user = $this->userRepo->findByEmail($email);

        // User not found OR password wrong — same error message (safe)
        if (!$user || !password_verify($password, $user['password'])) {
            Session::flash('login_error', 'Invalid email or password. Please try again.');
            Session::flash('auth_debug_event', [
                'action'     => 'LOGIN_FAILED',
                'sessionId'  => session_id(),
                'email'      => $email,
                'reason'     => 'Invalid email or password',
                'timestamp'  => date('Y-m-d H:i:s'),
            ]);
            error_log(sprintf(
                "[AUTH DEBUG] [LOGIN_FAILED] %s | Session ID: %s | Attempted Email: %s | Reason: Invalid credentials",
                date('Y-m-d H:i:s'),
                session_id(),
                $email
            ));
            return false;
        }

        // Inactive users cannot login
        if (!(bool) $user['is_active']) {
            Session::flash('login_error', 'Your account has been deactivated. Please contact an administrator.');
            Session::flash('auth_debug_event', [
                'action'     => 'LOGIN_FAILED',
                'sessionId'  => session_id(),
                'email'      => $email,
                'reason'     => 'Account is deactivated',
                'timestamp'  => date('Y-m-d H:i:s'),
            ]);
            error_log(sprintf(
                "[AUTH DEBUG] [LOGIN_FAILED] %s | Session ID: %s | Attempted Email: %s | Reason: Account deactivated",
                date('Y-m-d H:i:s'),
                session_id(),
                $email
            ));
            return false;
        }

        // Regenerate session ID after login (security)
        Session::regenerate();

        // Store auth data in session
        Auth::login($user);

        // Store debug event for browser console
        $debugData = [
            'action'      => 'LOGIN_SUCCESS',
            'sessionId'   => session_id(),
            'sessionName' => session_name(),
            'userId'      => (int) $user['id'],
            'userName'    => $user['name'],
            'userEmail'   => $user['email'],
            'userRole'    => $user['role'],
            'timestamp'   => date('Y-m-d H:i:s'),
        ];
        Session::flash('auth_debug_event', $debugData);

        // Log to server debug console
        error_log(sprintf(
            "[AUTH DEBUG] [LOGIN_SUCCESS] %s | Session ID: %s | User ID: %d | Name: %s | Email: %s | Role: %s",
            $debugData['timestamp'],
            $debugData['sessionId'],
            $debugData['userId'],
            $debugData['userName'],
            $debugData['userEmail'],
            $debugData['userRole']
        ));

        return true;
    }

    /**
     * Log out the current user.
     */
    public function logout(): void
    {
        Auth::logout();
    }
}
