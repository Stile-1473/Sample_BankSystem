<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Audit Logs</title>
  <link rel="stylesheet" href="/Group/assets/css/bootstrap-offline-docs-5.1/bootstrap-offline-docs-5.1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/Group/assets/css/remix%20icons/remixicon.css">
  <link rel="stylesheet" href="/Group/assets/css/neumorphism.css">
</head>
<body>
  <div class="container mt-4">
    <div style="display:flex; align-items:center; justify-content:space-between; gap: .5rem;">
      <h2 style="margin:0;"><i class="ri-file-list-3-line"></i> Audit Logs</h2>
      <div style="display:flex; gap:.5rem;">
        <a href="index.php?url=auditlog/exportcsv" class="btn btn-primary"><i class="ri-download-2-line"></i> Export CSV</a>
      </div>
    </div>

    <div class="card" style="margin-top:1rem;">
      <table class="table">
        <thead>
          <tr>
            <th>User</th>
            <th>Action</th>
            <th>Details</th>
            <th>Timestamp</th>
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
 foreach($logs as $log): ?>
            <?php
              $rowClass = '';
              if ($log['action'] === 'login_failed') $rowClass = 'table-danger';
              elseif ($log['action'] === 'registration') $rowClass = 'table-info';
              elseif ($log['action'] === 'user_blocked') $rowClass = 'table-warning';
              elseif ($log['action'] === 'user_unlocked') $rowClass = 'table-success';
              elseif ($log['action'] === 'role_changed') $rowClass = 'table-light';
            ?>
            <tr class="<?= $rowClass ?>">
              <td><?= htmlspecialchars($log['username']) ?></td>
              <td><?= htmlspecialchars($log['action']) ?></td>
              <td><code style="background:#f8fafc; padding:2px 6px; border-radius:6px;"><?= htmlspecialchars($log['details']) ?></code></td>
              <td><?= htmlspecialchars($log['created_at']) ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($logs)): ?>
            <tr><td colspan="4">No logs found.</td></tr>
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
