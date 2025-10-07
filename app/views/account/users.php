<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="/Group/assets/css/bootstrap-offline-docs-5.1/bootstrap-offline-docs-5.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Group/assets/css/remix%20icons/remixicon.css">
    <link rel="stylesheet" href="/Group/assets/css/neumorphism.css">
</head>
<body>
    <div class="container mt-5">
        <h2><i class="ri-team-line"></i> All Users</h2>
        <table class="table table-bordered neumorphism-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Status</th>
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
 foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td>
                        <form method="POST" action="index.php?url=account/changeRole/<?= $user['id'] ?>" style="display:inline-block;">
                            <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                                <?php foreach(['admin','manager','cashier','customer'] as $role): ?>
                                    <option value="<?= $role ?>" <?= $user['role'] === $role ? 'selected' : '' ?>><?= ucfirst($role) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </td>
                    <td><?= htmlspecialchars($user['status'] ?? 'active') ?></td>
                    <td>
                        <?php if (($user['status'] ?? 'active') !== 'blocked'): ?>
                            <a href="index.php?url=account/block/<?= $user['id'] ?>" class="btn btn-warning btn-sm"><i class="ri-user-unfollow-line"></i> Block</a>
                        <?php else: ?>
                            <a href="index.php?url=account/unlock/<?= $user['id'] ?>" class="btn btn-success btn-sm"><i class="ri-lock-unlock-line"></i> Unlock</a>
                        <?php endif; ?>
                        <a href="index.php?url=account/delete/<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete user?');"><i class="ri-delete-bin-6-line"></i> Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <nav aria-label="User pagination">
            <ul class="pagination justify-content-center mt-4">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item<?= $i == ($page ?? 1) ? ' active' : '' ?>">
                        <a class="page-link" href="index.php?url=account/users&page=<?= $i ?>">Page <?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
<footer style="border-top:1px solid var(--border);">
  <div class="container" style="padding:.75rem 0; text-align:center; color:var(--muted);"><small>Signature: Ghost1473 — ZeroDaySolutions</small></div>
</footer>
</body>
</html>
