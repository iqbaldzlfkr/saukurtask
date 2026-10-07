<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Services\ProjectService;
use App\Services\TaskService;

/**
 * DashboardController — Shows role-appropriate dashboard.
 */
class DashboardController
{
    private ProjectService $projectService;
    private TaskService    $taskService;

    public function __construct()
    {
        $this->projectService = new ProjectService();
        $this->taskService    = new TaskService();
    }

    public function index(): void
    {
        Auth::requireLogin();

        if (Auth::isAdmin()) {
            $this->adminDashboard();
        } else {
            $this->memberDashboard();
        }
    }

    private function adminDashboard(): void
    {
        $activeProjectCount = (new \App\Repositories\ProjectRepository())->countActive();
        $taskStats          = $this->taskService->getDashboardStats(); // all tasks

        require __DIR__ . '/../../views/dashboard/admin.php';
    }

    private function memberDashboard(): void
    {
        $memberId  = Auth::id();
        $taskStats = $this->taskService->getDashboardStats($memberId); // scoped to this member

        require __DIR__ . '/../../views/dashboard/member.php';
    }
}
