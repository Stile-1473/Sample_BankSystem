<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Goft Bank</title>
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
      <form method="POST" action="index.php?url=auth/handleLogin">
        <h2 style="margin:0 0 .75rem 0;"><i class="ri-login-box-line"></i> Login</h2>
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" placeholder="Username" required class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" placeholder="Password" required class="form-control">
        </div>
        <button type="submit" class="btn btn-primary w-100"><i class="ri-login-circle-line"></i> Login</button>
      </form>
      <div style="display:flex; justify-content:space-between; margin-top:.75rem;">
        <a href="index.php?url=auth/register" class="link-secondary">Create account</a>
        <a href="index.php?url=auth/forgotPassword" class="link-secondary">Forgot password?</a>
      </div>
    </div>
  </div>
  <script src="/Group/assets/js/popup.js"></script>
<footer style="border-top:1px solid var(--border);">
  <div class="container h-25" style="padding:.75rem 0; text-align:center; color:var(--muted);"><small>Signature: Ghost1473 — ZeroDaySolutions</small></div>
</footer>
</body>
</html>
