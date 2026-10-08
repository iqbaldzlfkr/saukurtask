<?php

declare(strict_types=1);

/**
 * Global View Helpers for Saukur Task
 */

if (!function_exists('e')) {
    /**
     * Safely escape HTML string or any scalar/null value.
     */
    function e(mixed $val): string
    {
        return htmlspecialchars((string) ($val ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('fieldError')) {
    /**
     * Render a form field validation error message.
     */
    function fieldError(array $errors, string $field): string
    {
        return isset($errors[$field])
            ? '<span class="field-error" role="alert">' . e($errors[$field]) . '</span>'
            : '';
    }
}

if (!function_exists('old')) {
    /**
     * Get old input value or fallback to default.
     */
    function old(array $old, string $field, mixed $default = ''): string
    {
        return e($old[$field] ?? $default);
    }
}

if (!function_exists('statusBadge')) {
    /**
     * Render a badge for project or task status.
     */
    function statusBadge(string $status): string
    {
        $map = [
            'Planning'    => 'planning',
            'Active'      => 'active',
            'Completed'   => 'completed',
            'Archived'    => 'archived',
            'To Do'       => 'todo',
            'In Progress' => 'inprogress',
            'Done'        => 'done',
        ];
        $cls = $map[$status] ?? 'todo';
        return '<span class="badge badge-' . $cls . '">' . e($status) . '</span>';
    }
}

if (!function_exists('priorityBadge')) {
    /**
     * Render a badge for task priority.
     */
    function priorityBadge(string $priority): string
    {
        $map = [
            'Low'    => 'low',
            'Medium' => 'medium',
            'High'   => 'high',
        ];
        $cls = $map[$priority] ?? 'low';
        return '<span class="badge badge-' . $cls . '">' . e($priority) . '</span>';
    }
}
