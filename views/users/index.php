<?php
/**
 * Users list — Admin only
 *
 * @var array<int, array<string, mixed>> $users
 * @var string $search
 * @var string|null $success
 * @var string|null $error
 */
$users   = $users ?? [];
$search  = $search ?? '';
$success = $success ?? null;
$error   = $error ?? null;

$pageTitle = 'User Management';
require __DIR__ . '/../layouts/base.php';
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
    <h2 class="page-header-title">Users</h2>
    <p class="page-header-sub">Manage all system users</p>
  </div>
  <a href="?page=users&action=create" class="btn btn-primary" id="btn-add-user">
    <i data-lucide="user-plus"></i> Add User
  </a>
</div>

<!-- Search bar -->
<form method="GET" action="" class="filter-bar" role="search">
  <input type="hidden" name="page" value="users" />
  <div class="form-group">
    <label for="search">Search by name</label>
    <input type="text" id="search" name="search"
           value="<?= e($search) ?>"
           placeholder="Enter name…"
           aria-label="Search users by name" />
  </div>
  <button type="submit" class="btn btn-primary">
    <i data-lucide="search"></i> Search
  </button>
  <?php if ($search): ?>
    <a href="?page=users" class="btn btn-secondary">
      <i data-lucide="x"></i> Clear
    </a>
  <?php endif; ?>
</form>

<?php if (empty($users)): ?>
  <div class="card">
    <div class="empty-state">
      <div class="empty-state-icon">
        <i data-lucide="users" style="width:48px;height:48px;color:var(--color-primary);"></i>
      </div>
      <div class="empty-state-title">No users found</div>
      <div class="empty-state-desc">
        <?= $search ? 'No users match your search. Try a different name.' : 'No users exist yet. Create the first one!' ?>
      </div>
      <a href="?page=users&action=create" class="btn btn-primary">
        <i data-lucide="user-plus"></i> Add User
      </a>
    </div>
  </div>
<?php else: ?>
  <div class="table-wrapper">
    <table class="table-responsive" aria-label="Users list">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col">Role</th>
          <th scope="col">Status</th>
          <th scope="col">Created</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $u): ?>
        <tr>
          <td data-label="#"><?= (int) $u['id'] ?></td>
          <td data-label="Name">
            <strong><?= e($u['name']) ?></strong>
          </td>
          <td data-label="Email"><?= e($u['email']) ?></td>
          <td data-label="Role">
            <span class="badge badge-<?= strtolower(e($u['role'])) ?>">
              <?= e($u['role']) ?>
            </span>
          </td>
          <td data-label="Status">
            <?php if ($u['is_active']): ?>
              <span class="badge badge-active">Active</span>
            <?php else: ?>
              <span class="badge badge-inactive">Inactive</span>
            <?php endif; ?>
          </td>
          <td data-label="Created"><?= e(date('d M Y', strtotime($u['created_at']))) ?></td>
          <td data-label="Actions">
            <div style="display:flex;gap:6px;flex-wrap:wrap;">
              <a href="?page=users&action=edit&id=<?= (int) $u['id'] ?>"
                 class="btn btn-secondary btn-xs"
                 aria-label="Edit <?= e($u['name']) ?>">
                <i data-lucide="pencil"></i> Edit
              </a>

              <?php if ($u['id'] !== \App\Core\Auth::id()): ?>
                <a href="?page=users&action=toggle&id=<?= (int) $u['id'] ?>"
                   class="btn <?= $u['is_active'] ? 'btn-warning' : 'btn-success' ?> btn-xs"
                   data-confirm="<?= $u['is_active'] ? 'Deactivate this user?' : 'Activate this user?' ?>"
                   aria-label="<?= $u['is_active'] ? 'Deactivate' : 'Activate' ?> <?= e($u['name']) ?>">
                  <?php if ($u['is_active']): ?>
                    <i data-lucide="lock"></i> Deactivate
                  <?php else: ?>
                    <i data-lucide="unlock"></i> Activate
                  <?php endif; ?>
                </a>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <p class="pagination-info"><?= count($users) ?> user(s) found</p>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
