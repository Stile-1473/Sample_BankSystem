<!-- Creator: ghost1473 -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Request Account</title>
  <link rel="stylesheet" href="/Group/assets/css/bootstrap-offline-docs-5.1/bootstrap-offline-docs-5.1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/Group/assets/css/remix%20icons/remixicon.css">
  <link rel="stylesheet" href="/Group/assets/css/neumorphism.css">
  <script src="/Group/assets/js/popup.js" defer></script>
</head>
<body>
  <div class="container" style="max-width: 560px; padding: 1rem;">
    <div class="card">
      <h2 style="margin:0 0 .75rem 0;"><i class="ri-open-arm-line"></i> Request a New Account</h2>
      <p style="color: var(--muted); margin-top: 0;">Submit a request to open a new account. A manager will review and approve.</p>
      <form method="POST" action="index.php?url=account/submitRequest">
        <div class="mb-3">
          <label class="form-label">Initial Deposit (optional)</label>
          <input type="number" step="0.01" name="initial_balance" class="form-control" placeholder="e.g. 50.00">
        </div>
        <button type="submit" class="btn btn-primary w-100"><i class="ri-send-plane-2-line"></i> Submit Request</button>
      </form>
    </div>
    <div style="margin-top: .75rem;">
      <a class="btn btn-outline-secondary" href="index.php?url=dashboard/index"><i class="ri-arrow-left-line"></i> Back to Dashboard</a>
    </div>
  </div>
<footer style="border-top:1px solid var(--border);">
  <div class="container" style="padding:.75rem 0; text-align:center; color:var(--muted);"><small>Signature: Ghost1473 — ZeroDaySolutions</small></div>
</footer>
</body>
</html>
