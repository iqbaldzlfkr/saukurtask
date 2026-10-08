<?php
/**
 * Admin Dashboard
 *
 * @var int $activeProjectCount
 * @var array<string, mixed> $taskStats
 */
$activeProjectCount = $activeProjectCount ?? 0;
$taskStats          = $taskStats ?? ['by_status' => [], 'overdue' => 0, 'nearest' => []];

$pageTitle = 'Dashboard';
require __DIR__ . '/../layouts/base.php';

$byStatus = $taskStats['by_status'] ?? [];
$overdue  = $taskStats['overdue'] ?? 0;
$nearest  = $taskStats['nearest'] ?? [];
?>

<!-- Stats row -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon purple" aria-hidden="true">
      <i data-lucide="folder-kanban" style="color:var(--color-primary);"></i>
    </div>
    <div class="stat-body">
      <div class="stat-value"><?= (int) $activeProjectCount ?></div>
      <div class="stat-label">Active Projects</div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon blue" aria-hidden="true">
      <i data-lucide="list-todo" style="color:var(--color-info);"></i>
    </div>
    <div class="stat-body">
      <div class="stat-value"><?= (int) ($byStatus['To Do'] ?? 0) ?></div>
      <div class="stat-label">Tasks — To Do</div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon yellow" aria-hidden="true">
      <i data-lucide="clock" style="color:var(--color-warning);"></i>
    </div>
    <div class="stat-body">
      <div class="stat-value"><?= (int) ($byStatus['In Progress'] ?? 0) ?></div>
      <div class="stat-label">Tasks — In Progress</div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon green" aria-hidden="true">
      <i data-lucide="check-circle-2" style="color:var(--color-success);"></i>
    </div>
    <div class="stat-body">
      <div class="stat-value"><?= (int) ($byStatus['Done'] ?? 0) ?></div>
      <div class="stat-label">Tasks — Done</div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon red" aria-hidden="true">
      <i data-lucide="alert-octagon" style="color:var(--color-danger);"></i>
    </div>
    <div class="stat-body">
      <div class="stat-value"><?= (int) $overdue ?></div>
      <div class="stat-label">Overdue Tasks</div>
    </div>
  </div>
</div>

<!-- Nearest Due Tasks -->
<div class="card">
  <div class="card-header">
    <h2 class="card-title" style="display:flex;align-items:center;gap:8px;">
      <i data-lucide="calendar-clock" style="color:var(--color-warning);"></i> Upcoming Deadlines
    </h2>
    <a href="?page=tasks&sort_due=ASC" class="btn btn-secondary btn-sm">
      <i data-lucide="arrow-right"></i> View all tasks
    </a>
  </div>

  <?php if (empty($nearest)): ?>
    <div class="empty-state">
      <div class="empty-state-icon">
        <i data-lucide="party-popper" style="width:48px;height:48px;color:var(--color-primary);"></i>
      </div>
      <div class="empty-state-title">No upcoming tasks</div>
      <div class="empty-state-desc">All tasks are completed or there are no tasks yet.</div>
    </div>
  <?php else: ?>
    <div class="table-wrapper">
      <table aria-label="Upcoming due tasks">
        <thead>
          <tr>
            <th scope="col">Task</th>
            <th scope="col">Project</th>
            <th scope="col">Assigned To</th>
            <th scope="col">Priority</th>
            <th scope="col">Status</th>
            <th scope="col">Due Date</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($nearest as $task):
            $isOverdue = ($task['status'] !== 'Done' && strtotime($task['due_date']) < strtotime('today'));
          ?>
          <tr class="<?= $isOverdue ? 'overdue-row' : '' ?>">
            <td data-label="Task">
              <a href="?page=tasks&action=detail&id=<?= (int) $task['id'] ?>">
                <?= e($task['title']) ?>
              </a>
              <?php if ($isOverdue): ?>
                <span class="badge badge-overdue" style="margin-left:6px;">Overdue</span>
              <?php endif; ?>
            </td>
            <td data-label="Project"><?= e($task['project_name']) ?></td>
            <td data-label="Assigned To"><?= e($task['assignee_name']) ?></td>
            <td data-label="Priority"><?= priorityBadge($task['priority']) ?></td>
            <td data-label="Status"><?= statusBadge($task['status']) ?></td>
            <td data-label="Due Date"><?= e(date('d M Y', strtotime($task['due_date']))) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
