<?php
/**
 * Member Dashboard — only shows data for the logged-in member.
 */
$pageTitle = 'My Dashboard';
require __DIR__ . '/../layouts/base.php';

$byStatus = $taskStats['by_status'];
$overdue  = $taskStats['overdue'];
$nearest  = $taskStats['nearest'];

function e(string $val): string {
    return htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
}
function statusBadge(string $s): string {
    $map = ['To Do'=>'todo','In Progress'=>'inprogress','Done'=>'done'];
    return '<span class="badge badge-' . ($map[$s]??'todo') . '">' . e($s) . '</span>';
}
function priorityBadge(string $p): string {
    $map = ['Low'=>'low','Medium'=>'medium','High'=>'high'];
    return '<span class="badge badge-' . ($map[$p]??'low') . '">' . e($p) . '</span>';
}
?>

<div class="alert alert-info" style="margin-bottom:24px;">
  <i data-lucide="sparkles"></i> Welcome back, <strong><?= e(\App\Core\Auth::user()['name']) ?></strong>!
  Here's a summary of <em>your</em> assigned tasks.
</div>

<!-- Stats row -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon blue" aria-hidden="true">
      <i data-lucide="list-todo" style="color:var(--color-info);"></i>
    </div>
    <div class="stat-body">
      <div class="stat-value"><?= (int) ($byStatus['To Do'] ?? 0) ?></div>
      <div class="stat-label">My To Do</div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon yellow" aria-hidden="true">
      <i data-lucide="clock" style="color:var(--color-warning);"></i>
    </div>
    <div class="stat-body">
      <div class="stat-value"><?= (int) ($byStatus['In Progress'] ?? 0) ?></div>
      <div class="stat-label">My In Progress</div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon green" aria-hidden="true">
      <i data-lucide="check-circle-2" style="color:var(--color-success);"></i>
    </div>
    <div class="stat-body">
      <div class="stat-value"><?= (int) ($byStatus['Done'] ?? 0) ?></div>
      <div class="stat-label">My Completed</div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon red" aria-hidden="true">
      <i data-lucide="alert-octagon" style="color:var(--color-danger);"></i>
    </div>
    <div class="stat-body">
      <div class="stat-value"><?= (int) $overdue ?></div>
      <div class="stat-label">My Overdue</div>
    </div>
  </div>
</div>

<!-- Nearest Due Tasks -->
<div class="card">
  <div class="card-header">
    <h2 class="card-title" style="display:flex;align-items:center;gap:8px;">
      <i data-lucide="calendar-clock" style="color:var(--color-warning);"></i> My Upcoming Deadlines
    </h2>
    <a href="?page=tasks&sort_due=ASC" class="btn btn-secondary btn-sm">
      <i data-lucide="arrow-right"></i> View all my tasks
    </a>
  </div>

  <?php if (empty($nearest)): ?>
    <div class="empty-state">
      <div class="empty-state-icon">
        <i data-lucide="party-popper" style="width:48px;height:48px;color:var(--color-primary);"></i>
      </div>
      <div class="empty-state-title">No upcoming tasks!</div>
      <div class="empty-state-desc">You have no pending tasks. Great work!</div>
    </div>
  <?php else: ?>
    <div class="table-wrapper">
      <table class="table-responsive" aria-label="Your upcoming tasks">
        <thead>
          <tr>
            <th scope="col">Task</th>
            <th scope="col">Project</th>
            <th scope="col">Priority</th>
            <th scope="col">Status</th>
            <th scope="col">Due Date</th>
            <th scope="col">Action</th>
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
            <td data-label="Priority"><?= priorityBadge($task['priority']) ?></td>
            <td data-label="Status"><?= statusBadge($task['status']) ?></td>
            <td data-label="Due Date"><?= e(date('d M Y', strtotime($task['due_date']))) ?></td>
            <td data-label="Action">
              <a href="?page=tasks&action=detail&id=<?= (int) $task['id'] ?>"
                 class="btn btn-secondary btn-xs">
                Update
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
