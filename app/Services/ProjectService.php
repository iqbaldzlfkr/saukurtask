<?php

namespace App\Services;

use App\Repositories\ProjectRepository;
use App\Validators\ProjectValidator;

/**
 * ProjectService — Business logic for project management.
 */
class ProjectService
{
    private ProjectRepository $projectRepo;

    public function __construct()
    {
        $this->projectRepo = new ProjectRepository();
    }

    public function getAll(string $search = '', string $status = ''): array
    {
        return $this->projectRepo->findAll($search, $status);
    }

    public function getByMember(int $memberId, string $search = '', string $status = ''): array
    {
        return $this->projectRepo->findByMemberId($memberId, $search, $status);
    }

    public function getById(int $id): ?array
    {
        return $this->projectRepo->findById($id);
    }

    /**
     * Create a new project with validation.
     */
    public function create(array $data): array
    {
        $errors = ProjectValidator::validate($data);

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $id = $this->projectRepo->create([
            'name'        => trim($data['name']),
            'description' => trim($data['description'] ?? ''),
            'status'      => $data['status'],
            'start_date'  => $data['start_date'],
            'target_date' => $data['target_date'],
        ]);

        return ['success' => true, 'errors' => [], 'id' => $id];
    }

    /**
     * Update a project with validation.
     */
    public function update(int $id, array $data): array
    {
        $errors = ProjectValidator::validate($data);

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $this->projectRepo->update($id, [
            'name'        => trim($data['name']),
            'description' => trim($data['description'] ?? ''),
            'status'      => $data['status'],
            'start_date'  => $data['start_date'],
            'target_date' => $data['target_date'],
        ]);

        return ['success' => true, 'errors' => []];
    }

    /**
     * Archive a project.
     * Projects with tasks cannot be permanently deleted — only archived.
     */
    public function archive(int $id): array
    {
        $project = $this->projectRepo->findById($id);

        if (!$project) {
            return ['success' => false, 'message' => 'Project not found.'];
        }

        if ($project['status'] === 'Archived') {
            return ['success' => false, 'message' => 'Project is already archived.'];
        }

        $this->projectRepo->archive($id);

        return ['success' => true, 'message' => 'Project archived successfully.'];
    }
}
