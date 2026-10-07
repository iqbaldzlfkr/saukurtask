<?php

namespace App\Services;

use App\Repositories\TaskRepository;
use App\Repositories\ProjectRepository;
use App\Validators\TaskValidator;

/**
 * TaskService — Business logic for task management.
 */
class TaskService
{
    private TaskRepository    $taskRepo;
    private ProjectRepository $projectRepo;

    public function __construct()
    {
        $this->taskRepo    = new TaskRepository();
        $this->projectRepo = new ProjectRepository();
    }

    public function getPaginated(array $filters, int $page, ?int $assigneeId = null): array
    {
        return $this->taskRepo->findPaginated($filters, $page, 10, $assigneeId);
    }

    public function getById(int $id): ?array
    {
        return $this->taskRepo->findById($id);
    }

    /**
     * Create a task with validation.
     */
    public function create(array $data): array
    {
        $project = null;
        if (!empty($data['project_id'])) {
            $project = $this->projectRepo->findById((int) $data['project_id']);
            if (!$project) {
                return ['success' => false, 'errors' => ['project_id' => 'Selected project does not exist.']];
            }
        }

        $errors = TaskValidator::validate($data, $project);

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $id = $this->taskRepo->create([
            'project_id'  => (int) $data['project_id'],
            'title'       => trim($data['title']),
            'description' => trim($data['description'] ?? ''),
            'assignee_id' => (int) $data['assignee_id'],
            'status'      => $data['status'],
            'priority'    => $data['priority'],
            'due_date'    => $data['due_date'],
        ]);

        return ['success' => true, 'errors' => [], 'id' => $id];
    }

    /**
     * Update a task (Admin only — full update).
     */
    public function update(int $id, array $data): array
    {
        $project = null;
        if (!empty($data['project_id'])) {
            $project = $this->projectRepo->findById((int) $data['project_id']);
            if (!$project) {
                return ['success' => false, 'errors' => ['project_id' => 'Selected project does not exist.']];
            }
        }

        $errors = TaskValidator::validate($data, $project);

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $this->taskRepo->update($id, [
            'project_id'  => (int) $data['project_id'],
            'title'       => trim($data['title']),
            'description' => trim($data['description'] ?? ''),
            'assignee_id' => (int) $data['assignee_id'],
            'status'      => $data['status'],
            'priority'    => $data['priority'],
            'due_date'    => $data['due_date'],
        ]);

        return ['success' => true, 'errors' => []];
    }

    /**
     * Update only the status (Member action — only on own tasks).
     *
     * @param int    $taskId  The task to update.
     * @param string $status  New status.
     * @param int    $userId  The member doing the update.
     */
    public function updateStatus(int $taskId, string $status, int $userId): array
    {
        // Authorization check — task must belong to this user
        if (!$this->taskRepo->belongsToUser($taskId, $userId)) {
            return ['success' => false, 'errors' => ['auth' => 'You are not authorized to update this task.']];
        }

        $errors = TaskValidator::validateStatusUpdate($status);

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $this->taskRepo->updateStatus($taskId, $status);

        return ['success' => true, 'errors' => []];
    }

    public function getDashboardStats(?int $assigneeId = null): array
    {
        return [
            'by_status' => $this->taskRepo->countByStatus($assigneeId),
            'overdue'   => $this->taskRepo->countOverdue($assigneeId),
            'nearest'   => $this->taskRepo->findNearestDue(5, $assigneeId),
        ];
    }
}
