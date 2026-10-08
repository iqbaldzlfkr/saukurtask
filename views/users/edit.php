<?php
/**
 * Edit user form — Admin only
 *
 * @var array<string, mixed> $user
 * @var array<string, string> $errors
 * @var array<string, mixed> $old
 */
$user   = $user ?? [];
$errors = $errors ?? [];
$old    = $old ?? [];

$pageTitle = 'Edit User';
require __DIR__ . '/../layouts/base.php';
?>

<div class="page-header">
  <div>
    <h2 class="page-header-title">Edit User</h2>
    <p class="page-header-sub">Editing: <?= e($user['name']) ?></p>
  </div>
  <a href="?page=users" class="btn btn-secondary">
    <i data-lucide="arrow-left"></i> Back to Users
  </a>
</div>

<div class="card" style="max-width:600px;">
  <form method="POST" action="?page=users&action=update" class="needs-loading" novalidate>
    <input type="hidden" name="id" value="<?= (int) $user['id'] ?>" />

    <div class="form-group">
      <label for="name">Full Name <span aria-hidden="true" style="color:var(--color-danger)">*</span></label>
      <input type="text" id="name" name="name"
             value="<?= old($old, 'name') ?>"
             required maxlength="100"
             class="<?= isset($errors['name']) ? 'error' : '' ?>"
             aria-describedby="name-error" />
      <span id="name-error"><?= fieldError($errors, 'name') ?></span>
    </div>

    <div class="form-group">
      <label for="email">Email Address <span aria-hidden="true" style="color:var(--color-danger)">*</span></label>
      <input type="email" id="email" name="email"
             value="<?= old($old, 'email') ?>"
             required maxlength="150"
             class="<?= isset($errors['email']) ? 'error' : '' ?>"
             aria-describedby="email-error" />
      <span id="email-error"><?= fieldError($errors, 'email') ?></span>
    </div>

    <div class="form-group">
      <label for="password">New Password
        <span class="text-muted text-xs">(leave blank to keep current)</span>
      </label>
      <div style="position:relative;">
        <input type="password" id="password" name="password"
               placeholder="Min 8 characters"
               minlength="8"
               class="<?= isset($errors['password']) ? 'error' : '' ?>"
               aria-describedby="password-error" />
        <button type="button" class="toggle-password" data-target="password"
                aria-label="Show password"
                style="position:absolute;right:12px;top:50%;transform:translateY(-50%);
                       background:none;border:none;cursor:pointer;font-size:16px;color:#94a3b8;display:flex;align-items:center;">
          <i data-lucide="eye"></i>
        </button>
      </div>
      <span id="password-error"><?= fieldError($errors, 'password') ?></span>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="role">Role <span aria-hidden="true" style="color:var(--color-danger)">*</span></label>
        <select id="role" name="role" required
                class="<?= isset($errors['role']) ? 'error' : '' ?>"
                aria-describedby="role-error">
          <option value="Admin"  <?= old($old,'role') === 'Admin'  ? 'selected' : '' ?>>Admin</option>
          <option value="Member" <?= old($old,'role') === 'Member' ? 'selected' : '' ?>>Member</option>
        </select>
        <span id="role-error"><?= fieldError($errors, 'role') ?></span>
      </div>

      <div class="form-group">
        <label for="is_active">Account Status</label>
        <select id="is_active" name="is_active" aria-label="Account status">
          <option value="1" <?= (old($old,'is_active','1') == '1') ? 'selected' : '' ?>>Active</option>
          <option value="0" <?= (old($old,'is_active','1') == '0') ? 'selected' : '' ?>>Inactive</option>
        </select>
      </div>
    </div>

    <div class="alert alert-info" style="margin-top:8px;">
      <i data-lucide="info"></i>
      This account was created on <strong><?= e(date('d M Y', strtotime($user['created_at']))) ?></strong>.
    </div>

    <hr class="divider" />
    <div style="display:flex;gap:10px;justify-content:flex-end;">
      <a href="?page=users" class="btn btn-secondary">Cancel</a>
      <button type="submit" class="btn btn-primary" id="btn-update-user">
        <i data-lucide="save"></i> Save Changes
      </button>
    </div>
  </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
