<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Transaction</title>
    <link rel="stylesheet" href="/Group/assets/css/neumorphism.css">
    <script src="/Group/assets/js/popup.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container neumorphism">
        <h2>Edit Transaction</h2>
        <form method="POST" action="index.php?url=transaction/update/<?php
// Creator: ghost1473
 echo $transaction['id']; ?>" class="neumorphism-form">
            <select name="status" class="neu-input" required>
                <option value="pending" <?php if($transaction['status']==='pending') echo 'selected'; ?>>Pending</option>
                <option value="completed" <?php if($transaction['status']==='completed') echo 'selected'; ?>>Completed</option>
                <option value="failed" <?php if($transaction['status']==='failed') echo 'selected'; ?>>Failed</option>
            </select><br>
            <button type="submit" class="btn">Update</button>
        </form>
    </div>
<footer style="border-top:1px solid var(--border);">
  <div class="container" style="padding:.75rem 0; text-align:center; color:var(--muted);"><small>Signature: Ghost1473 — ZeroDaySolutions</small></div>
</footer>
</body>
</html>
