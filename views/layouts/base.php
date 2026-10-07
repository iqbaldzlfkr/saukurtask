<?php
/**
 * Base layout — wraps all authenticated pages.
 *
 * Expected variables:
 *  $pageTitle  — string, page title
 *  $activePage — string, current page (for nav highlighting)
 */

use App\Core\Auth;
use App\Core\Session;

$user      = Auth::user();
$initials  = strtoupper(substr($user['name'] ?? 'U', 0, 1));
$pageTitle = $pageTitle ?? 'Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Project Activity & Task Management System — Neuronworks" />
  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?> — Saukur Task</title>
  <link rel="stylesheet" href="/assets/css/app.css" />
</head>
<body>
<div class="app-layout">

  <!-- Sidebar overlay (mobile) -->
  <div class="sidebar-overlay" id="sidebar-overlay" aria-hidden="true"></div>

  <!-- Sidebar -->
  <aside class="sidebar" id="sidebar" aria-label="Main navigation">
    <div class="sidebar-logo">
      <div class="sidebar-logo-icon" aria-hidden="true">
        <i data-lucide="clipboard-check" style="width:20px;height:20px;color:#fff;"></i>
      </div>
      <div class="sidebar-logo-text">
        Saukur Task
        <span>Neuronworks</span>
      </div>
    </div>

    <nav class="sidebar-nav">
      <!-- Main -->
      <div class="nav-section-label">Main</div>

      <a href="?page=dashboard" class="nav-item" data-page="dashboard" aria-label="Dashboard">
        <span class="nav-icon" aria-hidden="true"><i data-lucide="layout-dashboard"></i></span>
        Dashboard
      </a>

      <a href="?page=projects" class="nav-item" data-page="projects" aria-label="Projects">
        <span class="nav-icon" aria-hidden="true"><i data-lucide="folder-kanban"></i></span>
        Projects
      </a>

      <a href="?page=tasks" class="nav-item" data-page="tasks" aria-label="Tasks">
        <span class="nav-icon" aria-hidden="true"><i data-lucide="check-square"></i></span>
        Tasks
      </a>

      <?php if (Auth::isAdmin()): ?>
      <!-- Admin only -->
      <div class="nav-section-label">Administration</div>

      <a href="?page=users" class="nav-item" data-page="users" aria-label="User management">
        <span class="nav-icon" aria-hidden="true"><i data-lucide="users"></i></span>
        Users
      </a>
      <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
      <div class="user-info" aria-label="Logged in user">
        <div class="user-avatar" aria-hidden="true"><?= htmlspecialchars($initials, ENT_QUOTES, 'UTF-8') ?></div>
        <div class="user-details">
          <div class="user-name"><?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?></div>
          <div class="user-role"><?= htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8') ?></div>
        </div>
      </div>
      <button type="button"
              class="btn btn-secondary btn-sm"
              style="width:100%; justify-content:center; gap:8px;"
              id="btn-open-logout-modal"
              aria-label="Log out">
        <i data-lucide="log-out"></i> Logout
      </button>
    </div>
  </aside>

  <!-- Main content -->
  <main class="main-content">
    <header class="top-bar">
      <div style="display:flex;align-items:center;gap:12px;">
        <button class="hamburger" id="hamburger" aria-label="Toggle navigation" aria-expanded="false">
          <i data-lucide="menu"></i>
        </button>
        <h1 class="top-bar-title"><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></h1>
      </div>
      <div class="top-bar-right">
        <span class="text-muted text-small">
          <?= date('D, d M Y') ?>
        </span>
      </div>
    </header>

    <div class="page-content">
      <?php $content_body ?? null; ?>
