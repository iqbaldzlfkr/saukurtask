# Entity Relationship Diagram

## ERD (Text notation)

```
┌──────────────────────────────┐
│           users              │
├──────────────────────────────┤
│ PK  id          INT UNSIGNED │
│     name        VARCHAR(100) │
│ UQ  email       VARCHAR(150) │
│     password    VARCHAR(255) │
│     role        ENUM          │
│     is_active   TINYINT(1)   │
│     created_at  TIMESTAMP    │
│     updated_at  TIMESTAMP    │
└──────────────────────────────┘
       ↑                  ↑
       │ FK               │ FK
  assignee_id         (admin creates)
       │
┌──────────────────────────────┐        ┌──────────────────────────────┐
│           tasks              │        │          projects             │
├──────────────────────────────┤        ├──────────────────────────────┤
│ PK  id          INT UNSIGNED │        │ PK  id          INT UNSIGNED │
│ FK  project_id  INT UNSIGNED │───────▶│     name        VARCHAR(150) │
│     title       VARCHAR(200) │        │     description TEXT         │
│     description TEXT         │        │     status      ENUM          │
│ FK  assignee_id INT UNSIGNED │        │     start_date  DATE         │
│     status      ENUM          │        │     target_date DATE         │
│     priority    ENUM          │        │     created_at  TIMESTAMP    │
│     due_date    DATE          │        │     updated_at  TIMESTAMP    │
│     created_at  TIMESTAMP    │        └──────────────────────────────┘
│     updated_at  TIMESTAMP    │
└──────────────────────────────┘
```

## Relationships

| Relationship | Type | Description |
|---|---|---|
| tasks.assignee_id → users.id | Many-to-One | Each task is assigned to one user |
| tasks.project_id → projects.id | Many-to-One | Each task belongs to one project |

## Business Rules (enforced at application layer)

- `tasks.due_date` must be between `project.start_date` and `project.target_date`
- `projects.target_date >= projects.start_date`
- Users are soft-deleted (is_active = 0), never hard deleted
- Projects with tasks cannot be hard deleted — only archived
