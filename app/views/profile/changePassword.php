<!-- Creator: ghost1473 -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Change Password</title>
  <link rel="stylesheet" href="/Group/assets/css/bootstrap-offline-docs-5.1/bootstrap-offline-docs-5.1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/Group/assets/css/remix%20icons/remixicon.css">
  <link rel="stylesheet" href="/Group/assets/css/neumorphism.css">
  <script src="/Group/assets/js/popup.js" defer></script>
</head>
<body>
  <div class="container" style="max-width: 560px; padding: 1rem;">
    <div class="card">
      <h2 style="margin:0 0 .75rem 0;"><i class="ri-lock-password-line"></i> Change Password</h2>
      <p style="color: var(--muted); margin-top: 0;">Use a strong password with at least 8 characters.</p>
      <form method="POST" action="index.php?url=profile/updatePassword">
        <div class="mb-3">
          <label class="form-label">Old Password</label>
          <input type="password" name="old_password" required class="form-control" minlength="6" placeholder="Enter your current password">
        </div>
        <div class="mb-3">
          <label class="form-label">New Password</label>
          <input type="password" name="new_password" required class="form-control" minlength="8" placeholder="Enter a new password">
        </div>
        <div class="mb-3">
          <label class="form-label">Confirm Password</label>
          <input type="password" name="confirm_password" required class="form-control" minlength="8" placeholder="Re-enter the new password">
        </div>
        <button type="submit" class="btn btn-primary w-100"><i class="ri-check-line"></i> Change Password</button>
      </form>
    </div>
    <div style="margin-top: .75rem;">
      <a class="btn btn-outline-secondary" href="index.php?url=dashboard/index"><i class="ri-arrow-left-line"></i> Back to Dashboard</a>
    </div>
  </div>
<footer style="border-top:1px solid var(--border);">
  <div class="container" style="padding:.75rem 0; text-align:center; color:var(--muted);"><small>Signature: Ghost1473 — ZeroDaySolutions</small></div>
</footer>
</body>
</html>
