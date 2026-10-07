# Test Scenarios

## AUTH — Authentication

| # | Scenario | Steps | Expected |
|---|---|---|---|
| A1 | Admin login — valid | Enter `admin@taskmanager.dev` / `Admin@1234` | Redirect to dashboard |
| A2 | Member login — valid | Enter `iqbal@taskmanager.dev` / `Member@1234` | Redirect to dashboard |
| A3 | Wrong password | Enter correct email, wrong password | Generic error "Invalid email or password" |
| A4 | Wrong email | Enter non-existent email | Same generic error (no field hint) |
| A5 | Inactive user login | Enter `carol@taskmanager.dev` / `Member@1234` | Error "account deactivated" |
| A6 | Access protected URL without session | Open `?page=dashboard` in new tab | Redirect to login |
| A7 | Access protected URL after logout | Logout, press Back, try `?page=dashboard` | Redirect to login |

## USR — User Management

| # | Scenario | Steps | Expected |
|---|---|---|---|
| U1 | Admin creates user | Fill form, valid data | User created, success flash |
| U2 | Duplicate email | Create user with existing email | Error "already in use" |
| U3 | Deactivate user | Click Deactivate on a Member | User becomes inactive |
| U4 | Member accesses `/users` | Login as Member, go to `?page=users` | 403 Access Denied |

## PRJ — Project Management

| # | Scenario | Steps | Expected |
|---|---|---|---|
| P1 | Create project — valid | Fill form, start < target | Project created |
| P2 | Create project — invalid dates | Set target < start | Validation error |
| P3 | Edit project | Change name/status | Changes reflected |
| P4 | Archive project | Click Archive | Status becomes Archived |
| P5 | Member sees own projects | Login as Iqbal | Only projects with Iqbal's tasks |
| P6 | Member cannot see unrelated project | Direct URL with other project's ID | 403 or empty |

## TSK — Task Management

| # | Scenario | Steps | Expected |
|---|---|---|---|
| T1 | Create task — valid | Admin fills form, due within project | Task created |
| T2 | Create task — due out of range | due_date before project start | Validation error |
| T3 | Assign task to member | Select assignee in create form | Task appears in member's list |
| T4 | Member updates own task status | Iqbal changes status of his task | Status updated |
| T5 | Member cannot edit another's task | Bob tries to view/update Iqbal's task detail | 403 |
| T6 | Invalid status submitted | POST `status=Cancelled` | Validation error |

## SRCH — Search / Filter / Pagination

| # | Scenario | Steps | Expected |
|---|---|---|---|
| S1 | Search project by name | Enter partial name | Matching results only |
| S2 | Filter tasks by status | Select "In Progress" | Only In Progress tasks |
| S3 | Sort by due date ASC | Select "Earliest first" | Nearest due first |
| S4 | Page 2 of tasks | Click page 2 | Next 10 tasks, filters preserved |
| S5 | Pagination with filter | Filter by High priority, go to page 2 | High priority tasks, page 2 |

## DASH — Dashboard

| # | Scenario | Steps | Expected |
|---|---|---|---|
| D1 | Admin sees active projects | Login as Admin | Count matches actual active projects |
| D2 | Admin sees task counts | Login as Admin | To Do / In Progress / Done counts from DB |
| D3 | Admin sees overdue count | Login as Admin | Count = tasks with past due_date AND not Done |
| D4 | Member sees only own stats | Login as Iqbal | Counts only for Iqbal's tasks |
| D5 | Overdue task in nearest list | Admin dashboard | Overdue badge shown |
