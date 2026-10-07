-- ============================================================
-- Schema: Project Activity & Task Management System
-- MySQL 8.0
-- Run this from a clean (empty) database state.
-- ============================================================

SET NAMES utf8mb4;
SET time_zone = '+00:00';

-- Drop in reverse dependency order if re-running
DROP TABLE IF EXISTS `tasks`;
DROP TABLE IF EXISTS `projects`;
DROP TABLE IF EXISTS `users`;

-- ============================================================
-- Table: users
-- ============================================================
CREATE TABLE `users` (
    `id`         INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    `name`       VARCHAR(100)      NOT NULL,
    `email`      VARCHAR(150)      NOT NULL,
    `password`   VARCHAR(255)      NOT NULL COMMENT 'bcrypt hash via password_hash()',
    `role`       ENUM('Admin','Member') NOT NULL DEFAULT 'Member',
    `is_active`  TINYINT(1)        NOT NULL DEFAULT 1 COMMENT '1=active, 0=deactivated (soft delete)',
    `created_at` TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_users_email` (`email`),
    INDEX  `idx_users_role`      (`role`),
    INDEX  `idx_users_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: projects
-- ============================================================
CREATE TABLE `projects` (
    `id`          INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(150)      NOT NULL,
    `description` TEXT,
    `status`      ENUM('Planning','Active','Completed','Archived') NOT NULL DEFAULT 'Planning',
    `start_date`  DATE              NOT NULL,
    `target_date` DATE              NOT NULL COMMENT 'Must be >= start_date (enforced at app level)',
    `created_at`  TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    INDEX `idx_projects_status`      (`status`),
    INDEX `idx_projects_start_date`  (`start_date`),
    INDEX `idx_projects_target_date` (`target_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: tasks
-- ============================================================
CREATE TABLE `tasks` (
    `id`          INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    `project_id`  INT UNSIGNED      NOT NULL,
    `title`       VARCHAR(200)      NOT NULL,
    `description` TEXT,
    `assignee_id` INT UNSIGNED      NOT NULL,
    `status`      ENUM('To Do','In Progress','Done') NOT NULL DEFAULT 'To Do',
    `priority`    ENUM('Low','Medium','High')         NOT NULL DEFAULT 'Medium',
    `due_date`    DATE              NOT NULL COMMENT 'Must be within project start_date..target_date',
    `created_at`  TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    CONSTRAINT `fk_tasks_project`  FOREIGN KEY (`project_id`)  REFERENCES `projects`(`id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_tasks_assignee` FOREIGN KEY (`assignee_id`) REFERENCES `users`(`id`)    ON DELETE RESTRICT,

    INDEX `idx_tasks_project_id`  (`project_id`),
    INDEX `idx_tasks_assignee_id` (`assignee_id`),
    INDEX `idx_tasks_status`      (`status`),
    INDEX `idx_tasks_priority`    (`priority`),
    INDEX `idx_tasks_due_date`    (`due_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
