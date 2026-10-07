<?php

namespace App\Validators;

use App\Models\Task;

/**
 * TaskValidator — Validates task input.
 *
 * Returns an array of error messages (empty = valid).
 */
class TaskValidator
{
    /**
     * Validate task create/update input.
     *
     * @param array      $data     Form input.
     * @param array|null $project  The project row from database (to validate due_date range).
     * @return array               Error messages keyed by field name.
     */
    public static function validate(array $data, ?array $project = null): array
    {
        $errors = [];

        if (empty(trim($data['title'] ?? ''))) {
            $errors['title'] = 'Task title is required.';
        } elseif (strlen($data['title']) > 200) {
            $errors['title'] = 'Task title must not exceed 200 characters.';
        }

        if (empty($data['project_id'] ?? '')) {
            $errors['project_id'] = 'Please select a project.';
        }

        if (empty($data['assignee_id'] ?? '')) {
            $errors['assignee_id'] = 'Please select an assignee.';
        }

        if (!in_array($data['status'] ?? '', Task::validStatuses(), true)) {
            $errors['status'] = 'Invalid status. Must be: To Do, In Progress, or Done.';
        }

        if (!in_array($data['priority'] ?? '', Task::validPriorities(), true)) {
            $errors['priority'] = 'Invalid priority. Must be: Low, Medium, or High.';
        }

        if (empty($data['due_date'] ?? '')) {
            $errors['due_date'] = 'Due date is required.';
        } elseif ($project !== null) {
            // Validate due_date is within project date range
            $dueTs    = strtotime($data['due_date']);
            $startTs  = strtotime($project['start_date']);
            $targetTs = strtotime($project['target_date']);

            if ($dueTs < $startTs || $dueTs > $targetTs) {
                $errors['due_date'] = sprintf(
                    'Due date must be between project start (%s) and target (%s).',
                    $project['start_date'],
                    $project['target_date']
                );
            }
        }

        return $errors;
    }

    /**
     * Validate status-only update (Member action).
     */
    public static function validateStatusUpdate(string $status): array
    {
        $errors = [];
        if (!in_array($status, Task::validStatuses(), true)) {
            $errors['status'] = 'Invalid status value.';
        }
        return $errors;
    }
}
