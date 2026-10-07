/**
 * Saukur Task — Vanilla JavaScript
 * No jQuery, no framework — pure ES6+ JS
 */

document.addEventListener('DOMContentLoaded', function () {

  // ============================================================
  // 1. Sidebar toggle (mobile)
  // ============================================================
  const hamburger     = document.getElementById('hamburger');
  const sidebar       = document.getElementById('sidebar');
  const sidebarOverlay = document.getElementById('sidebar-overlay');

  function openSidebar() {
    sidebar?.classList.add('open');
    sidebarOverlay?.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeSidebar() {
    sidebar?.classList.remove('open');
    sidebarOverlay?.classList.remove('open');
    document.body.style.overflow = '';
  }

  hamburger?.addEventListener('click', openSidebar);
  sidebarOverlay?.addEventListener('click', closeSidebar);

  // ============================================================
  // 2. Flash message auto-dismiss
  // ============================================================
  const alerts = document.querySelectorAll('.alert[data-auto-dismiss]');
  alerts.forEach(function (alert) {
    setTimeout(function () {
      alert.style.transition = 'opacity .4s';
      alert.style.opacity = '0';
      setTimeout(() => alert.remove(), 400);
    }, 4000);
  });

  // ============================================================
  // 3. Confirm dialogs for destructive actions
  // ============================================================
  document.querySelectorAll('[data-confirm]').forEach(function (el) {
    el.addEventListener('click', function (e) {
      const message = el.dataset.confirm;
      if (!confirm(message)) {
        e.preventDefault();
        return false;
      }
    });
  });

  // ============================================================
  // 4. Frontend date validation for Project form
  // ============================================================
  const startDateInput  = document.getElementById('start_date');
  const targetDateInput = document.getElementById('target_date');

  if (startDateInput && targetDateInput) {
    function validateProjectDates() {
      const start  = startDateInput.value;
      const target = targetDateInput.value;
      if (start && target && target < start) {
        targetDateInput.setCustomValidity('Target date cannot be earlier than start date.');
        targetDateInput.classList.add('error');
      } else {
        targetDateInput.setCustomValidity('');
        targetDateInput.classList.remove('error');
      }
    }

    startDateInput.addEventListener('change', validateProjectDates);
    targetDateInput.addEventListener('change', validateProjectDates);
  }

  // ============================================================
  // 5. Task due_date validation against project dates
  // ============================================================
  const projectSelect = document.getElementById('project_id');
  const dueDateInput  = document.getElementById('due_date');

  // Project date bounds are embedded in data attributes on the select options
  if (projectSelect && dueDateInput) {
    function updateDueDateBounds() {
      const selected = projectSelect.options[projectSelect.selectedIndex];
      const start  = selected?.dataset.start;
      const target = selected?.dataset.target;

      if (start && target) {
        dueDateInput.min = start;
        dueDateInput.max = target;
        // Re-validate if a date was already entered
        if (dueDateInput.value) {
          if (dueDateInput.value < start || dueDateInput.value > target) {
            dueDateInput.setCustomValidity(`Due date must be between ${start} and ${target}.`);
            dueDateInput.classList.add('error');
          } else {
            dueDateInput.setCustomValidity('');
            dueDateInput.classList.remove('error');
          }
        }
      } else {
        dueDateInput.min = '';
        dueDateInput.max = '';
        dueDateInput.setCustomValidity('');
      }
    }

    projectSelect.addEventListener('change', updateDueDateBounds);
    updateDueDateBounds(); // run on load
  }

  // ============================================================
  // 6. Task status quick-update (AJAX from task list)
  // ============================================================
  document.querySelectorAll('[data-status-form]').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      // Regular form submit — no AJAX needed per requirements
      // Just add a loading state to the button
      const btn = form.querySelector('button[type="submit"]');
      if (btn) {
        btn.disabled = true;
        btn.textContent = 'Saving…';
      }
    });
  });

  // ============================================================
  // 7. Password visibility toggle
  // ============================================================
  document.querySelectorAll('.toggle-password').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const targetId = btn.dataset.target;
      const input    = document.getElementById(targetId);
      if (!input) return;

      if (input.type === 'password') {
        input.type = 'text';
        btn.innerHTML = '<i data-lucide="eye-off"></i>';
        btn.setAttribute('aria-label', 'Hide password');
      } else {
        input.type = 'password';
        btn.innerHTML = '<i data-lucide="eye"></i>';
        btn.setAttribute('aria-label', 'Show password');
      }
      if (window.lucide) {
        lucide.createIcons();
      }
    });
  });

  // ============================================================
  // 8. Active nav item highlight
  // ============================================================
  const currentPage = new URLSearchParams(window.location.search).get('page') || 'dashboard';
  const navItems = document.querySelectorAll('.nav-item[data-page]');
  navItems.forEach(function (item) {
    if (item.dataset.page === currentPage) {
      item.classList.add('active');
    }
  });

  // ============================================================
  // 9. Form submission loader (prevent double-submit)
  // ============================================================
  document.querySelectorAll('form.needs-loading').forEach(function (form) {
    form.addEventListener('submit', function () {
      const submitBtn = form.querySelector('button[type="submit"]');
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i data-lucide="loader-2" class="spin"></i> Saving…';
        if (window.lucide) {
          lucide.createIcons();
        }
      }
    });
  });

  // ============================================================
  // 10. Initialize Lucide Icons
  // ============================================================
  if (window.lucide) {
    lucide.createIcons();
  }

});

  // ============================================================
  // 11. Custom Logout Confirmation Modal
  // ============================================================
  const btnOpenLogoutModal = document.getElementById('btn-open-logout-modal');
  const logoutModal        = document.getElementById('logoutModal');
  const btnCancelLogout    = document.getElementById('btn-cancel-logout');

  function openLogoutModal() {
    logoutModal?.classList.add('active');
    document.body.style.overflow = 'hidden';
    if (window.lucide) {
      lucide.createIcons();
    }
  }

  function closeLogoutModal() {
    logoutModal?.classList.remove('active');
    document.body.style.overflow = '';
  }

  btnOpenLogoutModal?.addEventListener('click', openLogoutModal);
  btnCancelLogout?.addEventListener('click', closeLogoutModal);

  // Close on backdrop click
  logoutModal?.addEventListener('click', function (e) {
    if (e.target === logoutModal) {
      closeLogoutModal();
    }
  });

  // Close on Escape key
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && logoutModal?.classList.contains('active')) {
      closeLogoutModal();
    }
  });
