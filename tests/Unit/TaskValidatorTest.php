<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Validators\TaskValidator;

/**
 * TaskValidatorTest — Tests task status/priority enums and due_date range.
 *
 * Area: Task validation (status enum, priority enum, date-in-range)
 */
class TaskValidatorTest extends TestCase
{
    private array $validProject;

    protected function setUp(): void
    {
        $this->validProject = [
            'start_date'  => '2026-09-01',
            'target_date' => '2026-12-31',
        ];
    }

    // ── Test 1: Invalid status "Cancelled" is rejected ────────────────────
    public function testInvalidStatusCancelledIsRejected(): void
    {
        $data = [
            'title'       => 'Test Task',
            'project_id'  => 1,
            'assignee_id' => 2,
            'status'      => 'Cancelled',  // NOT valid
            'priority'    => 'Medium',
            'due_date'    => '2026-10-01',
        ];

        $errors = TaskValidator::validate($data, $this->validProject);

        $this->assertArrayHasKey(
            'status',
            $errors,
            'Status "Cancelled" is not in [To Do, In Progress, Done] and must be rejected.'
        );
    }

    // ── Test 2: Invalid priority "Urgent" is rejected ─────────────────────
    public function testInvalidPriorityUrgentIsRejected(): void
    {
        $data = [
            'title'       => 'Test Task',
            'project_id'  => 1,
            'assignee_id' => 2,
            'status'      => 'To Do',
            'priority'    => 'Urgent',  // NOT valid
            'due_date'    => '2026-10-01',
        ];

        $errors = TaskValidator::validate($data, $this->validProject);

        $this->assertArrayHasKey(
            'priority',
            $errors,
            'Priority "Urgent" is not in [Low, Medium, High] and must be rejected.'
        );
    }

    // ── Test 3: due_date before project start_date is rejected ────────────
    public function testDueDateBeforeProjectStartIsRejected(): void
    {
        $data = [
            'title'       => 'Early Task',
            'project_id'  => 1,
            'assignee_id' => 2,
            'status'      => 'To Do',
            'priority'    => 'High',
            'due_date'    => '2026-08-01',  // BEFORE project start
        ];

        $errors = TaskValidator::validate($data, $this->validProject);

        $this->assertArrayHasKey(
            'due_date',
            $errors,
            'Due date before project start_date must be rejected.'
        );
    }

    // ── Test 4: due_date after project target_date is rejected ────────────
    public function testDueDateAfterProjectTargetIsRejected(): void
    {
        $data = [
            'title'       => 'Late Task',
            'project_id'  => 1,
            'assignee_id' => 2,
            'status'      => 'To Do',
            'priority'    => 'Low',
            'due_date'    => '2027-01-01',  // AFTER project target
        ];

        $errors = TaskValidator::validate($data, $this->validProject);

        $this->assertArrayHasKey(
            'due_date',
            $errors,
            'Due date after project target_date must be rejected.'
        );
    }

    // ── Test 5: Valid task passes with no errors ───────────────────────────
    public function testValidTaskPassesValidation(): void
    {
        $data = [
            'title'       => 'Valid Task',
            'project_id'  => 1,
            'assignee_id' => 2,
            'status'      => 'In Progress',
            'priority'    => 'High',
            'due_date'    => '2026-10-15',  // within project range
        ];

        $errors = TaskValidator::validate($data, $this->validProject);

        $this->assertEmpty($errors, 'A valid task should produce no validation errors.');
    }

    // ── Test 6: Status-only update with valid status ───────────────────────
    public function testValidStatusUpdatePasses(): void
    {
        $errors = TaskValidator::validateStatusUpdate('Done');
        $this->assertEmpty($errors, '"Done" is a valid status and should pass.');
    }

    // ── Test 7: Status-only update with invalid status ────────────────────
    public function testInvalidStatusUpdateFails(): void
    {
        $errors = TaskValidator::validateStatusUpdate('Pending');
        $this->assertArrayHasKey(
            'status',
            $errors,
            '"Pending" is not a valid status and must be rejected.'
        );
    }
}
