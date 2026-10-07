<?php

namespace App\Models;

/**
 * Project — Simple data model (value object).
 */
class Project
{
    public function __construct(
        public readonly int    $id,
        public readonly string $name,
        public readonly string $description,
        public readonly string $status,
        public readonly string $startDate,
        public readonly string $targetDate,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    ) {}

    public static function fromArray(array $row): self
    {
        return new self(
            id:          (int) $row['id'],
            name:              $row['name'],
            description:       $row['description'] ?? '',
            status:            $row['status'],
            startDate:         $row['start_date'],
            targetDate:        $row['target_date'],
            createdAt:         $row['created_at'],
            updatedAt:         $row['updated_at'],
        );
    }

    public function isArchived(): bool
    {
        return $this->status === 'Archived';
    }

    /** Valid statuses — any other value should be rejected by validation */
    public static function validStatuses(): array
    {
        return ['Planning', 'Active', 'Completed', 'Archived'];
    }
}
