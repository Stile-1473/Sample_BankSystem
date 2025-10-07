<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transfer Money</title>
  <link rel="stylesheet" href="/Group/assets/css/bootstrap-offline-docs-5.1/bootstrap-offline-docs-5.1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/Group/assets/css/remix%20icons/remixicon.css">
  <link rel="stylesheet" href="/Group/assets/css/neumorphism.css">
  <script src="/Group/assets/js/popup.js" defer></script>
</head>
<body>
  <div class="container" style="max-width: 560px; padding: 1rem;">
    <div class="card">
      <h2 style="margin:0 0 .75rem 0;"><i class="ri-send-plane-2-line"></i> Transfer Money</h2>
      <p style="color: var(--muted); margin-top: 0;">Enter your account, select type, and amount.</p>
      <form method="POST" action="index.php?url=transaction/store">
        <div class="mb-3">
          <label class="form-label">Account</label>
          <?php
// Creator: ghost1473

// Creator: ghost1473

// Creator: ghost1473

// Creator: ghost1473

// Creator: ghost1473

// Creator: ghost1473
 if (isset($accounts) && is_array($accounts) && count($accounts) > 0): ?>
            <select name="account_id" class="form-select" required>
              <?php foreach ($accounts as $acc): ?>
                <option value="<?= (int)$acc['id'] ?>">#<?= (int)$acc['id'] ?> • Balance $<?= number_format((float)$acc['balance'], 2) ?></option>
              <?php endforeach; ?>
            </select>
          <?php else: ?>
            <input type="number" name="account_id" required class="form-control" placeholder="Enter your account ID">
            <small class="text-muted">Tip: Use View Balance to see your active account IDs.</small>
          <?php endif; ?>
        </div>
        <div class="mb-3">
          <label class="form-label">Type</label>
          <select name="type" id="tx-type" class="form-select" required onchange="document.getElementById('to-account').style.display = this.value==='transfer' ? 'block' : 'none';">
            <option value="deposit">Deposit</option>
            <option value="withdrawal">Withdrawal</option>
            <option value="transfer">Transfer</option>
          </select>
        </div>
        <div class="mb-3" id="to-account" style="display:none;">
          <label class="form-label">Destination Account (for Transfer)</label>
          <input type="number" name="to_account_id" class="form-control" placeholder="Destination account ID">
        </div>
        <div class="mb-3">
          <label class="form-label">Amount</label>
          <input type="number" step="0.01" name="amount" required class="form-control" placeholder="e.g. 100.00">
        </div>
        <button type="submit" class="btn btn-primary w-100"><i class="ri-arrow-right-circle-line"></i> Submit</button>
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
