<?php
/**
 * Create task form — Admin only
 *
 * @var array<int, array<string, mixed>> $projects
 * @var array<int, array<string, mixed>> $members
 * @var array<string, string> $errors
 * @var array<string, mixed> $old
 */
$projects = $projects ?? [];
$members  = $members ?? [];
$errors   = $errors ?? [];
$old      = $old ?? [];

$pageTitle = 'Create Task';
require __DIR__ . '/../layouts/base.php';
?>

<div class="page-header">
  <div>
    <h2 class="page-header-title">Create Task</h2>
    <p class="page-header-sub">Add a new task and assign it to a member</p>
  </div>
  <a href="?page=tasks" class="btn btn-secondary">
    <i data-lucide="arrow-left"></i> Back
  </a>
</div>

<div class="card" style="max-width:680px;">
  <form method="POST" action="?page=tasks&action=store" class="needs-loading" novalidate>

    <div class="form-group">
      <label for="title">Task Title <span aria-hidden="true" style="color:var(--color-danger)">*</span></label>
      <input type="text" id="title" name="title"
             value="<?= old($old, 'title') ?>"
             placeholder="e.g. Build navigation component"
             required maxlength="200"
             class="<?= isset($errors['title']) ? 'error' : '' ?>"
             aria-describedby="title-error" />
      <span id="title-error"><?= fieldError($errors, 'title') ?></span>
    </div>

    <div class="form-group">
      <label for="description">Description</label>
      <textarea id="description" name="description"
                placeholder="Task description, acceptance criteria, notes…"
                rows="3"><?= old($old, 'description') ?></textarea>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="project_id">Project <span aria-hidden="true" style="color:var(--color-danger)">*</span></label>
        <select id="project_id" name="project_id" required
                class="<?= isset($errors['project_id']) ? 'error' : '' ?>"
                aria-describedby="project-error">
          <option value="">— Select project —</option>
          <?php foreach ($projects as $p): ?>
            <option value="<?= (int) $p['id'] ?>"
                    data-start="<?= e($p['start_date']) ?>"
                    data-target="<?= e($p['target_date']) ?>"
                    <?= old($old,'project_id') == $p['id'] ? 'selected' : '' ?>>
              <?= e($p['name']) ?> (<?= e($p['start_date']) ?> → <?= e($p['target_date']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
        <span id="project-error"><?= fieldError($errors, 'project_id') ?></span>
      </div>

      <div class="form-group">
        <label for="assignee_id">Assign To <span aria-hidden="true" style="color:var(--color-danger)">*</span></label>
        <select id="assignee_id" name="assignee_id" required
                class="<?= isset($errors['assignee_id']) ? 'error' : '' ?>"
                aria-describedby="assignee-error">
          <option value="">— Select member —</option>
          <?php foreach ($members as $m): ?>
            <option value="<?= (int) $m['id'] ?>"
                    <?= old($old,'assignee_id') == $m['id'] ? 'selected' : '' ?>>
              <?= e($m['name']) ?> — <?= e($m['email']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <span id="assignee-error"><?= fieldError($errors, 'assignee_id') ?></span>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="status">Status <span aria-hidden="true" style="color:var(--color-danger)">*</span></label>
        <select id="status" name="status" required
                class="<?= isset($errors['status']) ? 'error' : '' ?>">
          <?php foreach (['To Do','In Progress','Done'] as $s): ?>
            <option value="<?= e($s) ?>" <?= old($old,'status','To Do') === $s ? 'selected' : '' ?>><?= e($s) ?></option>
          <?php endforeach; ?>
        </select>
        <?= fieldError($errors, 'status') ?>
      </div>

      <div class="form-group">
        <label for="priority">Priority <span aria-hidden="true" style="color:var(--color-danger)">*</span></label>
        <select id="priority" name="priority" required
                class="<?= isset($errors['priority']) ? 'error' : '' ?>">
          <?php foreach (['Low','Medium','High'] as $p): ?>
            <option value="<?= e($p) ?>" <?= old($old,'priority','Medium') === $p ? 'selected' : '' ?>><?= e($p) ?></option>
          <?php endforeach; ?>
        </select>
        <?= fieldError($errors, 'priority') ?>
      </div>
    </div>

    <div class="form-group">
      <label for="due_date">Due Date <span aria-hidden="true" style="color:var(--color-danger)">*</span></label>
      <input type="date" id="due_date" name="due_date"
             value="<?= old($old, 'due_date') ?>"
             required
             class="<?= isset($errors['due_date']) ? 'error' : '' ?>"
             aria-describedby="due-error" />
      <span id="due-error"><?= fieldError($errors, 'due_date') ?></span>
      <span class="text-muted text-xs" style="display:block;margin-top:4px;">
        Must fall within the selected project's date range.
      </span>
    </div>

    <hr class="divider" />
    <div style="display:flex;gap:10px;justify-content:flex-end;">
      <a href="?page=tasks" class="btn btn-secondary">Cancel</a>
      <button type="submit" class="btn btn-primary" id="btn-create-task">
        <i data-lucide="check"></i> Create Task
      </button>
    </div>
  </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
