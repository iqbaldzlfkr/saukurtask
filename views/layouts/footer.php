    </div><!-- /page-content -->
  </main><!-- /main-content -->

</div><!-- /app-layout -->

<!-- Custom Logout Confirmation Modal -->
<div class="custom-modal-backdrop" id="logoutModal" role="dialog" aria-modal="true" aria-labelledby="logoutModalTitle">
  <div class="custom-modal-card">
    <div class="modal-icon-wrapper danger">
      <i data-lucide="log-out" style="width:28px;height:28px;"></i>
    </div>
    <h3 class="modal-title" id="logoutModalTitle">Log Out of Your Account?</h3>
    <p class="modal-description">
      Are you sure you want to end your session? You will need to enter your email and password to sign in again.
    </p>
    <div class="modal-actions">
      <button type="button" class="btn btn-secondary" id="btn-cancel-logout">
        Cancel
      </button>
      <a href="?page=auth&action=logout" class="btn btn-danger" style="text-decoration:none; display:flex; align-items:center; justify-content:center; gap:6px;">
        <i data-lucide="log-out" style="width:16px;height:16px;"></i> Yes, Log Out
      </a>
    </div>
  </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="/assets/js/app.js"></script>
<?php
$authDebugEvent   = \App\Core\Session::getFlash('auth_debug_event');
$currentUser      = \App\Core\Auth::user();
$currentSessionId = session_id();
?>
<script>
(function() {
  const currentSessionId = <?= json_encode($currentSessionId) ?>;
  const currentUser = <?= json_encode($currentUser) ?>;
  const authEvent = <?= json_encode($authDebugEvent) ?>;

  window.__saukurSession = {
    sessionId: currentSessionId,
    user: currentUser,
    activeAt: new Date().toISOString()
  };

  if (authEvent && authEvent.action === 'LOGIN_SUCCESS') {
    console.group('%c🔐 [SESSION DEBUG] Login Successful!', 'color: #10b981; font-weight: bold; font-size: 13px; padding: 2px 0;');
    console.log('%cAction:     %cUser Logged In', 'font-weight:bold;', 'color: #10b981;');
    console.log('%cSession ID: %c' + authEvent.sessionId + ' (regenerated)', 'font-weight:bold;', 'color: #6366f1; font-weight:bold;');
    console.log('%cUser:       %c' + authEvent.userEmail + ' (' + authEvent.userRole + ', ID: ' + authEvent.userId + ')', 'font-weight:bold;', 'color: #0284c7;');
    console.log('%cTimestamp:  %c' + authEvent.timestamp, 'font-weight:bold;', 'color: #64748b;');
    console.table({
      'Event': 'User Login',
      'Session ID': authEvent.sessionId,
      'User ID': authEvent.userId,
      'User Name': authEvent.userName,
      'Email': authEvent.userEmail,
      'Role': authEvent.userRole,
      'Timestamp': authEvent.timestamp
    });
    console.groupEnd();
  } else {
    console.log(
      '%cℹ️ [SaukurTask Session]%c Active Session ID: %c' + currentSessionId + '%c | User: %c' + (currentUser.email || 'unknown') + ' (' + (currentUser.role || 'unknown') + ')',
      'color:#6366f1; font-weight:bold;',
      'color:#64748b;',
      'color:#6366f1; font-weight:bold;',
      'color:#64748b;',
      'color:#0284c7; font-weight:bold;'
    );
  }
})();
</script>
</body>
</html>
