# AI Usage Log — Saukur Task

**Project:** Project Activity & Task Management System
**Author:** Neuronworks Junior Programmer Participant
**Date Range:** August 31, 2026 – October 8, 2026

---

## Disclosure

This project used AI assistance (Antigravity / Gemini) during initial code generation.
All AI output has been reviewed, verified against requirements, and tested by the developer.

---

## AI Tool Used

| Tool | Purpose |
|---|---|
| Antigravity IDE (Gemini / Claude) | Implementation planning, code scaffolding, initial file generation |

---

## Usage Log

### Session 1 — Implementation Planning
**Date:** 2026-08-31
**Prompt summary:** Provided the full project brief and asked for an implementation plan covering architecture, directory structure, database schema, routing, and phased execution order.
**Output used:** Implementation plan document (Phase 1–13 structure, database schema design, routing pattern decision).
**Output rejected:** None.
**Developer changes:** Adjusted routing to use `?pg=` for pagination parameter to avoid conflict with `?page=` route parameter.
**Verification:** Plan reviewed against all 25+ acceptance criteria in the brief.

---

### Session 2 — Core Infrastructure Generation
**Date:** 2026-08-31
**Prompt summary:** Asked AI to generate Core PHP classes (Database, Router, Session, Auth, Request), Dockerfile, compose.yaml, schema.sql, seed.sql.
**Output used:** All core infrastructure files.
**Output rejected:** Initial TaskController updateStatus method had a logic bug (admin ownership bypass was incorrect).
**Developer changes:**
- Fixed `updateStatus()` to cleanly separate Admin vs Member path without relying on service-level ownership check for Admin.
- Changed `Request::get('page')` to `Request::get('pg')` for pagination to avoid route conflict.
**Verification:** Ran `docker compose up --build`, verified schema created and seed applied.

---

### Session 3 — Unit Tests
**Date:** 2026-08-31
**Prompt summary:** Asked AI to generate PHPUnit test cases for project date validation, task status/priority enums, overdue calculation, and user input validation.
**Output used:** All 4 test classes (21 test cases).
**Output rejected:** None.
**Developer changes:** None required — all tests passed on first run.
**Verification:** `./vendor/bin/phpunit --testdox` → 21/21 PASS.

---

### Session 4 — Views / Templates
**Date:** 2026-08-31
**Prompt summary:** Asked AI to generate PHP views for auth, dashboard, users, projects, tasks, and error pages.
**Output used:** All view files as base scaffolding.
**Output rejected:** None completely rejected; all views were reviewed and adjusted.
**Developer changes:**
- Ensured all `htmlspecialchars()` calls were present for every output variable.
- Verified `data-label` attributes were on all `<td>` elements for responsive CSS.
- Checked that role-based action buttons (edit/archive/create) are guarded with `Auth::isAdmin()` checks.
**Verification:** Manually tested each page flow as Admin and Member.

---

## What Was NOT Used from AI

- Business logic decisions (e.g., archive vs. delete, overdue definition) — per the brief
- Database schema decisions (PK, FK, ENUMs, indexes) — designed per requirements
- Security decisions (which functions to use, where to escape) — per requirements

---

## Verification Process

All AI-generated code was verified by:
1. Reading through each file for logic correctness
2. Running PHPUnit (21 tests, all pass)
3. Running full Docker environment
4. Testing Admin login flow → dashboard → project → task
5. Testing Member login flow → restricted views
6. Testing unauthorized access paths (403/404 responses)
7. Testing validation error paths (invalid dates, duplicate email, wrong enum)

---

*No proprietary source code, credentials, secrets, client data, or PII were shared with any AI tool.*
