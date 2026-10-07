<?php
/**
 * Create project form — Admin only
 * Variables: $errors, $old
 */
$pageTitle = 'Create Project';
require __DIR__ . '/../layouts/base.php';

function e(string $val): string {
    return htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
}
function fieldError(array $errors, string $field): string {
    return isset($errors[$field])
        ? '<span class="field-error" role="alert">' . e($errors[$field]) . '</span>'
        : '';
}
function old(array $old, string $field, string $default = ''): string {
    return e($old[$field] ?? $default);
}
?>

<div class="page-header">
  <div>
    <h2 class="page-header-title">Create Project</h2>
    <p class="page-header-sub">Add a new project to the system</p>
  </div>
  <a href="?page=projects" class="btn btn-secondary">
    <i data-lucide="arrow-left"></i> Back
  </a>
</div>

<div class="card" style="max-width:640px;">
  <form method="POST" action="?page=projects&action=store" class="needs-loading" novalidate>

    <div class="form-group">
      <label for="name">Project Name <span aria-hidden="true" style="color:var(--color-danger)">*</span></label>
      <input type="text" id="name" name="name"
             value="<?= old($old, 'name') ?>"
             placeholder="e.g. Website Redesign"
             required maxlength="150"
             class="<?= isset($errors['name']) ? 'error' : '' ?>"
             aria-describedby="name-error" />
      <span id="name-error"><?= fieldError($errors, 'name') ?></span>
    </div>

    <div class="form-group">
      <label for="description">Description</label>
      <textarea id="description" name="description"
                placeholder="Brief description of the project goals…"
                rows="3"><?= old($old, 'description') ?></textarea>
    </div>

    <div class="form-group">
      <label for="status">Status <span aria-hidden="true" style="color:var(--color-danger)">*</span></label>
      <select id="status" name="status" required
              class="<?= isset($errors['status']) ? 'error' : '' ?>"
              aria-describedby="status-error">
        <?php foreach (['Planning','Active','Completed','Archived'] as $s): ?>
          <option value="<?= e($s) ?>" <?= old($old,'status') === $s ? 'selected' : '' ?>>
            <?= e($s) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <span id="status-error"><?= fieldError($errors, 'status') ?></span>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="start_date">Start Date <span aria-hidden="true" style="color:var(--color-danger)">*</span></label>
        <input type="date" id="start_date" name="start_date"
               value="<?= old($old, 'start_date') ?>"
               required
               class="<?= isset($errors['start_date']) ? 'error' : '' ?>"
               aria-describedby="start-error" />
        <span id="start-error"><?= fieldError($errors, 'start_date') ?></span>
      </div>

      <div class="form-group">
        <label for="target_date">Target Date <span aria-hidden="true" style="color:var(--color-danger)">*</span></label>
        <input type="date" id="target_date" name="target_date"
               value="<?= old($old, 'target_date') ?>"
               required
               class="<?= isset($errors['target_date']) ? 'error' : '' ?>"
               aria-describedby="target-error" />
        <span id="target-error"><?= fieldError($errors, 'target_date') ?></span>
      </div>
    </div>

    <div class="alert alert-info">
      <i data-lucide="info"></i> Target date must be on or after the start date.
    </div>

    <hr class="divider" />
    <div style="display:flex;gap:10px;justify-content:flex-end;">
      <a href="?page=projects" class="btn btn-secondary">Cancel</a>
      <button type="submit" class="btn btn-primary" id="btn-create-project">
        <i data-lucide="check"></i> Create Project
      </button>
    </div>
  </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
