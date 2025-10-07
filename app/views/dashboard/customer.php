<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Customer Dashboard</title>
  <link rel="stylesheet" href="/Group/assets/css/neumorphism.css" />
  <link rel="stylesheet" href="/Group/assets/css/remix%20icons/remixicon.css" />
  <script src="/Group/assets/js/popup.js" defer></script>
  <?php
// Creator: ghost1473
// session_start();
 if (!empty($_SESSION['flash'])): ?>
  <script>
    window.addEventListener('DOMContentLoaded', () => {
      showPopup('<?= htmlspecialchars($_SESSION['flash']['message'], ENT_QUOTES) ?>', '<?= $_SESSION['flash']['type'] === 'success' ? 'success' : ($_SESSION['flash']['type'] === 'info' ? 'info' : 'error') ?>');
    });
  </script>
  <?php $_SESSION['flash'] = null; unset($_SESSION['flash']); endif; ?>
</head>
<body>
  <div class="dashboard-layout">
    <aside class="sidebar">
      <div class="brand"><i class="ri-bank-line"></i> Goft Customer</div>
      <nav>
        <a class="active" href="index.php?url=dashboard/index"><i class="ri-dashboard-2-line"></i> Overview</a>
        <a href="index.php?url=account/myBalances"><i class="ri-wallet-3-line"></i> View Balance</a>
        <a href="index.php?url=account/request"><i class="ri-open-arm-line"></i> Request Account</a>
        <a href="index.php?url=transaction/create"><i class="ri-send-plane-2-line"></i> Transfer Money</a>
        <a href="index.php?url=profile/changePassword"><i class="ri-lock-password-line"></i> Change Password</a>
        <a href="index.php?url=profile/editProfile"><i class="ri-lock-password-line"></i> Edit Profile</a>
        <a href="index.php?url=auth/logout" class="btn btn-danger" style="margin-top: .75rem;"><i class="ri-logout-box-line"></i> Logout</a>
      </nav>
    </aside>

    <main>
      <div class="dashboard-content">
        <h2 style="margin-top:0;">Welcome</h2>
        <p>Hello, <strong class="text-capitalize"><?php echo htmlspecialchars($_SESSION['user']['username']); ?></strong></p>

        <div class="card" style="margin-top:1rem;">
          <h3>Your Accounts</h3>
          <div class="skeleton" style="display:grid; grid-template-columns: repeat(2,1fr); gap: 12px;">
            <div class="skeleton-block"></div>
            <div class="skeleton-block"></div>
          </div>
        </div>
      </div>
    </main>
  </div>
<footer style="border-top:1px solid var(--border);">
  <div class="container" style="padding:.75rem 0; text-align:center; color:var(--muted);"><small>Signature: Ghost1473 — ZeroDaySolutions</small></div>
</footer>
</body>
</html>
