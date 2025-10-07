<?php
// Creator: ghost1473

class Transaction {
    private $db;
    private $approvalThreshold = 5000.00; // Amounts >= threshold require manager approval (pending)

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    // Legacy create (kept for compatibility but not used by controller after upgrade)
    public function create($account_id, $type, $amount) {
        $stmt = $this->db->prepare('INSERT INTO transactions (account_id, type, amount) VALUES (?, ?, ?)');
        return $stmt->execute([$account_id, $type, $amount]);
    }

    // New: Create a transaction and optionally process it immediately if status is 'completed'.
    public function createWithProcessing(int $accountId, string $type, float $amount, ?int $toAccountId, string $status) : array {
        if (!in_array($type, ['deposit','withdrawal','transfer'], true)) {
            throw new InvalidArgumentException('Invalid transaction type');
        }
        if ($amount <= 0) { throw new InvalidArgumentException('Amount must be positive'); }

        // If completed: perform balance update(s) atomically and insert transaction row(s)
        if ($status === 'completed') {
            if ($type === 'deposit') {
                return $this->processDeposit($accountId, $amount);
            } elseif ($type === 'withdrawal') {
                return $this->processWithdrawal($accountId, $amount);
            } else { // transfer
                if (!$toAccountId || $toAccountId <= 0) throw new InvalidArgumentException('to_account_id required for transfer');
                if ($toAccountId === $accountId) throw new InvalidArgumentException('Cannot transfer to the same account');
                return $this->processTransfer($accountId, $toAccountId, $amount);
            }
        }
        // Pending: insert one row; balances are not affected until approval.
        $stmt = $this->db->prepare('INSERT INTO transactions (account_id, to_account_id, type, amount, status) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$accountId, $toAccountId, $type, $amount, 'pending']);
        return ['id' => (int)$this->db->lastInsertId(), 'status' => 'pending'];
    }

    public function updateStatus($id, $status) {
        $tx = $this->get((int)$id);
        if (!$tx) return false;
        $current = $tx['status'];
        if ($current === $status) return true;

        if ($current === 'pending' && $status === 'completed') {
            // Apply the financial effect now
            if ($tx['type'] === 'deposit') {
                $this->processDeposit((int)$tx['account_id'], (float)$tx['amount'], $tx);
            } elseif ($tx['type'] === 'withdrawal') {
                $this->processWithdrawal((int)$tx['account_id'], (float)$tx['amount'], $tx);
            } elseif ($tx['type'] === 'transfer') {
                $toId = isset($tx['to_account_id']) ? (int)$tx['to_account_id'] : 0;
                $this->processTransfer((int)$tx['account_id'], $toId, (float)$tx['amount'], $tx);
            }
            // After processing, mark main row completed (process* functions will update status)
            return true;
        }

        // Other transitions: simple status update
        $stmt = $this->db->prepare('UPDATE transactions SET status = ?, updated_at = NOW() WHERE id = ?');
        return $stmt->execute([$status, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM transactions WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function getByAccount($account_id, $type = '', $status = '') {
        $sql = 'SELECT * FROM transactions WHERE account_id = ?';
        $params = [$account_id];
        if ($type) { $sql .= ' AND type = ?'; $params[] = $type; }
        if ($status) { $sql .= ' AND status = ?'; $params[] = $status; }
        $sql .= ' ORDER BY created_at DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get($id) {
        $stmt = $this->db->prepare('SELECT * FROM transactions WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $stmt = $this->db->query('SELECT transactions.*, users.username FROM transactions JOIN accounts ON transactions.account_id = accounts.id JOIN users ON accounts.user_id = users.id ORDER BY transactions.created_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Security helpers
    public function belongsToUser($transactionId, $userId) {
        $stmt = $this->db->prepare('SELECT 1 FROM transactions t JOIN accounts a ON t.account_id = a.id WHERE t.id = ? AND a.user_id = ?');
        $stmt->execute([$transactionId, $userId]);
        return (bool) $stmt->fetchColumn();
    }

    public function accountBelongsToUser($accountId, $userId) {
        $stmt = $this->db->prepare('SELECT 1 FROM accounts WHERE id = ? AND user_id = ?');
        $stmt->execute([$accountId, $userId]);
        return (bool) $stmt->fetchColumn();
    }

    // Processing helpers (atomic)
    private function processDeposit(int $accountId, float $amount, ?array $existingTx = null) : array {
        $this->db->beginTransaction();
        try {
            $this->adjustBalance($accountId, +$amount);
            if ($existingTx) {
                $stmt = $this->db->prepare('UPDATE transactions SET status = "completed", updated_at = NOW() WHERE id = ?');
                $stmt->execute([(int)$existingTx['id']]);
                $id = (int)$existingTx['id'];
            } else {
                $stmt = $this->db->prepare('INSERT INTO transactions (account_id, type, amount, status) VALUES (?, "deposit", ?, "completed")');
                $stmt->execute([$accountId, $amount]);
                $id = (int)$this->db->lastInsertId();
            }
            $this->db->commit();
            return ['id' => $id, 'status' => 'completed'];
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    private function processWithdrawal(int $accountId, float $amount, ?array $existingTx = null) : array {
        $this->db->beginTransaction();
        try {
            $balance = $this->getBalance($accountId);
            if ($balance < $amount) { throw new RuntimeException('Insufficient funds'); }
            $this->adjustBalance($accountId, -$amount);
            if ($existingTx) {
                $stmt = $this->db->prepare('UPDATE transactions SET status = "completed", updated_at = NOW() WHERE id = ?');
                $stmt->execute([(int)$existingTx['id']]);
                $id = (int)$existingTx['id'];
            } else {
                $stmt = $this->db->prepare('INSERT INTO transactions (account_id, type, amount, status) VALUES (?, "withdrawal", ?, "completed")');
                $stmt->execute([$accountId, $amount]);
                $id = (int)$this->db->lastInsertId();
            }
            $this->db->commit();
            return ['id' => $id, 'status' => 'completed'];
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    private function processTransfer(int $fromAccountId, int $toAccountId, float $amount, ?array $existingTx = null) : array {
        if ($fromAccountId === $toAccountId) throw new InvalidArgumentException('Cannot transfer to the same account');
        $this->db->beginTransaction();
        try {
            $fromBal = $this->getBalance($fromAccountId);
            if ($fromBal < $amount) { throw new RuntimeException('Insufficient funds'); }
            // Adjust balances
            $this->adjustBalance($fromAccountId, -$amount);
            $this->adjustBalance($toAccountId, +$amount);

            if ($existingTx) {
                // complete existing source row and create paired dest row
                $stmt = $this->db->prepare('UPDATE transactions SET status = "completed", updated_at = NOW() WHERE id = ?');
                $stmt->execute([(int)$existingTx['id']]);
                $sourceId = (int)$existingTx['id'];
                // ensure source row has to_account_id filled
                $stmt = $this->db->prepare('UPDATE transactions SET to_account_id = ? WHERE id = ?');
                $stmt->execute([$toAccountId, $sourceId]);

                $stmt = $this->db->prepare('INSERT INTO transactions (account_id, type, amount, status, pair_id) VALUES (?, "deposit", ?, "completed", ?)');
                $stmt->execute([$toAccountId, $amount, $sourceId]);
                $destId = (int)$this->db->lastInsertId();
                // link back
                $this->db->prepare('UPDATE transactions SET pair_id = ? WHERE id = ?')->execute([$destId, $sourceId]);
                $this->db->commit();
                return ['id' => $sourceId, 'pair_id' => $destId, 'status' => 'completed'];
            } else {
                // fresh source transfer row
                $stmt = $this->db->prepare('INSERT INTO transactions (account_id, to_account_id, type, amount, status) VALUES (?, ?, "transfer", ?, "completed")');
                $stmt->execute([$fromAccountId, $toAccountId, $amount]);
                $sourceId = (int)$this->db->lastInsertId();
                // paired destination deposit row
                $stmt = $this->db->prepare('INSERT INTO transactions (account_id, type, amount, status, pair_id) VALUES (?, "deposit", ?, "completed", ?)');
                $stmt->execute([$toAccountId, $amount, $sourceId]);
                $destId = (int)$this->db->lastInsertId();
                // link back
                $this->db->prepare('UPDATE transactions SET pair_id = ? WHERE id = ?')->execute([$destId, $sourceId]);
                $this->db->commit();
                return ['id' => $sourceId, 'pair_id' => $destId, 'status' => 'completed'];
            }
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    private function getBalance(int $accountId) : float {
        $stmt = $this->db->prepare('SELECT balance FROM accounts WHERE id = ?');
        $stmt->execute([$accountId]);
        $bal = $stmt->fetchColumn();
        if ($bal === false) throw new RuntimeException('Account not found');
        return (float)$bal;
    }

    private function adjustBalance(int $accountId, float $delta) : void {
        $stmt = $this->db->prepare('UPDATE accounts SET balance = balance + ? WHERE id = ?');
        $stmt->execute([$delta, $accountId]);
        if ($stmt->rowCount() === 0) throw new RuntimeException('Failed to update account');
    }
}
