<!-- Creator: ghost1473 -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Process Transaction</title>
  <link rel="stylesheet" href="/Group/assets/css/bootstrap-offline-docs-5.1/bootstrap-offline-docs-5.1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/Group/assets/css/remix%20icons/remixicon.css">
  <link rel="stylesheet" href="/Group/assets/css/neumorphism.css">
  <script src="/Group/assets/js/popup.js" defer></script>
</head>
<body>
  <div class="container" style="max-width: 560px; padding: 1rem;">
    <div class="card">
      <h2 style="margin:0 0 .75rem 0;"><i class="ri-exchange-dollar-line"></i> Process Transaction</h2>
      <form method="POST" action="index.php?url=transaction/store">
        <div class="mb-3">
          <label class="form-label">Account ID</label>
          <input type="number" name="account_id" required class="form-control" placeholder="Account ID" value="<?= isset($_GET['account_id']) ? (int)$_GET['account_id'] : '' ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Type</label>
          <select name="type" class="form-select" required>
            <option value="deposit">Deposit</option>
            <option value="withdrawal">Withdrawal</option>
            <option value="transfer">Transfer</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Amount</label>
          <input type="number" step="0.01" name="amount" required class="form-control" placeholder="e.g. 50.00">
        </div>
        <button type="submit" class="btn btn-primary w-100"><i class="ri-check-line"></i> Submit</button>
      </form>
    </div>
    <div style="margin-top: .75rem;">
      <a class="btn btn-outline-secondary" href="index.php?url=account/customerAccounts"><i class="ri-arrow-left-line"></i> Back to Accounts</a>
    </div>
  </div>
<footer style="border-top:1px solid var(--border);">
  <div class="container" style="padding:.75rem 0; text-align:center; color:var(--muted);"><small>Signature: Ghost1473 — ZeroDaySolutions</small></div>
</footer>
</body>
</html>
