<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Validators\ProjectValidator;

/**
 * ProjectValidatorTest — Tests project date validation and status rules.
 *
 * Area: Project date validation (FIND-01, PRJ-01)
 */
class ProjectValidatorTest extends TestCase
{
    // ── Test 1: Valid project data passes with no errors ───────────────────
    public function testValidProjectPassesValidation(): void
    {
        $data = [
            'name'        => 'Valid Project',
            'status'      => 'Active',
            'start_date'  => '2026-09-01',
            'target_date' => '2026-12-31',
        ];

        $errors = ProjectValidator::validate($data);

        $this->assertEmpty($errors, 'A valid project should produce no validation errors.');
    }

    // ── Test 2: target_date before start_date is rejected ─────────────────
    public function testTargetDateBeforeStartDateFails(): void
    {
        $data = [
            'name'        => 'Bad Date Project',
            'status'      => 'Active',
            'start_date'  => '2026-09-01',
            'target_date' => '2026-08-01',  // BEFORE start_date
        ];

        $errors = ProjectValidator::validate($data);

        $this->assertArrayHasKey(
            'target_date',
            $errors,
            'target_date earlier than start_date must be rejected.'
        );
    }

    // ── Test 3: Same start and target date is valid ────────────────────────
    public function testSameDateIsValid(): void
    {
        $data = [
            'name'        => 'One Day Project',
            'status'      => 'Planning',
            'start_date'  => '2026-09-15',
            'target_date' => '2026-09-15',  // SAME as start — OK
        ];

        $errors = ProjectValidator::validate($data);

        $this->assertArrayNotHasKey(
            'target_date',
            $errors,
            'Same start and target date should be allowed.'
        );
    }

    // ── Test 4: Invalid status is rejected ─────────────────────────────────
    public function testInvalidStatusIsRejected(): void
    {
        $data = [
            'name'        => 'Project X',
            'status'      => 'Cancelled',  // NOT in allowed list
            'start_date'  => '2026-09-01',
            'target_date' => '2026-12-31',
        ];

        $errors = ProjectValidator::validate($data);

        $this->assertArrayHasKey(
            'status',
            $errors,
            'Invalid status value "Cancelled" must be rejected.'
        );
    }

    // ── Test 5: Missing name is rejected ───────────────────────────────────
    public function testMissingNameIsRejected(): void
    {
        $data = [
            'name'        => '',  // EMPTY
            'status'      => 'Active',
            'start_date'  => '2026-09-01',
            'target_date' => '2026-12-31',
        ];

        $errors = ProjectValidator::validate($data);

        $this->assertArrayHasKey('name', $errors, 'Empty name must produce a validation error.');
    }
}
