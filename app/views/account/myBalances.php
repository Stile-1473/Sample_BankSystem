<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Balances</title>
  <link rel="stylesheet" href="/Group/assets/css/bootstrap-offline-docs-5.1/bootstrap-offline-docs-5.1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/Group/assets/css/remix%20icons/remixicon.css">
  <link rel="stylesheet" href="/Group/assets/css/neumorphism.css">
  <script src="/Group/assets/js/popup.js" defer></script>
</head>
<body>
  <div class="container mt-5">
    <h2><i class="ri-wallet-3-line"></i> My Balances</h2>

    <?php
// Creator: ghost1473
 if (empty($accounts)): ?>
      <div class="card" style="margin-top:1rem;">
        <p style="margin:0;">No active accounts found.</p>
      </div>
    <?php else: ?>
      <table class="table">
        <thead>
          <tr>
            <th>Account ID</th>
            <th>Status</th>
            <th>Balance</th>
            <th>Created</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($accounts as $acc): ?>
            <tr>
              <td><?= htmlspecialchars($acc['id']) ?></td>
              <td><span class="badge"><?= htmlspecialchars($acc['status']) ?></span></td>
              <td><strong>$<?= number_format((float)$acc['balance'], 2) ?></strong></td>
              <td><?= htmlspecialchars($acc['created_at']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <div style="margin-top:1rem; display:flex; gap:.6rem;">
      <a class="btn btn-primary" href="index.php?url=dashboard/index"><i class="ri-arrow-left-line"></i> Back to Dashboard</a>
    </div>
  </div>
<footer style="border-top:1px solid var(--border);">
  <div class="container" style="padding:.75rem 0; text-align:center; color:var(--muted);"><small>Signature: Ghost1473 — ZeroDaySolutions</small></div>
</footer>
</body>
</html>
