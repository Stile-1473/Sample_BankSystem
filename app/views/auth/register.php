<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Goft Bank</title>
  <link rel="stylesheet" href="/Group/assets/css/bootstrap-offline-docs-5.1/bootstrap-offline-docs-5.1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/Group/assets/css/remix%20icons/remixicon.css">
  <link rel="stylesheet" href="/Group/assets/css/neumorphism.css">
</head>
<body>
  <div class="container" style="max-width: 560px; padding: 1rem;">
    <div class="card">
      <?php
// Creator: ghost1473
 if (!empty($error)): ?>
        <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
      <?php if (!empty($success)): ?>
        <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($success); ?></div>
      <?php endif; ?>
      <form method="POST" action="index.php?url=auth/handleRegister">
        <h2 style="margin:0 0 .75rem 0;"><i class="ri-user-add-line"></i> Register</h2>
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" placeholder="Username" required class="form-control" pattern="[A-Za-z0-9_]{4,20}" title="4-20 letters, numbers, or underscores">
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" placeholder="Password" required class="form-control" minlength="6" title="At least 6 characters">
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" placeholder="Email" required class="form-control" pattern="[^@\s]+@[^@\s]+\.[^@\s]+" title="Valid email address">
        </div>
        <div class="mb-3">
          <label class="form-label">Phone</label>
          <input type="text" name="phone" placeholder="Phone" required class="form-control" pattern="[0-9\-\+]{9,15}" title="Valid phone number">
        </div>
        <div class="mb-3">
          <label class="form-label">Role</label>
          <select name="role" class="form-select" required>
            <option value="customer">Customer</option>
            <option value="cashier">Cashier</option>
            <option value="manager">Manager</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary w-100"><i class="ri-check-line"></i> Register</button>
      </form>
      <div style="margin-top:.75rem;">
        <a href="index.php?url=auth/login" class="btn btn-outline-secondary"><i class="ri-arrow-left-line"></i> Back to Login</a>
      </div>
    </div>
  </div>
  <script src="/Group/assets/js/popup.js"></script>
<footer style="border-top:1px solid var(--border);">
  <div class="container" style="padding:.75rem 0; text-align:center; color:var(--muted);"><small>Signature: Ghost1473 — ZeroDaySolutions</small></div>
</footer>
</body>
</html>
