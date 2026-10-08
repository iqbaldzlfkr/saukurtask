<?php
/**
 * Tasks list with search, filter, sort, pagination
 *
 * @var array<string, mixed> $result
 * @var array<string, string> $filters
 * @var array<int, array<string, mixed>> $projects
 * @var string|null $success
 * @var string|null $error
 */
$result   = $result ?? ['tasks' => [], 'total' => 0, 'pages' => 1, 'page' => 1];
$filters  = $filters ?? [];
$projects = $projects ?? [];
$success  = $success ?? null;
$error    = $error ?? null;

$pageTitle = 'Tasks';
require __DIR__ . '/../layouts/base.php';

$tasks       = $result['tasks'] ?? [];
$total       = $result['total'] ?? 0;
$pages       = $result['pages'] ?? 1;
$currentPage = $result['page'] ?? 1;
$isAdmin     = \App\Core\Auth::isAdmin();
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
    <h2 class="page-header-title">Tasks</h2>
    <p class="page-header-sub">
      <?= $isAdmin ? 'All tasks' : 'Your assigned tasks' ?>
      — <?= $total ?> total
    </p>
  </div>
  <?php if ($isAdmin): ?>
    <a href="?page=tasks&action=create" class="btn btn-primary" id="btn-create-task">
      <i data-lucide="plus"></i> New Task
    </a>
  <?php endif; ?>
</div>

<!-- Filter/sort bar -->
<form method="GET" action="" class="filter-bar" role="search" id="filter-form">
  <input type="hidden" name="page" value="tasks" />

  <div class="form-group">
    <label for="search">Search title</label>
    <input type="text" id="search" name="search"
           value="<?= e($filters['search']) ?>"
           placeholder="Task title…" />
  </div>

  <div class="form-group">
    <label for="project_id">Project</label>
    <select id="project_id" name="project_id">
      <option value="">All projects</option>
      <?php foreach ($projects as $p): ?>
        <option value="<?= (int) $p['id'] ?>"
          <?= ((string)$filters['project_id'] === (string)$p['id']) ? 'selected' : '' ?>>
          <?= e($p['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="form-group">
    <label for="status">Status</label>
    <select id="status" name="status">
      <option value="">All statuses</option>
      <?php foreach (['To Do','In Progress','Done'] as $s): ?>
        <option value="<?= e($s) ?>" <?= $filters['status'] === $s ? 'selected' : '' ?>><?= e($s) ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="form-group">
    <label for="priority">Priority</label>
    <select id="priority" name="priority">
      <option value="">All priorities</option>
      <?php foreach (['Low','Medium','High'] as $p): ?>
        <option value="<?= e($p) ?>" <?= $filters['priority'] === $p ? 'selected' : '' ?>><?= e($p) ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="form-group">
    <label for="sort_due">Sort by Due Date</label>
    <select id="sort_due" name="sort_due">
      <option value="ASC"  <?= $filters['sort_due'] === 'ASC'  ? 'selected' : '' ?>>Earliest first</option>
      <option value="DESC" <?= $filters['sort_due'] === 'DESC' ? 'selected' : '' ?>>Latest first</option>
    </select>
  </div>

  <button type="submit" class="btn btn-primary">
    <i data-lucide="filter"></i> Apply
  </button>
  <?php if (array_filter([$filters['search'],$filters['project_id'],$filters['status'],$filters['priority']])): ?>
    <a href="?page=tasks" class="btn btn-secondary">
      <i data-lucide="x"></i> Clear
    </a>
  <?php endif; ?>
</form>

<?php if (empty($tasks)): ?>
  <div class="card">
    <div class="empty-state">
      <div class="empty-state-icon">
        <i data-lucide="check-square" style="width:48px;height:48px;color:var(--color-primary);"></i>
      </div>
      <div class="empty-state-title">No tasks found</div>
      <div class="empty-state-desc">
        <?php if (array_filter([$filters['search'],$filters['project_id'],$filters['status'],$filters['priority']])): ?>
          No tasks match your filters. Try broadening your search.
        <?php elseif ($isAdmin): ?>
          No tasks created yet. Create the first task!
        <?php else: ?>
          You have no assigned tasks yet.
        <?php endif; ?>
      </div>
      <?php if ($isAdmin): ?>
        <a href="?page=tasks&action=create" class="btn btn-primary">
          <i data-lucide="plus"></i> New Task
        </a>
      <?php endif; ?>
    </div>
  </div>
<?php else: ?>
  <div class="table-wrapper">
    <table class="table-responsive" aria-label="Tasks list">
      <thead>
        <tr>
          <th scope="col">Title</th>
          <th scope="col">Project</th>
          <?php if ($isAdmin): ?><th scope="col">Assignee</th><?php endif; ?>
          <th scope="col">Priority</th>
          <th scope="col">Status</th>
          <th scope="col">Due Date</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($tasks as $task):
          $isOverdue = ($task['status'] !== 'Done' && strtotime($task['due_date']) < strtotime('today'));
        ?>
        <tr class="<?= $isOverdue ? 'overdue-row' : '' ?>">
          <td data-label="Title">
            <a href="?page=tasks&action=detail&id=<?= (int) $task['id'] ?>">
              <?= e($task['title']) ?>
            </a>
            <?php if ($isOverdue): ?>
              <span class="badge badge-overdue" style="margin-left:4px;">Overdue</span>
            <?php endif; ?>
          </td>
          <td data-label="Project"><?= e($task['project_name']) ?></td>
          <?php if ($isAdmin): ?>
            <td data-label="Assignee"><?= e($task['assignee_name']) ?></td>
          <?php endif; ?>
          <td data-label="Priority"><?= priorityBadge($task['priority']) ?></td>
          <td data-label="Status"><?= statusBadge($task['status']) ?></td>
          <td data-label="Due Date">
            <span class="<?= $isOverdue ? 'text-danger fw-600' : '' ?>">
              <?= e(date('d M Y', strtotime($task['due_date']))) ?>
            </span>
          </td>
          <td data-label="Actions">
            <div style="display:flex;gap:6px;flex-wrap:wrap;">
              <a href="?page=tasks&action=detail&id=<?= (int) $task['id'] ?>"
                 class="btn btn-secondary btn-xs">
                <i data-lucide="eye"></i> View
              </a>
              <?php if ($isAdmin): ?>
                <a href="?page=tasks&action=edit&id=<?= (int) $task['id'] ?>"
                   class="btn btn-secondary btn-xs">
                  <i data-lucide="pencil"></i> Edit
                </a>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <?php if ($pages > 1): ?>
    <nav class="pagination" aria-label="Task list pagination">
      <?php
      $baseParams = array_merge($filters, ['page' => 'tasks']);

      // Previous
      if ($currentPage > 1):
        $prevUrl = '?' . http_build_query(array_merge($baseParams, ['pg' => $currentPage - 1]));
      ?>
        <a href="<?= e($prevUrl) ?>" aria-label="Previous page">‹ Prev</a>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $pages; $i++):
        $pageUrl = '?' . http_build_query(array_merge($baseParams, ['pg' => $i]));
        if ($i === $currentPage): ?>
          <span class="current" aria-current="page"><?= $i ?></span>
        <?php elseif ($i === 1 || $i === $pages || abs($i - $currentPage) <= 2): ?>
          <a href="<?= e($pageUrl) ?>"><?= $i ?></a>
        <?php elseif (abs($i - $currentPage) === 3): ?>
          <span class="dots">…</span>
        <?php endif;
      endfor; ?>

      <?php if ($currentPage < $pages):
        $nextUrl = '?' . http_build_query(array_merge($baseParams, ['pg' => $currentPage + 1]));
      ?>
        <a href="<?= e($nextUrl) ?>" aria-label="Next page">Next ›</a>
      <?php endif; ?>
    </nav>
    <p class="pagination-info">
      Page <?= $currentPage ?> of <?= $pages ?> — <?= $total ?> task(s) total
    </p>
  <?php endif; ?>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
