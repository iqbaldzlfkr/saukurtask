<?php
/**
 * Project detail page
 * Variables: $project
 */
$pageTitle = htmlspecialchars($project['name'], ENT_QUOTES, 'UTF-8') . ' — Project';
require __DIR__ . '/../layouts/base.php';

function e(string $val): string {
    return htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
}
function statusBadge(string $s): string {
    $map = ['Planning'=>'planning','Active'=>'active','Completed'=>'completed','Archived'=>'archived'];
    return '<span class="badge badge-' . ($map[$s]??'archived') . '">' . e($s) . '</span>';
}
$isAdmin = \App\Core\Auth::isAdmin();
?>

<div class="page-header">
  <div>
    <h2 class="page-header-title"><?= e($project['name']) ?></h2>
    <p class="page-header-sub">Project Detail</p>
  </div>
  <div style="display:flex;gap:8px;flex-wrap:wrap;">
    <a href="?page=projects" class="btn btn-secondary">
      <i data-lucide="arrow-left"></i> Projects
    </a>
    <?php if ($isAdmin): ?>
      <a href="?page=projects&action=edit&id=<?= (int) $project['id'] ?>" class="btn btn-primary">
        <i data-lucide="pencil"></i> Edit
      </a>
      <?php if ($project['status'] !== 'Archived'): ?>
        <a href="?page=projects&action=archive&id=<?= (int) $project['id'] ?>"
           class="btn btn-warning"
           data-confirm="Archive this project?">
          <i data-lucide="archive"></i> Archive
        </a>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>

<div class="card mb-24">
  <div class="info-grid">
    <div class="info-item">
      <label>Status</label>
      <div class="info-value"><?= statusBadge($project['status']) ?></div>
    </div>
    <div class="info-item">
      <label>Start Date</label>
      <div class="info-value"><?= e(date('d M Y', strtotime($project['start_date']))) ?></div>
    </div>
    <div class="info-item">
      <label>Target Date</label>
      <div class="info-value"><?= e(date('d M Y', strtotime($project['target_date']))) ?></div>
    </div>
    <div class="info-item">
      <label>Created</label>
      <div class="info-value"><?= e(date('d M Y', strtotime($project['created_at']))) ?></div>
    </div>
  </div>

  <?php if (!empty($project['description'])): ?>
    <hr class="divider" />
    <div>
      <label style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.6px;
                    color:var(--color-text-dim);display:block;margin-bottom:8px;">
        Description
      </label>
      <p style="color:var(--color-text);line-height:1.7;font-size:14px;">
        <?= nl2br(e($project['description'])) ?>
      </p>
    </div>
  <?php endif; ?>
</div>

<!-- Tasks in this project -->
<div class="card">
  <div class="card-header">
    <h2 class="card-title">Tasks in this Project</h2>
    <?php if ($isAdmin): ?>
      <a href="?page=tasks&action=create" class="btn btn-primary btn-sm">
        <i data-lucide="plus"></i> Add Task
      </a>
    <?php endif; ?>
  </div>
  <p class="text-muted text-small">
    <a href="?page=tasks&project_id=<?= (int) $project['id'] ?>" style="display:inline-flex;align-items:center;gap:4px;">
      View all tasks for this project <i data-lucide="arrow-right" style="width:14px;height:14px;"></i>
    </a>
  </p>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
