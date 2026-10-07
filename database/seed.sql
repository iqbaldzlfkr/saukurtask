-- ============================================================
-- Seed Data: Project Activity & Task Management System
-- ============================================================
-- Passwords are bcrypt hashes generated with password_hash('Password@1234', PASSWORD_BCRYPT)
-- Admin:  admin@taskmanager.dev  / Admin@1234
-- Iqbal:  iqbal@taskmanager.dev  / Member@1234
-- Bob:    bob@taskmanager.dev    / Member@1234
-- ============================================================

SET NAMES utf8mb4;

-- Clear existing data (in dependency order)
DELETE FROM `tasks`;
DELETE FROM `projects`;
DELETE FROM `users`;

-- Reset auto-increment
ALTER TABLE `tasks`    AUTO_INCREMENT = 1;
ALTER TABLE `projects` AUTO_INCREMENT = 1;
ALTER TABLE `users`    AUTO_INCREMENT = 1;

-- ============================================================
-- Users (1 Admin + 3 Members)
-- ============================================================
INSERT INTO `users` (`name`, `email`, `password`, `role`, `is_active`) VALUES
-- Admin@1234
('Admin User',    'admin@taskmanager.dev', '$2y$12$zQviTDxOh7Ez4xNLXY0RA.t5D.ewdReIPfo3K./vf5037dr83EbfK', 'Admin',  1),
-- Member@1234
('Iqbal',         'iqbal@taskmanager.dev', '$2y$12$p8zT2WnFuUNTyxdz6.zRg.Ek8PDOv7olPUH.FaMmM.P8lXh/e2b4a', 'Member', 1),
('Bob Martinez',  'bob@taskmanager.dev',   '$2y$12$p8zT2WnFuUNTyxdz6.zRg.Ek8PDOv7olPUH.FaMmM.P8lXh/e2b4a', 'Member', 1),
('Carol White',   'carol@taskmanager.dev', '$2y$12$p8zT2WnFuUNTyxdz6.zRg.Ek8PDOv7olPUH.FaMmM.P8lXh/e2b4a', 'Member', 0);
-- Carol is deactivated (tests inactive user cannot login)

-- ============================================================
-- Projects (4 projects with different statuses)
-- ============================================================
INSERT INTO `projects` (`name`, `description`, `status`, `start_date`, `target_date`) VALUES
('Website Redesign',
 'Complete overhaul of the company website with modern UI and improved performance.',
 'Active',
 '2026-07-01',
 '2026-10-31'),

('Mobile App Development',
 'Native mobile application for iOS and Android platforms for internal team use.',
 'Planning',
 '2026-09-01',
 '2027-03-31'),

('Data Migration Project',
 'Migrate legacy database to new cloud infrastructure with zero downtime.',
 'Completed',
 '2026-04-01',
 '2026-07-31'),

('Security Audit & Hardening',
 'Comprehensive security review and hardening of all production systems.',
 'Active',
 '2026-08-01',
 '2026-11-30');

-- ============================================================
-- Tasks (28 tasks — exactly 2 overdue tasks, others active/done)
-- Current date reference: 2026-09-18
-- Overdue rule: status != 'Done' AND due_date < CURDATE()
-- IDs: users: admin=1, iqbal=2, bob=3, carol=4
--      projects: 1=Website, 2=Mobile, 3=DataMigration, 4=Security
-- ============================================================
INSERT INTO `tasks` (`project_id`, `title`, `description`, `assignee_id`, `status`, `priority`, `due_date`) VALUES

-- ===== Project 1: Website Redesign (Active, 2026-07-01 to 2026-10-31) =====
(1, 'Create wireframes for homepage',
 'Design wireframes for the new homepage layout including hero, features, and footer.',
 2, 'Done', 'High', '2026-07-20'),

(1, 'Design color palette and typography',
 'Select brand colors, font families, and establish the design system.',
 2, 'Done', 'Medium', '2026-07-25'),

(1, 'Build responsive navigation component',
 'Implement mobile-friendly navigation with hamburger menu.',
 3, 'Done', 'High', '2026-08-05'),

-- OVERDUE TASK #1 (Assignee: Iqbal, Status: In Progress, Due: 2026-09-10 < 2026-09-18)
(1, 'Implement hero section',
 'Build the homepage hero with animated background and CTA buttons.',
 2, 'In Progress', 'High', '2026-09-10'),

-- OVERDUE TASK #2 (Assignee: Bob, Status: In Progress, Due: 2026-09-12 < 2026-09-18)
(1, 'Build features section',
 'Create the product features grid with icons and descriptions.',
 3, 'In Progress', 'Medium', '2026-09-12'),

(1, 'Write SEO meta tags for all pages',
 'Add proper title, description, and Open Graph tags to all public pages.',
 2, 'To Do', 'Low', '2026-09-25'),

(1, 'Performance optimization pass',
 'Compress images, enable lazy loading, and optimize JavaScript bundles.',
 3, 'To Do', 'Medium', '2026-10-05'),

(1, 'Cross-browser testing',
 'Test on Chrome, Firefox, Safari, and Edge. Document any visual discrepancies.',
 2, 'To Do', 'High', '2026-10-18'),

(1, 'Final QA and content review',
 'End-to-end review of all pages for content accuracy and visual consistency.',
 3, 'To Do', 'High', '2026-10-28'),

(1, 'Set up development environment',
 'Install Node.js, configure Webpack, and set up local dev server.',
 2, 'Done', 'High', '2026-07-10'),

(1, 'Create sitemap and information architecture',
 'Document all pages and their hierarchy before design begins.',
 3, 'Done', 'Medium', '2026-07-15'),

-- ===== Project 2: Mobile App Development (Planning, 2026-09-01 to 2027-03-31) =====
(2, 'Define app requirements document',
 'Gather all functional and non-functional requirements from stakeholders.',
 2, 'To Do', 'High', '2026-09-25'),

(2, 'Create technical architecture diagram',
 'Design the system architecture including backend APIs and data flow.',
 3, 'To Do', 'High', '2026-09-30'),

(2, 'Set up React Native project structure',
 'Initialize project with proper folder structure and CI/CD pipeline.',
 2, 'To Do', 'Medium', '2026-10-10'),

(2, 'Design onboarding screens',
 'Create UI/UX for the user onboarding flow including login and signup.',
 3, 'To Do', 'Medium', '2026-10-20'),

(2, 'Implement push notification service',
 'Integrate Firebase Cloud Messaging for iOS and Android notifications.',
 2, 'To Do', 'Low', '2026-11-05'),

(2, 'Write unit tests for core business logic',
 'Achieve at least 80% code coverage on service layer functions.',
 3, 'To Do', 'Medium', '2026-11-25'),

-- ===== Project 3: Data Migration (Completed, 2026-04-01 to 2026-07-31) =====
(3, 'Audit existing data schema',
 'Document all existing tables, relationships, and data volumes.',
 2, 'Done', 'High', '2026-04-15'),

(3, 'Write ETL migration scripts',
 'Develop Extract-Transform-Load scripts for each data entity.',
 3, 'Done', 'High', '2026-05-30'),

(3, 'Test migration on staging environment',
 'Run full migration dry-run on staging and verify data integrity.',
 2, 'Done', 'High', '2026-06-20'),

(3, 'Execute production migration',
 'Perform live migration with rollback plan ready.',
 3, 'Done', 'High', '2026-07-10'),

(3, 'Post-migration validation report',
 'Validate all records, generate diff report, and sign off.',
 2, 'Done', 'Medium', '2026-07-25'),

-- ===== Project 4: Security Audit (Active, 2026-08-01 to 2026-11-30) =====
(4, 'Conduct vulnerability assessment',
 'Use OWASP ZAP and manual testing to identify vulnerabilities.',
 3, 'Done', 'High', '2026-08-20'),

(4, 'Review authentication and session management',
 'Audit all login flows, session handling, and token management.',
 2, 'Done', 'High', '2026-09-05'),

(4, 'Document findings in security report',
 'Compile all vulnerabilities with CVSS scores and remediation steps.',
 3, 'In Progress', 'Medium', '2026-09-28'),

(4, 'Implement rate limiting on all endpoints',
 'Add IP-based and user-based rate limiting to prevent brute force.',
 2, 'To Do', 'High', '2026-10-12'),

(4, 'Security awareness training session',
 'Conduct training for all team members on secure coding practices.',
 3, 'To Do', 'Low', '2026-10-31'),

(4, 'Patch identified critical vulnerabilities',
 'Fix all critical and high-severity vulnerabilities found in the audit.',
 2, 'In Progress', 'High', '2026-09-22');
