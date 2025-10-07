<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="/Group/assets/css/neumorphism.css">
    <link rel="stylesheet" href="/Group/assets/css/bootstrap-offline-docs-5.1/bootstrap-offline-docs-5.1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container neumorphism mt-5">
        <h2>Edit Profile</h2>
        <?php
// Creator: ghost1473
 if (!empty($error)): ?>
            <div class="alert alert-danger" style="color:#b30000;background:#ffe5e5;border-radius:8px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success" style="color:#005580;background:#e5f7ff;border-radius:8px;">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="index.php?url=profile/updateProfile" class="neumorphism-form">
            <input type="email" name="email" placeholder="Email" required class="neu-input mb-3" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"><br>
            <input type="text" name="phone" placeholder="Phone" required class="neu-input mb-3" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"><br>
            <button type="submit" class="btn">Update Profile</button>
        </form>
    </div>
<footer style="border-top:1px solid var(--border);">
  <div class="container" style="padding:.75rem 0; text-align:center; color:var(--muted);"><small>Signature: Ghost1473 — ZeroDaySolutions</small></div>
</footer>
</body>
</html>
