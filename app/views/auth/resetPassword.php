<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <link rel="stylesheet" href="/Group/assets/css/bootstrap-offline-docs-5.1/bootstrap-offline-docs-5.1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/Group/assets/css/remix%20icons/remixicon.css">
  <link rel="stylesheet" href="/Group/assets/css/neumorphism.css">
</head>
<body>
  <div class="container" style="max-width: 520px; padding: 1rem;">
    <div class="card">
      <?php
// Creator: ghost1473
 if (!empty($error)): ?>
        <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
      <?php if (!empty($success)): ?>
        <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($success); ?></div>
      <?php endif; ?>
      <h2 style="margin:0 0 .75rem 0;"><i class="ri-lock-password-line"></i> Reset Password</h2>
      <form method="POST" action="index.php?url=auth/handleResetPassword">
        <input type="hidden" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>">
        <div class="mb-3">
          <label class="form-label">New Password</label>
          <input type="password" name="new_password" placeholder="New Password" required class="form-control" minlength="6">
        </div>
        <button type="submit" class="btn btn-primary w-100"><i class="ri-check-line"></i> Set New Password</button>
      </form>
    </div>
    <div style="margin-top:.75rem;">
      <a href="index.php?url=auth/login" class="btn btn-outline-secondary"><i class="ri-arrow-left-line"></i> Back to Login</a>
    </div>
  </div>
<footer style="border-top:1px solid var(--border);">
  <div class="container" style="padding:.75rem 0; text-align:center; color:var(--muted);"><small>Signature: Ghost1473 — ZeroDaySolutions</small></div>
</footer>
</body>
</html>
