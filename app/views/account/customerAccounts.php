<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Customer Accounts</title>
  <link rel="stylesheet" href="/Group/assets/css/bootstrap-offline-docs-5.1/bootstrap-offline-docs-5.1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/Group/assets/css/remix%20icons/remixicon.css">
  <link rel="stylesheet" href="/Group/assets/css/neumorphism.css">
</head>
<body>
  <div class="container mt-4">
    <h2><i class="ri-user-3-line"></i> Customer Accounts</h2>
    <table class="table">
      <thead>
        <tr>
          <th>Account ID</th>
          <th>Username</th>
          <th>Status</th>
          <th>Balance</th>
          <th>Actions</th>
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
            <td>
              <a class="btn btn-primary btn-sm" href="index.php?url=account/process&account_id=<?= (int)$acc['id'] ?>"><i class="ri-exchange-dollar-line"></i> Process</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <a class="btn btn-outline-secondary" href="index.php?url=dashboard/index"><i class="ri-arrow-left-line"></i> Back</a>
  </div>
<footer style="border-top:1px solid var(--border);">
  <div class="container" style="padding:.75rem 0; text-align:center; color:var(--muted);"><small>Signature: Ghost1473 — ZeroDaySolutions</small></div>
</footer>
</body>
</html>
