<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;
use App\Services\ProjectService;

/**
 * ProjectController — Project management.
 * Admin: full CRUD + archive. Member: read-only (own projects).
 */
class ProjectController
{
    private ProjectService $projectService;

    public function __construct()
    {
        $this->projectService = new ProjectService();
    }

    /**
     * List projects (filtered by role).
     */
    public function index(): void
    {
        Auth::requireLogin();

        $search  = Request::get('search', '');
        $status  = Request::get('status', '');
        $success = Session::getFlash('success');
        $error   = Session::getFlash('error');

        if (Auth::isAdmin()) {
            $projects = $this->projectService->getAll($search, $status);
        } else {
            $projects = $this->projectService->getByMember(Auth::id(), $search, $status);
        }

        require __DIR__ . '/../../views/projects/index.php';
    }

    /**
     * Project detail page.
     */
    public function detail(): void
    {
        Auth::requireLogin();

        $id      = (int) Request::get('id', 0);
        $project = $this->projectService->getById($id);

        if (!$project) {
            http_response_code(404);
            require __DIR__ . '/../../views/errors/404.php';
            exit;
        }

        // Member can only view projects with their tasks
        if (Auth::isMember()) {
            $memberProjects = $this->projectService->getByMember(Auth::id());
            $ids = array_column($memberProjects, 'id');
            if (!in_array($id, $ids)) {
                http_response_code(403);
                require __DIR__ . '/../../views/errors/403.php';
                exit;
            }
        }

        require __DIR__ . '/../../views/projects/detail.php';
    }

    /**
     * Show create project form (Admin only).
     */
    public function create(): void
    {
        Auth::requireAdmin();
        $errors = [];
        $old    = [];
        require __DIR__ . '/../../views/projects/create.php';
    }

    /**
     * Process create project form (Admin only).
     */
    public function store(): void
    {
        Auth::requireAdmin();

        if (!Request::isPost()) {
            header('Location: ?page=projects&action=create');
            exit;
        }

        $data   = Request::all();
        $result = $this->projectService->create($data);

        if ($result['success']) {
            Session::flash('success', 'Project created successfully.');
            header('Location: ?page=projects');
            exit;
        }

        $errors = $result['errors'];
        $old    = $data;
        require __DIR__ . '/../../views/projects/create.php';
    }

    /**
     * Show edit project form (Admin only).
     */
    public function edit(): void
    {
        Auth::requireAdmin();

        $id      = (int) Request::get('id', 0);
        $project = $this->projectService->getById($id);

        if (!$project) {
            http_response_code(404);
            require __DIR__ . '/../../views/errors/404.php';
            exit;
        }

        $errors = [];
        $old    = $project;
        require __DIR__ . '/../../views/projects/edit.php';
    }

    /**
     * Process edit project form (Admin only).
     */
    public function update(): void
    {
        Auth::requireAdmin();

        if (!Request::isPost()) {
            header('Location: ?page=projects');
            exit;
        }

        $id      = (int) Request::post('id', 0);
        $project = $this->projectService->getById($id);

        if (!$project) {
            http_response_code(404);
            require __DIR__ . '/../../views/errors/404.php';
            exit;
        }

        $data   = Request::all();
        $result = $this->projectService->update($id, $data);

        if ($result['success']) {
            Session::flash('success', 'Project updated successfully.');
            header('Location: ?page=projects');
            exit;
        }

        $errors = $result['errors'];
        $old    = array_merge($project, $data);
        require __DIR__ . '/../../views/projects/edit.php';
    }

    /**
     * Archive a project (Admin only).
     */
    public function archive(): void
    {
        Auth::requireAdmin();

        $id     = (int) Request::get('id', 0);
        $result = $this->projectService->archive($id);

        if ($result['success']) {
            Session::flash('success', $result['message']);
        } else {
            Session::flash('error', $result['message']);
        }

        header('Location: ?page=projects');
        exit;
    }
}
