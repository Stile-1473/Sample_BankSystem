<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Accounts</title>
    <link rel="stylesheet" href="/Group/assets/css/neumorphism.css">
    <script src="/Group/assets/js/popup.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container neumorphism">
        <h2>Pending Accounts</h2>
        <table class="table" style="margin-top:1rem;">
            <thead>
                <tr><th>ID</th><th>User</th><th>Balance</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php
// Creator: ghost1473
 foreach($accounts as $acc): ?>
                <tr>
                    <td>#<?php echo (int)$acc['id']; ?></td>
                    <td><?php echo htmlspecialchars($acc['username']); ?></td>
                    <td>$<?php echo number_format((float)$acc['balance'], 2); ?></td>
                    <td><span class="badge"><?php echo htmlspecialchars($acc['status']); ?></span></td>
                    <td>
                        <a href="index.php?url=account/approve/<?php echo (int)$acc['id']; ?>" class="btn btn-success btn-sm"><i class="ri-check-line"></i> Approve</a>
                        <a href="index.php?url=account/reject/<?php echo (int)$acc['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Reject this account request?');"><i class="ri-close-line"></i> Reject</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($accounts)): ?>
                <tr><td colspan="5">No pending accounts.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<footer style="border-top:1px solid var(--border);">
  <div class="container" style="padding:.75rem 0; text-align:center; color:var(--muted);"><small>Signature: Ghost1473 — ZeroDaySolutions</small></div>
</footer>
</body>
</html>
