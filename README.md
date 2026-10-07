# Saukur Task — Project Activity & Task Management System

> Final Project — Neuronworks Junior Programmer Program

A web application for managing projects and tasks, built with **PHP 8.2 Native OOP**, **MySQL 8**, and **Docker**.

---

## Features

- **Authentication** — Email/password login with secure sessions and role-based access control
- **Two Roles** — Admin (full access) and Member (own tasks only)
- **User Management** — Admin CRUD, activate/deactivate, email uniqueness enforcement
- **Project Management** — Create, edit, archive projects; date validation; role-scoped views
- **Task Management** — Create, edit, assign tasks; status/priority enums; due-date-in-project-range validation
- **Dashboard** — Role-appropriate stats (active projects, task counts by status, overdue count, nearest due tasks) — all from DB aggregation
- **Search / Filter / Sort / Pagination** — Projects: search + status filter; Tasks: search + project/status/priority filter + due-date sort; 10 tasks/page from DB `LIMIT/OFFSET`
- **Security** — `password_hash`, PDO prepared statements, server-side authorization, `htmlspecialchars` output escaping, session ID regeneration
- **Unit Tests** — 21 PHPUnit tests across 4 test classes covering 4 business logic areas

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.2+, Native OOP, PDO |
| Frontend | HTML5, Custom CSS, Vanilla JS |
| Database | MySQL 8 |
| Environment | Docker + Docker Compose |
| Testing | PHPUnit 11 |
| Dependencies | Composer (autoload + PHPUnit only) |

---

## Requirements

- Docker Desktop (or Docker Engine + Compose v2)
- No PHP, MySQL, or Composer needed locally — everything runs in containers

---

## Installation & Setup

### 1. Clone / get the project

```bash
git clone <your-repo-url>
cd taskmanager
```

### 2. Copy environment file

```bash
cp .env.example .env
```

Edit `.env` if you need to change ports. The defaults work out of the box:

```
APP_PORT=8080
DB_EXTERNAL_PORT=3307
```

### 3. Start with Docker Compose

```bash
docker compose up --build
```

This will:
1. Build the PHP 8.2-Apache application image
2. Start a MySQL 8 database container
3. Automatically run `database/schema.sql` (create tables) and `database/seed.sql` (insert demo data)
4. Make the app available at **http://localhost:8080**

> First startup may take 30–60 seconds for MySQL to initialize.

---

## Demo Accounts

| Role | Email | Password |
|---|---|---|
| Admin | `admin@taskmanager.dev` | `Admin@1234` |
| Member | `iqbal@taskmanager.dev` | `Member@1234` |
| Member | `bob@taskmanager.dev` | `Member@1234` |
| Member (inactive) | `carol@taskmanager.dev` | *(cannot login — deactivated)* |

---

## Application Flow

```
Login → Dashboard → Projects → Tasks → Update Task Status → Logout
```

**As Admin:**
1. Login → see full dashboard with all stats
2. Create / edit / archive projects
3. Create / edit / assign tasks to Members
4. Manage users (create, edit, activate/deactivate)

**As Member:**
1. Login → see personal dashboard (own tasks only)
2. View projects that have tasks assigned to you
3. View and update the status of your own tasks

---

## Running Unit Tests

```bash
docker compose exec app ./vendor/bin/phpunit --testdox
```

Or locally (if PHP 8.2+ is installed):

```bash
./vendor/bin/phpunit --testdox
```

Expected output: **21 tests, 21 assertions, all PASS**.

Test evidence is in `docs/testing/test-results.md`.

---

## Database Setup

### Schema + Seed (automatic)
Docker Compose mounts and auto-runs:
- `database/schema.sql` — creates tables
- `database/seed.sql` — inserts demo data

### Manual reset
```bash
# Connect to MySQL container
docker compose exec db mysql -u taskmanager -ptaskmanager_secret taskmanager

# In MySQL shell:
source /docker-entrypoint-initdb.d/01-schema.sql
source /docker-entrypoint-initdb.d/02-seed.sql
```

### Full reset (delete all data)
```bash
docker compose down -v        # removes DB volume
docker compose up --build     # rebuilds from scratch
```

---

## Ports

| Service | Port |
|---|---|
| Web application | `http://localhost:8080` |
| MySQL (external) | `localhost:3307` |

---

## Project Structure

```
taskmanager/
├── public/             ← Web root (document root)
│   ├── index.php       ← Front controller (single entry point)
│   └── assets/         ← CSS + JS
├── app/
│   ├── Core/           ← Router, Database, Session, Auth, Request
│   ├── Controllers/    ← HTTP request handlers
│   ├── Services/       ← Business logic
│   ├── Repositories/   ← Database access (PDO)
│   ├── Models/         ← Data models (value objects)
│   └── Validators/     ← Input validation
├── views/              ← PHP HTML templates
├── config/             ← Application config
├── database/           ← schema.sql + seed.sql
├── tests/Unit/         ← PHPUnit unit tests
├── docs/               ← Planning + testing documentation
├── Dockerfile
├── compose.yaml
└── .env.example
```

---

## Stopping the Application

```bash
docker compose down          # stop containers, keep DB data
docker compose down -v       # stop + delete all data (full reset)
```

---

## Known Limitations

1. **No CSRF protection** — POST forms are not CSRF-protected (bonus feature, not required by brief)
2. **No pagination for projects** — project list has no pagination (only tasks require 10/page pagination per the brief)
3. **No file upload** — project/task attachments are out of scope
4. **Carol (inactive user) password** — the inactive demo account uses the same hash as other Members; login is blocked at the application level, not the password level
5. **Session storage** — sessions use PHP's default file-based storage, which is reset on container restart

---

## Security Notes

- Passwords stored as bcrypt hashes via `password_hash()` — never plaintext
- All database queries use PDO prepared statements — no SQL injection risk
- All user output is escaped with `htmlspecialchars()` — no XSS risk
- Authorization checked server-side in every Controller method
- Session ID regenerated after login (`session_regenerate_id(true)`)
- `.env` is in `.gitignore` — no credentials committed
