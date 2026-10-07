<?php
/**
 * Task detail page
 * Variables: $task (includes project_name, assignee_name, project_start, project_target)
 */
$pageTitle = htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') . ' — Task';
require __DIR__ . '/../layouts/base.php';

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

$isAdmin   = \App\Core\Auth::isAdmin();
$isMine    = (\App\Core\Auth::id() === (int) $task['assignee_id']);
$isOverdue = ($task['status'] !== 'Done' && strtotime($task['due_date']) < strtotime('today'));
$success   = \App\Core\Session::getFlash('success');
$error     = \App\Core\Session::getFlash('error');
?>

<?php if ($success): ?>
  <div class="alert alert-success" data-auto-dismiss>
    <i data-lucide="check-circle-2"></i> <?= e($success) ?>
  </div>
<?php endif; ?>
<?php if ($error): ?>
  <div class="alert alert-error" data-auto-dismiss>
    <i data-lucide="alert-circle"></i> <?= e($error) ?>
  </div>
<?php endif; ?>

<div class="page-header">
  <div>
    <h2 class="page-header-title"><?= e($task['title']) ?></h2>
    <p class="page-header-sub">
      Task in <a href="?page=projects&action=detail&id=<?= (int) $task['project_id'] ?>">
        <?= e($task['project_name']) ?>
      </a>
    </p>
  </div>
  <div style="display:flex;gap:8px;flex-wrap:wrap;">
    <a href="?page=tasks" class="btn btn-secondary">
      <i data-lucide="arrow-left"></i> Tasks
    </a>
    <?php if ($isAdmin): ?>
      <a href="?page=tasks&action=edit&id=<?= (int) $task['id'] ?>" class="btn btn-primary">
        <i data-lucide="pencil"></i> Edit Task
      </a>
    <?php endif; ?>
  </div>
</div>

<?php if ($isOverdue): ?>
  <div class="alert alert-error">
    <i data-lucide="alert-triangle"></i> This task is <strong>overdue</strong>! Due date was <?= e(date('d M Y', strtotime($task['due_date']))) ?>.
  </div>
<?php endif; ?>

<div class="card mb-24">
  <div class="info-grid">
    <div class="info-item">
      <label>Status</label>
      <div class="info-value"><?= statusBadge($task['status']) ?></div>
    </div>
    <div class="info-item">
      <label>Priority</label>
      <div class="info-value"><?= priorityBadge($task['priority']) ?></div>
    </div>
    <div class="info-item">
      <label>Assigned To</label>
      <div class="info-value"><?= e($task['assignee_name']) ?></div>
    </div>
    <div class="info-item">
      <label>Due Date</label>
      <div class="info-value <?= $isOverdue ? 'text-danger fw-600' : '' ?>">
        <?= e(date('d M Y', strtotime($task['due_date']))) ?>
      </div>
    </div>
    <div class="info-item">
      <label>Project Dates</label>
      <div class="info-value text-muted text-small">
        <?= e(date('d M Y', strtotime($task['project_start']))) ?>
        → <?= e(date('d M Y', strtotime($task['project_target']))) ?>
      </div>
    </div>
    <div class="info-item">
      <label>Created</label>
      <div class="info-value"><?= e(date('d M Y', strtotime($task['created_at']))) ?></div>
    </div>
  </div>

  <?php if (!empty($task['description'])): ?>
    <hr class="divider" />
    <div>
      <label style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.6px;
                    color:var(--color-text-dim);display:block;margin-bottom:8px;">Description</label>
      <p style="color:var(--color-text);line-height:1.7;font-size:14px;">
        <?= nl2br(e($task['description'])) ?>
      </p>
    </div>
  <?php endif; ?>
</div>

<!-- Status Update (Member: own task only | Admin: any) -->
<?php if ($isAdmin || $isMine): ?>
<div class="card">
  <h2 class="card-title" style="margin-bottom:16px;">Update Status</h2>
  <form method="POST" action="?page=tasks&action=updateStatus" data-status-form>
    <input type="hidden" name="task_id" value="<?= (int) $task['id'] ?>" />
    <div style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
      <div class="form-group" style="margin-bottom:0;flex:1;min-width:180px;">
        <label for="status-select">New Status</label>
        <select id="status-select" name="status">
          <?php foreach (['To Do','In Progress','Done'] as $s): ?>
            <option value="<?= e($s) ?>" <?= $task['status'] === $s ? 'selected' : '' ?>><?= e($s) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="btn btn-primary" id="btn-update-status">
        <i data-lucide="save"></i> Update Status
      </button>
    </div>
  </form>
</div>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
