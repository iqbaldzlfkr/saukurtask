# Unit Test Results

## PHPUnit Run — 2026-08-31

**Command:** `./vendor/bin/phpunit --testdox`
**Result:** ✅ **21 tests, 21 assertions — ALL PASS**

```
PHPUnit 11.5.56 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.4.6
Configuration: /var/www/html/phpunit.xml

.....................                                             21 / 21 (100%)

Time: 00:00.007, Memory: 8.00 MB

Overdue Calculation (Tests\Unit\OverdueCalculation)
 ✔ Past due date with todo status is overdue
 ✔ Past due date with done status is not overdue
 ✔ Future due date is not overdue
 ✔ In progress with past due date is overdue

Project Validator (Tests\Unit\ProjectValidator)
 ✔ Valid project passes validation
 ✔ Target date before start date fails
 ✔ Same date is valid
 ✔ Invalid status is rejected
 ✔ Missing name is rejected

Task Validator (Tests\Unit\TaskValidator)
 ✔ Invalid status cancelled is rejected
 ✔ Invalid priority urgent is rejected
 ✔ Due date before project start is rejected
 ✔ Due date after project target is rejected
 ✔ Valid task passes validation
 ✔ Valid status update passes
 ✔ Invalid status update fails

User Validator (Tests\Unit\UserValidator)
 ✔ Invalid email format is rejected
 ✔ Duplicate email is rejected
 ✔ Invalid role is rejected
 ✔ Short password is rejected
 ✔ Valid user passes validation

OK (21 tests, 21 assertions)
```

## Test Coverage Summary

| Test Class | Area | Tests | Pass |
|---|---|---|---|
| OverdueCalculationTest | Overdue calculation business rule | 4 | 4 ✅ |
| ProjectValidatorTest | Project date validation, status enum | 5 | 5 ✅ |
| TaskValidatorTest | Task status/priority enum, due_date range | 7 | 7 ✅ |
| UserValidatorTest | Email format, role enum, password length | 5 | 5 ✅ |
| **Total** | **4 areas** | **21** | **21 ✅** |

## Known Bugs / Limitations

1. **No CSRF protection** — form POST submissions are not CSRF-token protected (out of scope per brief, section 24)
2. **Session files reset on container restart** — file-based PHP sessions are container-local
3. **Carol password in seed** — the inactive user's password hash is the same as other members (this is correct behavior; she simply cannot log in because her account is deactivated)
