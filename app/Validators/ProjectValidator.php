<?php

namespace App\Validators;

use App\Models\Project;

/**
 * ProjectValidator — Validates project input.
 *
 * Returns an array of error messages (empty = valid).
 */
class ProjectValidator
{
    /**
     * Validate project create/update form data.
     *
     * @param array $data     Form input data.
     * @return array          Error messages keyed by field name.
     */
    public static function validate(array $data): array
    {
        $errors = [];

        if (empty(trim($data['name'] ?? ''))) {
            $errors['name'] = 'Project name is required.';
        } elseif (strlen($data['name']) > 150) {
            $errors['name'] = 'Project name must not exceed 150 characters.';
        }

        if (!in_array($data['status'] ?? '', Project::validStatuses(), true)) {
            $errors['status'] = 'Invalid status value.';
        }

        if (empty($data['start_date'] ?? '')) {
            $errors['start_date'] = 'Start date is required.';
        }

        if (empty($data['target_date'] ?? '')) {
            $errors['target_date'] = 'Target date is required.';
        }

        // Date range validation: target must be >= start
        if (
            empty($errors['start_date']) &&
            empty($errors['target_date']) &&
            strtotime($data['target_date']) < strtotime($data['start_date'])
        ) {
            $errors['target_date'] = 'Target date cannot be earlier than start date.';
        }

        return $errors;
    }
}
