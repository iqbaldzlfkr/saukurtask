<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Sign in to Saukur Task — Project Activity & Task Management System" />
  <title>Sign In — Saukur Task</title>
  <link rel="stylesheet" href="/assets/css/app.css" />
  <style>
    /* Premium Login Page Enhancements */
    .auth-page {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: radial-gradient(circle at 50% 20%, rgba(108, 99, 255, 0.15), transparent 45%),
                  radial-gradient(circle at 80% 80%, rgba(56, 189, 248, 0.1), transparent 40%),
                  var(--color-bg);
      padding: 24px;
      position: relative;
      overflow: hidden;
    }

    .auth-bg-glow {
      position: absolute;
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, rgba(108, 99, 255, 0.18) 0%, rgba(0,0,0,0) 70%);
      top: -150px;
      left: 50%;
      transform: translateX(-50%);
      pointer-events: none;
      z-index: 0;
    }

    .auth-container {
      width: 100%;
      max-width: 440px;
      position: relative;
      z-index: 1;
    }

    .auth-card {
      background: rgba(26, 29, 39, 0.85);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 20px;
      padding: 40px 36px;
      box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.6),
                  0 0 0 1px rgba(255, 255, 255, 0.05);
    }

    .brand-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(108, 99, 255, 0.12);
      border: 1px solid rgba(108, 99, 255, 0.3);
      color: #9c8bff;
      font-size: 11px;
      font-weight: 600;
      padding: 4px 10px;
      border-radius: 20px;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      margin-bottom: 16px;
    }

    .auth-header {
      text-align: center;
      margin-bottom: 28px;
    }

    .auth-icon-wrapper {
      width: 58px;
      height: 58px;
      background: linear-gradient(135deg, #6c63ff, #8b5cf6);
      border-radius: 16px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 16px;
      box-shadow: 0 8px 20px rgba(108, 99, 255, 0.35);
      color: #fff;
    }

    .auth-title {
      font-size: 24px;
      font-weight: 700;
      color: var(--color-text);
      letter-spacing: -0.5px;
      margin-bottom: 6px;
    }

    .auth-subtitle {
      font-size: 14px;
      color: var(--color-text-muted);
      line-height: 1.5;
    }

    .input-with-icon {
      position: relative;
      display: flex;
      align-items: center;
    }

    .input-with-icon .input-icon-left {
      position: absolute;
      left: 14px;
      color: var(--color-text-dim);
      pointer-events: none;
      display: flex;
      align-items: center;
    }

    .input-with-icon input {
      padding-left: 42px !important;
      padding-right: 42px !important;
      height: 46px;
      font-size: 14px;
      background: rgba(34, 38, 58, 0.6);
      border: 1px solid var(--color-border);
      border-radius: 10px;
      transition: all 0.25s ease;
    }

    .input-with-icon input:focus {
      background: rgba(34, 38, 58, 0.9);
      border-color: var(--color-primary);
      box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.2);
    }

    .toggle-password {
      position: absolute;
      right: 12px;
      background: none;
      border: none;
      color: var(--color-text-muted);
      cursor: pointer;
      padding: 6px;
      border-radius: 6px;
      display: flex;
      align-items: center;
      transition: color 0.2s;
    }

    .toggle-password:hover {
      color: var(--color-text);
    }

    .btn-login-submit {
      height: 46px;
      font-size: 15px;
      font-weight: 600;
      border-radius: 10px;
      background: linear-gradient(135deg, var(--color-primary), #8b5cf6);
      border: none;
      box-shadow: 0 4px 14px rgba(108, 99, 255, 0.35);
      transition: all 0.25s ease;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .btn-login-submit:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(108, 99, 255, 0.45);
      filter: brightness(1.05);
    }

    .btn-login-submit:active {
      transform: translateY(0);
    }

    /* Demo Account Quick Fill Helper */
    .demo-section {
      margin-top: 24px;
      padding-top: 20px;
      border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .demo-title {
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: var(--color-text-dim);
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .demo-chips {
      display: flex;
      gap: 8px;
    }

    .demo-chip {
      flex: 1;
      background: var(--color-surface-2);
      border: 1px solid var(--color-border);
      border-radius: 8px;
      padding: 8px 10px;
      font-size: 12px;
      color: var(--color-text);
      cursor: pointer;
      text-align: left;
      transition: all 0.2s ease;
      display: flex;
      flex-direction: column;
      gap: 2px;
    }

    .demo-chip:hover {
      border-color: var(--color-primary);
      background: rgba(108, 99, 255, 0.1);
      transform: translateY(-1px);
    }

    .demo-chip .chip-role {
      font-weight: 600;
      color: var(--color-primary);
      font-size: 11px;
    }

    .demo-chip .chip-email {
      color: var(--color-text-muted);
      font-size: 11px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .footer-note {
      text-align: center;
      margin-top: 20px;
      font-size: 12px;
      color: var(--color-text-dim);
    }
  </style>
</head>
<body>

<div class="auth-page">
  <div class="auth-bg-glow"></div>

  <div class="auth-container">
    <div class="auth-card">
      <div class="auth-header">
        <div class="brand-badge">
          <i data-lucide="shield-check" style="width:12px;height:12px;"></i> Neuronworks Final Project
        </div>
        <div>
          <div class="auth-icon-wrapper">
            <i data-lucide="check-square" style="width:28px;height:28px;"></i>
          </div>
        </div>
        <h1 class="auth-title">Saukur Task</h1>
        <p class="auth-subtitle">Project Activity & Task Management System</p>
      </div>

      <?php if (!empty($error)): ?>
        <div class="alert alert-error" role="alert" data-auto-dismiss>
          <i data-lucide="alert-circle"></i> <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
      <?php endif; ?>

      <form method="POST"
            action="?page=auth&action=login"
            class="needs-loading"
            novalidate>

        <div class="form-group">
          <label for="email">Email Address</label>
          <div class="input-with-icon">
            <span class="input-icon-left">
              <i data-lucide="mail" style="width:18px;height:18px;"></i>
            </span>
            <input
              type="email"
              id="email"
              name="email"
              value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
              placeholder="user@taskmanager.dev"
              required
              autocomplete="email"
              aria-required="true"
            />
          </div>
        </div>

        <div class="form-group" style="margin-bottom: 22px;">
          <label for="password">Password</label>
          <div class="input-with-icon">
            <span class="input-icon-left">
              <i data-lucide="lock" style="width:18px;height:18px;"></i>
            </span>
            <input
              type="password"
              id="password"
              name="password"
              placeholder="Enter your password"
              required
              autocomplete="current-password"
              aria-required="true"
            />
            <button type="button"
                    class="toggle-password"
                    data-target="password"
                    aria-label="Show password">
              <i data-lucide="eye" style="width:18px;height:18px;"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-login-submit" id="login-btn" style="width:100%;">
          <i data-lucide="log-in" style="width:18px;height:18px;"></i> Sign In
        </button>
      </form>

      <!-- Quick Demo Account Fillers -->
      <div class="demo-section">
        <div class="demo-title">
          <span>Demo Accounts (Click to auto-fill)</span>
          <i data-lucide="key" style="width:12px;height:12px;"></i>
        </div>
        <div class="demo-chips">
          <button type="button" class="demo-chip" onclick="fillDemo('admin@taskmanager.dev', 'Admin@1234')">
            <span class="chip-role">Admin User</span>
            <span class="chip-email">admin@taskmanager.dev</span>
          </button>
          <button type="button" class="demo-chip" onclick="fillDemo('iqbal@taskmanager.dev', 'Member@1234')">
            <span class="chip-role">Member (Iqbal)</span>
            <span class="chip-email">iqbal@taskmanager.dev</span>
          </button>
        </div>
      </div>

    </div>

    <div class="footer-note">
      &copy; <?= date('Y') ?> Saukur Task &bull; Neuronworks Junior Programmer
    </div>
  </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="/assets/js/app.js"></script>
<script>
  function fillDemo(email, password) {
    const emailInput = document.getElementById('email');
    const passInput = document.getElementById('password');
    if (emailInput && passInput) {
      emailInput.value = email;
      passInput.value = password;
      emailInput.focus();
    }
  }

  // Session Debug Console Logger
  (function() {
    <?php
    $authDebugEvent = \App\Core\Session::getFlash('auth_debug_event');
    $isLoggedOut    = \App\Core\Request::get('logged_out') === '1';
    $logoutEvent    = null;
    if ($isLoggedOut) {
        $logoutEvent = [
            'action'              => 'LOGOUT_SUCCESS',
            'terminatedSessionId' => \App\Core\Request::get('prev_sid', ''),
            'userEmail'           => \App\Core\Request::get('prev_user', 'User'),
            'userRole'            => \App\Core\Request::get('prev_role', 'Member'),
            'timestamp'           => \App\Core\Request::get('time', date('Y-m-d H:i:s')),
        ];
    }
    $effectiveEvent = $logoutEvent ?? $authDebugEvent;
    $guestSessionId = session_id();
    ?>
    const authEvent = <?= json_encode($effectiveEvent) ?>;
    const guestSessionId = <?= json_encode($guestSessionId) ?>;
    const isLoggedOut = <?= json_encode($isLoggedOut) ?>;

    if (isLoggedOut && window.history && window.history.replaceState) {
      window.history.replaceState({}, document.title, '?page=auth&action=login');
    }

    window.__saukurSession = {
      sessionId: guestSessionId,
      status: 'unauthenticated',
      time: new Date().toISOString()
    };

    if (authEvent && authEvent.action === 'LOGOUT_SUCCESS') {
      console.group('%c🚪 [SESSION DEBUG] User Logged Out', 'color: #ef4444; font-weight: bold; font-size: 13px; padding: 2px 0;');
      console.log('%cAction:                %cSession Terminated (Logout)', 'font-weight:bold;', 'color: #ef4444;');
      console.log('%cTerminated Session ID: %c' + authEvent.terminatedSessionId, 'font-weight:bold;', 'color: #ef4444; font-weight:bold;');
      console.log('%cPrevious User:         %c' + authEvent.userEmail + ' (' + authEvent.userRole + ')', 'font-weight:bold;', 'color: #64748b;');
      console.log('%cTimestamp:             %c' + authEvent.timestamp, 'font-weight:bold;', 'color: #64748b;');
      console.table({
        'Event': 'User Logout',
        'Terminated Session ID': authEvent.terminatedSessionId,
        'Previous User': authEvent.userEmail,
        'Role': authEvent.userRole,
        'Timestamp': authEvent.timestamp
      });
      console.groupEnd();
    } else if (authEvent && authEvent.action === 'LOGIN_FAILED') {
      console.group('%c⚠️ [SESSION DEBUG] Login Attempt Failed', 'color: #f59e0b; font-weight: bold; font-size: 13px; padding: 2px 0;');
      console.warn('Attempted Email: ' + authEvent.email);
      console.warn('Session ID:      ' + authEvent.sessionId);
      console.warn('Failure Reason:  ' + authEvent.reason);
      console.warn('Timestamp:       ' + authEvent.timestamp);
      console.table({
        'Event': 'Login Failed',
        'Session ID': authEvent.sessionId,
        'Attempted Email': authEvent.email,
        'Reason': authEvent.reason,
        'Timestamp': authEvent.timestamp
      });
      console.groupEnd();
    } else {
      console.log(
        '%cℹ️ [SaukurTask Session]%c Guest Session ID: %c' + guestSessionId,
        'color:#94a3b8; font-weight:bold;',
        'color:#64748b;',
        'color:#94a3b8; font-weight:bold;'
      );
    }
  })();
</script>
</body>
</html>
