<?php

namespace App\Models;

/**
 * Task — Simple data model (value object).
 */
class Task
{
    public function __construct(
        public readonly int    $id,
        public readonly int    $projectId,
        public readonly string $title,
        public readonly string $description,
        public readonly int    $assigneeId,
        public readonly string $status,
        public readonly string $priority,
        public readonly string $dueDate,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    ) {}

    public static function fromArray(array $row): self
    {
        return new self(
            id:          (int) $row['id'],
            projectId:   (int) $row['project_id'],
            title:             $row['title'],
            description:       $row['description'] ?? '',
            assigneeId:  (int) $row['assignee_id'],
            status:            $row['status'],
            priority:          $row['priority'],
            dueDate:           $row['due_date'],
            createdAt:         $row['created_at'],
            updatedAt:         $row['updated_at'],
        );
    }

    /** Valid status values — anything else is rejected */
    public static function validStatuses(): array
    {
        return ['To Do', 'In Progress', 'Done'];
    }

    /** Valid priority values */
    public static function validPriorities(): array
    {
        return ['Low', 'Medium', 'High'];
    }

    /**
     * A task is overdue if its due_date has passed and the status is not 'Done'.
     */
    public function isOverdue(): bool
    {
        return $this->status !== 'Done'
            && strtotime($this->dueDate) < strtotime(date('Y-m-d'));
    }
}
