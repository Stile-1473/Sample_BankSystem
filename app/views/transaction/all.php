<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Transactions</title>
  <link rel="stylesheet" href="/Group/assets/css/bootstrap-offline-docs-5.1/bootstrap-offline-docs-5.1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/Group/assets/css/remix%20icons/remixicon.css">
  <link rel="stylesheet" href="/Group/assets/css/neumorphism.css">
</head>
<body>
  <div class="container mt-4">
    <div style="display:flex; align-items:center; justify-content:space-between; gap: .5rem;">
      <h2 style="margin:0;"><i class="ri-swap-box-line"></i> All Transactions</h2>
      <div style="display:flex; gap:.5rem;">
        <a href="index.php?url=transaction/exportcsv&account_id=" class="btn btn-primary"><i class="ri-download-2-line"></i> Export CSV</a>
      </div>
    </div>

    <div class="card" style="margin-top:1rem;">
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>User</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Status</th>
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
 foreach($transactions as $tx): ?>
            <tr>
              <td><?= (int)$tx['id'] ?></td>
              <td><?= htmlspecialchars($tx['username']) ?></td>
              <td><?= htmlspecialchars($tx['type']) ?></td>
              <td>$<?= number_format((float)$tx['amount'], 2) ?></td>
              <td>
                <span class="badge"><?= htmlspecialchars($tx['status']) ?></span>
                <?php if ($tx['status'] === 'pending'): ?>
                  <div style="margin-top:.35rem; display:flex; gap:.35rem;">
                    <a class="btn btn-success btn-sm" href="index.php?url=transaction/approve/<?= (int)$tx['id'] ?>"><i class="ri-check-line"></i> Approve</a>
                    <a class="btn btn-danger btn-sm" href="index.php?url=transaction/reject/<?= (int)$tx['id'] ?>"><i class="ri-close-line"></i> Reject</a>
                  </div>
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($tx['created_at']) ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($transactions)): ?>
            <tr><td colspan="6">No transactions found.</td></tr>
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
