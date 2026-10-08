<?php
/**
 * Create user form — Admin only
 *
 * @var array<string, string> $errors
 * @var array<string, mixed> $old
 */
$errors = $errors ?? [];
$old    = $old ?? [];

$pageTitle = 'Create User';
require __DIR__ . '/../layouts/base.php';
?>

<div class="page-header">
  <div>
    <h2 class="page-header-title">Create User</h2>
    <p class="page-header-sub">Add a new Admin or Member account</p>
  </div>
  <a href="?page=users" class="btn btn-secondary">
    <i data-lucide="arrow-left"></i> Back to Users
  </a>
</div>

<div class="card" style="max-width:600px;">
  <form method="POST" action="?page=users&action=store" class="needs-loading" novalidate>

    <div class="form-group">
      <label for="name">Full Name <span aria-hidden="true" style="color:var(--color-danger)">*</span></label>
      <input type="text" id="name" name="name"
             value="<?= old($old, 'name') ?>"
             placeholder="e.g. Alice Johnson"
             required maxlength="100"
             class="<?= isset($errors['name']) ? 'error' : '' ?>"
             aria-describedby="name-error" />
      <span id="name-error"><?= fieldError($errors, 'name') ?></span>
    </div>

    <div class="form-group">
      <label for="email">Email Address <span aria-hidden="true" style="color:var(--color-danger)">*</span></label>
      <input type="email" id="email" name="email"
             value="<?= old($old, 'email') ?>"
             placeholder="user@example.com"
             required maxlength="150"
             class="<?= isset($errors['email']) ? 'error' : '' ?>"
             aria-describedby="email-error" />
      <span id="email-error"><?= fieldError($errors, 'email') ?></span>
    </div>

    <div class="form-group">
      <label for="password">Password <span aria-hidden="true" style="color:var(--color-danger)">*</span></label>
      <div style="position:relative;">
        <input type="password" id="password" name="password"
               placeholder="Min 8 characters"
               required minlength="8"
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

    <div class="form-group">
      <label for="role">Role <span aria-hidden="true" style="color:var(--color-danger)">*</span></label>
      <select id="role" name="role" required
              class="<?= isset($errors['role']) ? 'error' : '' ?>"
              aria-describedby="role-error">
        <option value="">— Select role —</option>
        <option value="Admin"  <?= old($old,'role') === 'Admin'  ? 'selected' : '' ?>>Admin</option>
        <option value="Member" <?= old($old,'role') === 'Member' ? 'selected' : '' ?>>Member</option>
      </select>
      <span id="role-error"><?= fieldError($errors, 'role') ?></span>
    </div>

    <hr class="divider" />
    <div style="display:flex;gap:10px;justify-content:flex-end;">
      <a href="?page=users" class="btn btn-secondary">Cancel</a>
      <button type="submit" class="btn btn-primary" id="btn-create-user">
        <i data-lucide="check"></i> Create User
      </button>
    </div>
  </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
