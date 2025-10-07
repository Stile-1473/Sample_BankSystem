<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>All Accounts</title>
  <link rel="stylesheet" href="/Group/assets/css/bootstrap-offline-docs-5.1/bootstrap-offline-docs-5.1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/Group/assets/css/remix%20icons/remixicon.css">
  <link rel="stylesheet" href="/Group/assets/css/neumorphism.css">
</head>
<body>
  <div class="container mt-4">
    <div style="display:flex; align-items:center; justify-content:space-between; gap: .5rem;">
      <h2 style="margin:0;"><i class="ri-briefcase-3-line"></i> All Accounts</h2>
    </div>

    <div class="card" style="margin-top:1rem;">
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Status</th>
            <th>Balance</th>
            <th>Created</th>
          </tr>
        </thead>
        <tbody>
          <?php
// Creator: ghost1473

// Creator: ghost1473

// Creator: ghost1473

// Creator: ghost1473

// Creator: ghost1473

// Creator: ghost1473
 foreach ($accounts as $acc): ?>
            <tr>
              <td>#<?= (int)$acc['id'] ?></td>
              <td><?= htmlspecialchars($acc['username']) ?></td>
              <td><span class="badge"><?= htmlspecialchars($acc['status']) ?></span></td>
              <td>$<?= number_format((float)$acc['balance'], 2) ?></td>
              <td><?= htmlspecialchars($acc['created_at']) ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($accounts)): ?>
            <tr><td colspan="5">No accounts found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
<footer style="border-top:1px solid var(--border);">
  <div class="container" style="padding:.75rem 0; text-align:center; color:var(--muted);"><small>Signature: Ghost1473 — ZeroDaySolutions</small></div>
</footer>
</body>
</html>
