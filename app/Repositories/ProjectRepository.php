<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

/**
 * ProjectRepository — All database operations for projects.
 */
class ProjectRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Find a project by ID.
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM projects WHERE id = :id LIMIT 1'
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Get all projects for Admin with optional search and status filter.
     */
    public function findAll(string $search = '', string $status = ''): array
    {
        $conditions = [];
        $params     = [];

        if ($search !== '') {
            $conditions[] = 'name LIKE :search';
            $params[':search'] = '%' . $search . '%';
        }

        if ($status !== '' && in_array($status, ['Planning', 'Active', 'Completed', 'Archived'], true)) {
            $conditions[] = 'status = :status';
            $params[':status'] = $status;
        }

        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        $sql   = "SELECT * FROM projects {$where} ORDER BY created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Get projects visible to a Member — only those with tasks assigned to them.
     */
    public function findByMemberId(int $memberId, string $search = '', string $status = ''): array
    {
        $conditions = ['t.assignee_id = :member_id'];
        $params     = [':member_id' => $memberId];

        if ($search !== '') {
            $conditions[] = 'p.name LIKE :search';
            $params[':search'] = '%' . $search . '%';
        }

        if ($status !== '' && in_array($status, ['Planning', 'Active', 'Completed', 'Archived'], true)) {
            $conditions[] = 'p.status = :status';
            $params[':status'] = $status;
        }

        $where = 'WHERE ' . implode(' AND ', $conditions);
        $sql   = "SELECT DISTINCT p.*
                  FROM projects p
                  INNER JOIN tasks t ON t.project_id = p.id
                  {$where}
                  ORDER BY p.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Count tasks belonging to a project (used to decide archive vs. delete).
     */
    public function countTasks(int $projectId): int
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM tasks WHERE project_id = :pid'
        );
        $stmt->execute([':pid' => $projectId]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Create a new project.
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO projects (name, description, status, start_date, target_date)
             VALUES (:name, :description, :status, :start_date, :target_date)'
        );
        $stmt->execute([
            ':name'        => $data['name'],
            ':description' => $data['description'],
            ':status'      => $data['status'],
            ':start_date'  => $data['start_date'],
            ':target_date' => $data['target_date'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Update a project.
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE projects
             SET name = :name, description = :description, status = :status,
                 start_date = :start_date, target_date = :target_date
             WHERE id = :id'
        );
        return $stmt->execute([
            ':name'        => $data['name'],
            ':description' => $data['description'],
            ':status'      => $data['status'],
            ':start_date'  => $data['start_date'],
            ':target_date' => $data['target_date'],
            ':id'          => $id,
        ]);
    }

    /**
     * Archive a project.
     */
    public function archive(int $id): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE projects SET status = 'Archived' WHERE id = :id"
        );
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Get count of active projects (for dashboard).
     */
    public function countActive(): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM projects WHERE status = 'Active'"
        );
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
}
