<?php
/**
 * Under Construction View — User Management Module
 */
$pageTitle = 'User Management';
require __DIR__ . '/../layouts/base.php';
?>

<div style="max-width: 600px; margin: 60px auto 40px;">
  <div class="card" style="text-align: center; padding: 48px 32px; border: 1px dashed rgba(245, 158, 11, 0.4); background: linear-gradient(180deg, rgba(245, 158, 11, 0.04) 0%, var(--color-surface) 100%);">
    
    <!-- Icon -->
    <div style="width: 80px; height: 80px; margin: 0 auto 24px; border-radius: 24px; background: rgba(245, 158, 11, 0.12); display: flex; align-items: center; justify-content: center; border: 1px solid rgba(245, 158, 11, 0.3); box-shadow: 0 0 30px rgba(245, 158, 11, 0.15);">
      <i data-lucide="construction" style="width: 40px; height: 40px; color: #f59e0b;"></i>
    </div>

    <span class="badge badge-warning" style="font-size: 11px; padding: 4px 14px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">
      Under Construction
    </span>

    <h2 style="font-size: 22px; font-weight: 700; margin: 16px 0 10px; color: var(--color-text-main, #f8fafc);">
      Modul Dalam Pengembangan
    </h2>
    
    <p style="color: var(--color-text-muted, #94a3b8); font-size: 14px; max-width: 440px; margin: 0 auto 28px; line-height: 1.6;">
      Fitur Manajemen Pengguna saat ini sedang dalam tahap pengerjaan (<strong>Under Construction</strong>) dan belum dapat diakses.
    </p>

    <div>
      <a href="?page=dashboard" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
        <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i> Kembali ke Dashboard
      </a>
    </div>

  </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
