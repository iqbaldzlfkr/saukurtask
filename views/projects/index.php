<?php
/**
 * Projects list
 *
 * @var array<int, array<string, mixed>> $projects
 * @var string $search
 * @var string $status
 * @var string|null $success
 * @var string|null $error
 */
$projects = $projects ?? [];
$search   = $search ?? '';
$status   = $status ?? '';
$success  = $success ?? null;
$error    = $error ?? null;

$pageTitle = 'Projects';
require __DIR__ . '/../layouts/base.php';

$isAdmin = \App\Core\Auth::isAdmin();
?>

<?php if ($success): ?>
  <div class="alert alert-success" data-auto-dismiss role="alert">
    <i data-lucide="check-circle-2"></i> <?= e($success) ?>
  </div>
<?php endif; ?>
<?php if ($error): ?>
  <div class="alert alert-error" data-auto-dismiss role="alert">
    <i data-lucide="alert-circle"></i> <?= e($error) ?>
  </div>
<?php endif; ?>

<div class="page-header">
  <div>
    <h2 class="page-header-title">Projects</h2>
    <p class="page-header-sub">
      <?= $isAdmin ? 'All projects' : 'Projects with your assigned tasks' ?>
    </p>
  </div>
  <?php if ($isAdmin): ?>
    <a href="?page=projects&action=create" class="btn btn-primary" id="btn-create-project">
      <i data-lucide="plus"></i> New Project
    </a>
  <?php endif; ?>
</div>

<!-- Filter bar -->
<form method="GET" action="" class="filter-bar" role="search">
  <input type="hidden" name="page" value="projects" />
  <div class="form-group">
    <label for="search">Search by name</label>
    <input type="text" id="search" name="search"
           value="<?= e($search) ?>"
           placeholder="Project name…" />
  </div>
  <div class="form-group">
    <label for="status">Status</label>
    <select id="status" name="status">
      <option value="">All statuses</option>
      <?php foreach (['Planning','Active','Completed','Archived'] as $s): ?>
        <option value="<?= e($s) ?>" <?= $status === $s ? 'selected' : '' ?>><?= e($s) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <button type="submit" class="btn btn-primary">
    <i data-lucide="search"></i> Filter
  </button>
  <?php if ($search || $status): ?>
    <a href="?page=projects" class="btn btn-secondary">
      <i data-lucide="x"></i> Clear
    </a>
  <?php endif; ?>
</form>

<?php if (empty($projects)): ?>
  <div class="card">
    <div class="empty-state">
      <div class="empty-state-icon">
        <i data-lucide="folder-open" style="width:48px;height:48px;color:var(--color-primary);"></i>
      </div>
      <div class="empty-state-title">No projects found</div>
      <div class="empty-state-desc">
        <?php if ($search || $status): ?>
          No projects match your current filters. Try adjusting the search.
        <?php elseif ($isAdmin): ?>
          No projects yet. Create your first project to get started.
        <?php else: ?>
          You have no assigned tasks in any project yet.
        <?php endif; ?>
      </div>
      <?php if ($isAdmin): ?>
        <a href="?page=projects&action=create" class="btn btn-primary">
          <i data-lucide="plus"></i> New Project
        </a>
      <?php endif; ?>
    </div>
  </div>
<?php else: ?>
  <div class="table-wrapper">
    <table class="table-responsive" aria-label="Projects list">
      <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Status</th>
          <th scope="col">Start Date</th>
          <th scope="col">Target Date</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($projects as $p): ?>
        <tr>
          <td data-label="Name">
            <a href="?page=projects&action=detail&id=<?= (int) $p['id'] ?>">
              <strong><?= e($p['name']) ?></strong>
            </a>
            <?php if (!empty($p['description'])): ?>
              <div class="text-muted text-xs" style="margin-top:2px;">
                <?= e(mb_substr($p['description'], 0, 60)) ?><?= strlen($p['description']) > 60 ? '…' : '' ?>
              </div>
            <?php endif; ?>
          </td>
          <td data-label="Status"><?= statusBadge($p['status']) ?></td>
          <td data-label="Start"><?= e(date('d M Y', strtotime($p['start_date']))) ?></td>
          <td data-label="Target"><?= e(date('d M Y', strtotime($p['target_date']))) ?></td>
          <td data-label="Actions">
            <div style="display:flex;gap:6px;flex-wrap:wrap;">
              <a href="?page=projects&action=detail&id=<?= (int) $p['id'] ?>"
                 class="btn btn-secondary btn-xs">
                <i data-lucide="eye"></i> View
              </a>
              <?php if ($isAdmin): ?>
                <a href="?page=projects&action=edit&id=<?= (int) $p['id'] ?>"
                   class="btn btn-secondary btn-xs"
                   aria-label="Edit <?= e($p['name']) ?>">
                  <i data-lucide="pencil"></i> Edit
                </a>
                <?php if ($p['status'] !== 'Archived'): ?>
                  <a href="?page=projects&action=archive&id=<?= (int) $p['id'] ?>"
                     class="btn btn-warning btn-xs"
                     data-confirm="Archive project '<?= e($p['name']) ?>'? This cannot be undone."
                     aria-label="Archive <?= e($p['name']) ?>">
                    <i data-lucide="archive"></i> Archive
                  </a>
                <?php endif; ?>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <p class="pagination-info"><?= count($projects) ?> project(s)</p>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
