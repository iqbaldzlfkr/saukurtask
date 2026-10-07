<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;
use App\Services\TaskService;
use App\Services\ProjectService;
use App\Repositories\UserRepository;

/**
 * TaskController — Task management.
 * Admin: full CRUD. Member: view own tasks + update status.
 */
class TaskController
{
    private TaskService    $taskService;
    private ProjectService $projectService;
    private UserRepository $userRepo;

    public function __construct()
    {
        $this->taskService    = new TaskService();
        $this->projectService = new ProjectService();
        $this->userRepo       = new UserRepository();
    }

    /**
     * Task list with search, filter, sort, and pagination.
     */
    public function index(): void
    {
        Auth::requireLogin();

        $filters = [
            'search'     => Request::get('search', ''),
            'project_id' => Request::get('project_id', ''),
            'status'     => Request::get('status', ''),
            'priority'   => Request::get('priority', ''),
            'sort_due'   => Request::get('sort_due', 'ASC'),
        ];
        $page = max(1, (int) Request::get('pg', 1));

        $assigneeId = Auth::isAdmin() ? null : Auth::id();
        $result     = $this->taskService->getPaginated($filters, $page, $assigneeId);

        // For project filter dropdown
        $projects = Auth::isAdmin()
            ? $this->projectService->getAll()
            : $this->projectService->getByMember(Auth::id());

        $success = Session::getFlash('success');
        $error   = Session::getFlash('error');

        require __DIR__ . '/../../views/tasks/index.php';
    }

    /**
     * Task detail page.
     */
    public function detail(): void
    {
        Auth::requireLogin();

        $id   = (int) Request::get('id', 0);
        $task = $this->taskService->getById($id);

        if (!$task) {
            http_response_code(404);
            require __DIR__ . '/../../views/errors/404.php';
            exit;
        }

        // Member can only view own tasks
        if (Auth::isMember() && $task['assignee_id'] != Auth::id()) {
            http_response_code(403);
            require __DIR__ . '/../../views/errors/403.php';
            exit;
        }

        require __DIR__ . '/../../views/tasks/detail.php';
    }

    /**
     * Show create task form (Admin only).
     */
    public function create(): void
    {
        Auth::requireAdmin();

        $projects = $this->projectService->getAll();
        $members  = $this->userRepo->findActiveMembers();
        $errors   = [];
        $old      = [];

        require __DIR__ . '/../../views/tasks/create.php';
    }

    /**
     * Process create task (Admin only).
     */
    public function store(): void
    {
        Auth::requireAdmin();

        if (!Request::isPost()) {
            header('Location: ?page=tasks&action=create');
            exit;
        }

        $data   = Request::all();
        $result = $this->taskService->create($data);

        if ($result['success']) {
            Session::flash('success', 'Task created successfully.');
            header('Location: ?page=tasks');
            exit;
        }

        $projects = $this->projectService->getAll();
        $members  = $this->userRepo->findActiveMembers();
        $errors   = $result['errors'];
        $old      = $data;
        require __DIR__ . '/../../views/tasks/create.php';
    }

    /**
     * Show edit task form (Admin only).
     */
    public function edit(): void
    {
        Auth::requireAdmin();

        $id   = (int) Request::get('id', 0);
        $task = $this->taskService->getById($id);

        if (!$task) {
            http_response_code(404);
            require __DIR__ . '/../../views/errors/404.php';
            exit;
        }

        $projects = $this->projectService->getAll();
        $members  = $this->userRepo->findActiveMembers();
        $errors   = [];
        $old      = $task;

        require __DIR__ . '/../../views/tasks/edit.php';
    }

    /**
     * Process full task update (Admin only).
     */
    public function update(): void
    {
        Auth::requireAdmin();

        if (!Request::isPost()) {
            header('Location: ?page=tasks');
            exit;
        }

        $id   = (int) Request::post('id', 0);
        $task = $this->taskService->getById($id);

        if (!$task) {
            http_response_code(404);
            require __DIR__ . '/../../views/errors/404.php';
            exit;
        }

        $data   = Request::all();
        $result = $this->taskService->update($id, $data);

        if ($result['success']) {
            Session::flash('success', 'Task updated successfully.');
            header('Location: ?page=tasks&action=detail&id=' . $id);
            exit;
        }

        $projects = $this->projectService->getAll();
        $members  = $this->userRepo->findActiveMembers();
        $errors   = $result['errors'];
        $old      = array_merge($task, $data);
        require __DIR__ . '/../../views/tasks/edit.php';
    }

    /**
     * Update only the status of a task.
     * Member: only own tasks. Admin: any task.
     */
    public function updateStatus(): void
    {
        Auth::requireLogin();

        if (!Request::isPost()) {
            header('Location: ?page=tasks');
            exit;
        }

        $taskId = (int) Request::post('task_id', 0);
        $status = Request::post('status', '');

        // Validate status enum first
        $errors = \App\Validators\TaskValidator::validateStatusUpdate($status);
        if (!empty($errors)) {
            Session::flash('error', array_values($errors)[0]);
            header('Location: ?page=tasks&action=detail&id=' . $taskId);
            exit;
        }

        if (Auth::isMember()) {
            // Member: must own the task
            $taskRepo = new \App\Repositories\TaskRepository();
            if (!$taskRepo->belongsToUser($taskId, Auth::id())) {
                http_response_code(403);
                require __DIR__ . '/../../views/errors/403.php';
                exit;
            }
            $taskRepo->updateStatus($taskId, $status);
        } else {
            // Admin: can update any task
            (new \App\Repositories\TaskRepository())->updateStatus($taskId, $status);
        }

        Session::flash('success', 'Task status updated successfully.');
        header('Location: ?page=tasks&action=detail&id=' . $taskId);
        exit;
    }
}
