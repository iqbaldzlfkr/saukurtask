<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;

/**
 * AuthController — Handles login and logout.
 */
class AuthController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    /**
     * Show the login form (GET) or process login (POST).
     */
    public function login(): void
    {
        // Already logged in → redirect to dashboard
        if (Auth::check()) {
            header('Location: ?page=dashboard');
            exit;
        }

        if (Request::isPost()) {
            $email    = trim(Request::post('email', ''));
            $password = Request::post('password', '');

            $success = $this->authService->attempt($email, $password);

            if ($success) {
                // Redirect to dashboard after successful login
                header('Location: ?page=dashboard');
                exit;
            }

            // Redisplay form with error — keep email so user doesn't retype
            $error = Session::getFlash('login_error');
            require __DIR__ . '/../../views/auth/login.php';
            return;
        }

        // GET: show login form
        $error = Session::getFlash('login_error');
        require __DIR__ . '/../../views/auth/login.php';
    }

    /**
     * Log out the current user and redirect to login.
     */
    public function logout(): void
    {
        $oldSessionId = session_id();
        $user         = Auth::user();

        // Log to server debug console
        error_log(sprintf(
            "[AUTH DEBUG] [LOGOUT] %s | Terminated Session ID: %s | User ID: %s | Email: %s | Role: %s",
            date('Y-m-d H:i:s'),
            $oldSessionId,
            $user['id'] ?? 'unknown',
            $user['email'] ?? 'unknown',
            $user['role'] ?? 'unknown'
        ));

        // Destroy authenticated session
        $this->authService->logout();

        // Redirect to login page with debug info in query string (reliable across session destructions)
        $query = http_build_query([
            'page'       => 'auth',
            'action'     => 'login',
            'logged_out' => '1',
            'prev_sid'   => $oldSessionId,
            'prev_user'  => $user['email'] ?? 'User',
            'prev_role'  => $user['role'] ?? 'Member',
            'time'       => date('Y-m-d H:i:s'),
        ]);

        header("Location: ?$query");
        exit;
    }
}
