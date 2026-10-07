<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Task;

/**
 * OverdueCalculationTest — Tests the isOverdue logic on the Task model.
 *
 * Definition: A task is overdue if:
 *   - due_date is in the past (before today)
 *   - status is NOT 'Done'
 *
 * Area: Overdue calculation business rule
 */
class OverdueCalculationTest extends TestCase
{
    /**
     * Helper: create a Task with given status and due_date.
     */
    private function makeTask(string $status, string $dueDate): Task
    {
        return new Task(
            id:          1,
            projectId:   1,
            title:       'Test Task',
            description: '',
            assigneeId:  2,
            status:      $status,
            priority:    'Medium',
            dueDate:     $dueDate,
            createdAt:   '2026-01-01 00:00:00',
            updatedAt:   '2026-01-01 00:00:00',
        );
    }

    // ── Test 1: Past due_date + not Done = overdue ─────────────────────────
    public function testPastDueDateWithTodoStatusIsOverdue(): void
    {
        $task = $this->makeTask('To Do', '2020-01-01');  // definitely in the past

        $this->assertTrue(
            $task->isOverdue(),
            'A To Do task with a past due date must be overdue.'
        );
    }

    // ── Test 2: Past due_date + Done = NOT overdue ─────────────────────────
    public function testPastDueDateWithDoneStatusIsNotOverdue(): void
    {
        $task = $this->makeTask('Done', '2020-01-01');  // past date but Done

        $this->assertFalse(
            $task->isOverdue(),
            'A Done task is never overdue, even with a past due date.'
        );
    }

    // ── Test 3: Future due_date = NOT overdue ──────────────────────────────
    public function testFutureDueDateIsNotOverdue(): void
    {
        $task = $this->makeTask('In Progress', '2099-12-31');  // far future

        $this->assertFalse(
            $task->isOverdue(),
            'A task with a future due date cannot be overdue.'
        );
    }

    // ── Test 4: In Progress + past due_date = overdue ─────────────────────
    public function testInProgressWithPastDueDateIsOverdue(): void
    {
        $task = $this->makeTask('In Progress', '2020-06-15');

        $this->assertTrue(
            $task->isOverdue(),
            'An In Progress task with a past due date must be overdue.'
        );
    }
}
