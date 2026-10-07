<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

/**
 * TaskRepository — All database operations for tasks.
 * Supports search, filter, sort, and server-side pagination.
 */
class TaskRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Find a task by ID (includes joined project and assignee data).
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT t.*,
                    p.name AS project_name, p.start_date AS project_start, p.target_date AS project_target,
                    u.name AS assignee_name
             FROM tasks t
             INNER JOIN projects  p ON p.id = t.project_id
             INNER JOIN users     u ON u.id = t.assignee_id
             WHERE t.id = :id
             LIMIT 1'
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Build and execute a task list query with filtering, sorting, pagination.
     *
     * @param array $filters  Keys: search, project_id, status, priority, sort_due
     * @param int   $page     Current page (1-indexed)
     * @param int   $perPage  Items per page (default 10)
     * @param int|null $assigneeId  If set, scope to this Member only
     * @return array ['tasks' => [...], 'total' => int, 'pages' => int]
     */
    public function findPaginated(
        array  $filters    = [],
        int    $page       = 1,
        int    $perPage    = 10,
        ?int   $assigneeId = null
    ): array {
        $conditions = [];
        $params     = [];

        // Scope to a specific member
        if ($assigneeId !== null) {
            $conditions[] = 't.assignee_id = :assignee_id';
            $params[':assignee_id'] = $assigneeId;
        }

        if (!empty($filters['search'])) {
            $conditions[] = 't.title LIKE :search';
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['project_id'])) {
            $conditions[] = 't.project_id = :project_id';
            $params[':project_id'] = (int) $filters['project_id'];
        }

        if (!empty($filters['status']) && in_array($filters['status'], ['To Do', 'In Progress', 'Done'], true)) {
            $conditions[] = 't.status = :status';
            $params[':status'] = $filters['status'];
        }

        if (!empty($filters['priority']) && in_array($filters['priority'], ['Low', 'Medium', 'High'], true)) {
            $conditions[] = 't.priority = :priority';
            $params[':priority'] = $filters['priority'];
        }

        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

        // Sort order for due_date
        $sortDir = (isset($filters['sort_due']) && strtoupper($filters['sort_due']) === 'ASC') ? 'ASC' : 'DESC';
        $orderBy = "ORDER BY t.due_date {$sortDir}";

        // Count total for pagination
        $countSql  = "SELECT COUNT(*) FROM tasks t {$where}";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();
        $pages = (int) ceil($total / $perPage);
        $page  = max(1, min($page, max(1, $pages)));

        $offset = ($page - 1) * $perPage;

        $sql = "SELECT t.*,
                       p.name AS project_name,
                       u.name AS assignee_name
                FROM tasks t
                INNER JOIN projects p ON p.id = t.project_id
                INNER JOIN users    u ON u.id = t.assignee_id
                {$where}
                {$orderBy}
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
        $stmt->execute();

        return [
            'tasks' => $stmt->fetchAll(),
            'total' => $total,
            'pages' => $pages,
            'page'  => $page,
        ];
    }

    /**
     * Create a new task.
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO tasks (project_id, title, description, assignee_id, status, priority, due_date)
             VALUES (:project_id, :title, :description, :assignee_id, :status, :priority, :due_date)'
        );
        $stmt->execute([
            ':project_id'  => $data['project_id'],
            ':title'       => $data['title'],
            ':description' => $data['description'],
            ':assignee_id' => $data['assignee_id'],
            ':status'      => $data['status'],
            ':priority'    => $data['priority'],
            ':due_date'    => $data['due_date'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Update a task (Admin only — full update).
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE tasks
             SET project_id = :project_id, title = :title, description = :description,
                 assignee_id = :assignee_id, status = :status, priority = :priority,
                 due_date = :due_date
             WHERE id = :id'
        );
        return $stmt->execute([
            ':project_id'  => $data['project_id'],
            ':title'       => $data['title'],
            ':description' => $data['description'],
            ':assignee_id' => $data['assignee_id'],
            ':status'      => $data['status'],
            ':priority'    => $data['priority'],
            ':due_date'    => $data['due_date'],
            ':id'          => $id,
        ]);
    }

    /**
     * Update only the status of a task (Member action).
     */
    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE tasks SET status = :status WHERE id = :id'
        );
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    /**
     * Dashboard: Count tasks grouped by status (Admin = all, Member = scoped).
     */
    public function countByStatus(?int $assigneeId = null): array
    {
        if ($assigneeId !== null) {
            $stmt = $this->db->prepare(
                'SELECT status, COUNT(*) AS cnt FROM tasks
                 WHERE assignee_id = :assignee_id
                 GROUP BY status'
            );
            $stmt->execute([':assignee_id' => $assigneeId]);
        } else {
            $stmt = $this->db->prepare(
                'SELECT status, COUNT(*) AS cnt FROM tasks GROUP BY status'
            );
            $stmt->execute();
        }

        $rows   = $stmt->fetchAll();
        $counts = ['To Do' => 0, 'In Progress' => 0, 'Done' => 0];
        foreach ($rows as $row) {
            $counts[$row['status']] = (int) $row['cnt'];
        }
        return $counts;
    }

    /**
     * Dashboard: Count overdue tasks (due_date < today AND status != Done).
     */
    public function countOverdue(?int $assigneeId = null): int
    {
        if ($assigneeId !== null) {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM tasks
                 WHERE assignee_id = :assignee_id
                   AND status != 'Done'
                   AND due_date < CURDATE()"
            );
            $stmt->execute([':assignee_id' => $assigneeId]);
        } else {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM tasks
                 WHERE status != 'Done' AND due_date < CURDATE()"
            );
            $stmt->execute();
        }
        return (int) $stmt->fetchColumn();
    }

    /**
     * Dashboard: Get 5 tasks with nearest due date (not Done).
     */
    public function findNearestDue(int $limit = 5, ?int $assigneeId = null): array
    {
        if ($assigneeId !== null) {
            $stmt = $this->db->prepare(
                "SELECT t.*, p.name AS project_name, u.name AS assignee_name
                 FROM tasks t
                 INNER JOIN projects p ON p.id = t.project_id
                 INNER JOIN users    u ON u.id = t.assignee_id
                 WHERE t.assignee_id = :assignee_id AND t.status != 'Done'
                 ORDER BY t.due_date ASC
                 LIMIT :limit"
            );
            $stmt->bindValue(':assignee_id', $assigneeId, PDO::PARAM_INT);
        } else {
            $stmt = $this->db->prepare(
                "SELECT t.*, p.name AS project_name, u.name AS assignee_name
                 FROM tasks t
                 INNER JOIN projects p ON p.id = t.project_id
                 INNER JOIN users    u ON u.id = t.assignee_id
                 WHERE t.status != 'Done'
                 ORDER BY t.due_date ASC
                 LIMIT :limit"
            );
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Check if a task belongs to a specific user (for authorization).
     */
    public function belongsToUser(int $taskId, int $userId): bool
    {
        $stmt = $this->db->prepare(
            'SELECT id FROM tasks WHERE id = :task_id AND assignee_id = :user_id LIMIT 1'
        );
        $stmt->execute([':task_id' => $taskId, ':user_id' => $userId]);
        return (bool) $stmt->fetch();
    }
}
