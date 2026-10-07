<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Validators\UserValidator;

/**
 * UserValidatorTest — Tests user input validation rules.
 *
 * Area: User management validation (email format, role enum, password requirements)
 */
class UserValidatorTest extends TestCase
{
    // ── Test 1: Invalid email format is rejected ───────────────────────────
    public function testInvalidEmailFormatIsRejected(): void
    {
        $data = [
            'name'     => 'Test User',
            'email'    => 'not-a-valid-email',  // BAD format
            'password' => 'Secure@1234',
            'role'     => 'Member',
        ];

        $errors = UserValidator::validateCreate($data, false);

        $this->assertArrayHasKey(
            'email',
            $errors,
            'An improperly formatted email must be rejected.'
        );
    }

    // ── Test 2: Duplicate email is rejected ────────────────────────────────
    public function testDuplicateEmailIsRejected(): void
    {
        $data = [
            'name'     => 'Alice',
            'email'    => 'alice@example.com',
            'password' => 'Secure@1234',
            'role'     => 'Member',
        ];

        // Simulate emailExists = true (as would come from the repository)
        $errors = UserValidator::validateCreate($data, true);

        $this->assertArrayHasKey(
            'email',
            $errors,
            'A duplicate email must be rejected.'
        );
    }

    // ── Test 3: Invalid role is rejected ───────────────────────────────────
    public function testInvalidRoleIsRejected(): void
    {
        $data = [
            'name'     => 'Bob',
            'email'    => 'bob@example.com',
            'password' => 'Secure@1234',
            'role'     => 'Superuser',  // NOT a valid role
        ];

        $errors = UserValidator::validateCreate($data, false);

        $this->assertArrayHasKey(
            'role',
            $errors,
            'Role "Superuser" is not Admin or Member and must be rejected.'
        );
    }

    // ── Test 4: Short password is rejected ────────────────────────────────
    public function testShortPasswordIsRejected(): void
    {
        $data = [
            'name'     => 'Carol',
            'email'    => 'carol@example.com',
            'password' => '123',  // TOO SHORT
            'role'     => 'Member',
        ];

        $errors = UserValidator::validateCreate($data, false);

        $this->assertArrayHasKey(
            'password',
            $errors,
            'Password shorter than 8 characters must be rejected.'
        );
    }

    // ── Test 5: Valid user data passes ────────────────────────────────────
    public function testValidUserPassesValidation(): void
    {
        $data = [
            'name'     => 'Dave Smith',
            'email'    => 'dave@example.com',
            'password' => 'Secure@1234',
            'role'     => 'Admin',
        ];

        $errors = UserValidator::validateCreate($data, false);

        $this->assertEmpty($errors, 'Valid user data should produce no errors.');
    }
}
