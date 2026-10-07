<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;
use App\Services\UserService;

/**
 * UserController — Admin-only user management.
 * Every method calls Auth::requireAdmin() first.
 */
class UserController
{
    /**
     * Switch to true next week (Week 4) to re-enable User Management.
     * Set to false for Week 3 evaluation to reflect in-progress roadmap.
     */
    public const IS_MODULE_ACTIVE = true;

    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    /**
     * Intercept request if module is currently marked in-progress.
     */
    private function checkModuleActive(): void
    {
        if (!self::IS_MODULE_ACTIVE) {
            require __DIR__ . '/../../views/users/under-construction.php';
            exit;
        }
    }

    /**
     * List all users with optional search.
     */
    public function index(): void
    {
        Auth::requireAdmin();
        $this->checkModuleActive();

        $search = Request::get('search', '');
        $users  = $this->userService->getAll($search);
        $success = Session::getFlash('success');
        $error   = Session::getFlash('error');

        require __DIR__ . '/../../views/users/index.php';
    }

    /**
     * Show create user form.
     */
    public function create(): void
    {
        Auth::requireAdmin();
        $this->checkModuleActive();
        $errors = [];
        $old    = [];
        require __DIR__ . '/../../views/users/create.php';
    }

    /**
     * Process create user form submission.
     */
    public function store(): void
    {
        Auth::requireAdmin();
        $this->checkModuleActive();

        if (!Request::isPost()) {
            header('Location: ?page=users&action=create');
            exit;
        }

        $data   = Request::all();
        $result = $this->userService->create($data);

        if ($result['success']) {
            Session::flash('success', 'User created successfully.');
            header('Location: ?page=users');
            exit;
        }

        // Re-show form with errors and old input
        $errors = $result['errors'];
        $old    = $data;
        require __DIR__ . '/../../views/users/create.php';
    }

    /**
     * Show edit user form.
     */
    public function edit(): void
    {
        Auth::requireAdmin();
        $this->checkModuleActive();

        $id   = (int) Request::get('id', 0);
        $user = $this->userService->getById($id);

        if (!$user) {
            http_response_code(404);
            require __DIR__ . '/../../views/errors/404.php';
            exit;
        }

        $errors = [];
        $old    = $user;
        require __DIR__ . '/../../views/users/edit.php';
    }

    /**
     * Process edit user form submission.
     */
    public function update(): void
    {
        Auth::requireAdmin();
        $this->checkModuleActive();

        if (!Request::isPost()) {
            header('Location: ?page=users');
            exit;
        }

        $id     = (int) Request::post('id', 0);
        $user   = $this->userService->getById($id);

        if (!$user) {
            http_response_code(404);
            require __DIR__ . '/../../views/errors/404.php';
            exit;
        }

        $data   = Request::all();
        $result = $this->userService->update($id, $data);

        if ($result['success']) {
            Session::flash('success', 'User updated successfully.');
            header('Location: ?page=users');
            exit;
        }

        $errors = $result['errors'];
        $old    = array_merge($user, $data);
        require __DIR__ . '/../../views/users/edit.php';
    }

    /**
     * Toggle user active/inactive status.
     */
    public function toggle(): void
    {
        Auth::requireAdmin();
        $this->checkModuleActive();

        $id = (int) Request::get('id', 0);

        // Prevent Admin from deactivating themselves
        if ($id === Auth::id()) {
            Session::flash('error', 'You cannot deactivate your own account.');
            header('Location: ?page=users');
            exit;
        }

        $this->userService->toggleActive($id);
        Session::flash('success', 'User status updated.');
        header('Location: ?page=users');
        exit;
    }
}
