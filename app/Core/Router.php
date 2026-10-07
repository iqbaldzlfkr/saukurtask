<?php

namespace App\Core;

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\UserController;
use App\Controllers\ProjectController;
use App\Controllers\TaskController;

/**
 * Router — Maps page/action to the correct Controller method.
 *
 * URL pattern: ?page=X&action=Y&id=Z
 */
class Router
{
    public function dispatch(): void
    {
        $page   = Request::page();
        $action = Request::action();

        try {
            match ($page) {
                'auth'      => $this->dispatchAuth($action),
                'dashboard' => $this->dispatchDashboard($action),
                'users'     => $this->dispatchUsers($action),
                'projects'  => $this->dispatchProjects($action),
                'tasks'     => $this->dispatchTasks($action),
                default     => $this->notFound(),
            };
        } catch (\RuntimeException $e) {
            // Database/system errors — show safe generic message
            error_log('Application error: ' . $e->getMessage());
            http_response_code(500);
            require __DIR__ . '/../../views/errors/500.php';
        }
    }

    private function dispatchAuth(string $action): void
    {
        $controller = new AuthController();
        match ($action) {
            'login'  => $controller->login(),
            'logout' => $controller->logout(),
            default  => $this->notFound(),
        };
    }

    private function dispatchDashboard(string $action): void
    {
        $controller = new DashboardController();
        match ($action) {
            'index', '' => $controller->index(),
            default     => $this->notFound(),
        };
    }

    private function dispatchUsers(string $action): void
    {
        $controller = new UserController();
        match ($action) {
            'index'      => $controller->index(),
            'create'     => $controller->create(),
            'store'      => $controller->store(),
            'edit'       => $controller->edit(),
            'update'     => $controller->update(),
            'toggle'     => $controller->toggle(),
            default      => $this->notFound(),
        };
    }

    private function dispatchProjects(string $action): void
    {
        $controller = new ProjectController();
        match ($action) {
            'index'   => $controller->index(),
            'create'  => $controller->create(),
            'store'   => $controller->store(),
            'edit'    => $controller->edit(),
            'update'  => $controller->update(),
            'archive' => $controller->archive(),
            'detail'  => $controller->detail(),
            default   => $this->notFound(),
        };
    }

    private function dispatchTasks(string $action): void
    {
        $controller = new TaskController();
        match ($action) {
            'index'         => $controller->index(),
            'create'        => $controller->create(),
            'store'         => $controller->store(),
            'edit'          => $controller->edit(),
            'update'        => $controller->update(),
            'updateStatus'  => $controller->updateStatus(),
            'detail'        => $controller->detail(),
            default         => $this->notFound(),
        };
    }

    private function notFound(): void
    {
        http_response_code(404);
        require __DIR__ . '/../../views/errors/404.php';
        exit;
    }
}
