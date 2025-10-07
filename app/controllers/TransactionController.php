<?php
// Creator: ghost1473


class TransactionController extends Controller {
    public function create() {
        session_start();
        if (!isset($_SESSION['user'])) header('Location: index.php?url=auth/login');
        $user = $_SESSION['user'];
        $accounts = [];
        if ($user['role'] === 'customer') {
            $accounts = $this->model('Account')->getActiveByUser((int)$user['id']);
        }
        require_once '../app/views/transaction/create.php';
    }
    public function store() {
        session_start();
        if (!isset($_SESSION['user'])) header('Location: index.php?url=auth/login');
        $txModel = $this->model('Transaction');
        $user = $_SESSION['user'];
        $account_id = isset($_POST['account_id']) ? (int) $_POST['account_id'] : 0;
        $to_account_id = isset($_POST['to_account_id']) ? (int) $_POST['to_account_id'] : null;
        $type = isset($_POST['type']) ? trim($_POST['type']) : '';
        $amount = isset($_POST['amount']) ? (float) $_POST['amount'] : 0.0;

        // Ownership: only customers must own the source account
        if ($user['role'] === 'customer') {
            if (!$txModel->accountBelongsToUser($account_id, (int)$user['id'])) {
                $this->error403();
            }
        }
        if ($amount <= 0) {
            echo "<script>showPopup('Invalid amount'); setTimeout(()=>{window.location='index.php?url=transaction/create'}, 1500);</script>";
            return;
        }
        if (!in_array($type, ['deposit','withdrawal','transfer'])) {
            echo "<script>showPopup('Invalid transaction type'); setTimeout(()=>{window.location='index.php?url=transaction/create'}, 1500);</script>";
            return;
        }
        if ($type === 'transfer' && (!$to_account_id || $to_account_id <= 0)) {
            echo "<script>showPopup('Destination account required for transfer'); setTimeout(()=>{window.location='index.php?url=transaction/create'}, 1500);</script>";
            return;
        }
        // Validate accounts existence and active status
        $accountModel = $this->model('Account');
        if (!$accountModel->exists($account_id)) {
            echo "<script>showPopup('Source account not found'); setTimeout(()=>{window.location='index.php?url=transaction/create'}, 1500);</script>";
            return;
        }
        if (!$accountModel->isActive($account_id)) {
            echo "<script>showPopup('Source account is not active'); setTimeout(()=>{window.location='index.php?url=transaction/create'}, 1500);</script>";
            return;
        }
        if ($type === 'transfer') {
            if (!$accountModel->exists($to_account_id)) {
                echo "<script>showPopup('Destination account not found'); setTimeout(()=>{window.location='index.php?url=transaction/create'}, 1500);</script>";
                return;
            }
            if (!$accountModel->isActive($to_account_id)) {
                echo "<script>showPopup('Destination account is not active'); setTimeout(()=>{window.location='index.php?url=transaction/create'}, 1500);</script>";
                return;
            }
        }

        // cashiers complete small transactions immediately; large amounts go pending. Customers always pending.
        $status = 'completed';
        $approvalThreshold = 5000.0;
        if ($user['role'] === 'customer') {
            $status = 'pending';
        } elseif ($user['role'] === 'cashier' && $amount >= $approvalThreshold) {
            $status = 'pending';
        } else {
            $status = 'completed';
        }

        try {
            $result = $txModel->createWithProcessing($account_id, $type, $amount, $to_account_id, $status);
            $this->model('AuditLog')->log($user['id'], 'transaction_create', json_encode([
                'account_id'=>$account_id,
                'to_account_id'=>$to_account_id,
                'type'=>$type,
                'amount'=>$amount,
                'status'=>$status
            ]));
            $msg = $status === 'pending' ? 'Transaction submitted for approval' : 'Transaction completed';
            echo "<script>showPopup('".$msg."','success'); setTimeout(()=>{window.location='index.php?url=dashboard/index'}, 1500);</script>";
        } catch (Throwable $e) {
            $safe = htmlspecialchars($e->getMessage(), ENT_QUOTES);
            echo "<script>showPopup('Failed: " . $safe . "','error'); setTimeout(()=>{window.location='index.php?url=transaction/create'}, 2000);</script>";
        }
    }
    
    
    public function edit($id) {
        session_start();
        if (!isset($_SESSION['user'])) header('Location: index.php?url=auth/login');
        $model = $this->model('Transaction');
        $id = (int) $id;
        $transaction = $model->get($id);
        if (!$transaction) $this->error403();
        $user = $_SESSION['user'];
        if ($user['role'] !== 'admin' && $user['role'] !== 'manager' && !$model->belongsToUser($id, (int)$user['id'])) {
            $this->error403();
        }
        require '../app/views/transaction/edit.php';
    }
    public function update($id) {
        session_start();
        if (!isset($_SESSION['user'])) header('Location: index.php?url=auth/login');
        $model = $this->model('Transaction');
        $id = (int) $id;
        $transaction = $model->get($id);
        if (!$transaction) $this->error403();
        $user = $_SESSION['user'];
        if ($user['role'] !== 'admin' && $user['role'] !== 'manager' && !$model->belongsToUser($id, (int)$user['id'])) {
            $this->error403();
        }
        $status = isset($_POST['status']) ? trim($_POST['status']) : '';
        if ($status === '') {
            echo "<script>showPopup('Invalid status'); setTimeout(()=>{window.location='index.php?url=dashboard/index'}, 1500);</script>";
            return;
        }
        $model->updateStatus($id, $status);
        $this->model('AuditLog')->log($user['id'], 'transaction_update', json_encode(['id'=>$id,'status'=>$status]));
        echo "<script>showPopup('Transaction updated!','success'); setTimeout(()=>{window.location='index.php?url=dashboard/index'}, 1500);</script>";
    }
    public function delete($id) {
        session_start();
        if (!isset($_SESSION['user'])) header('Location: index.php?url=auth/login');
        $model = $this->model('Transaction');
        $id = (int) $id;
        $transaction = $model->get($id);
        if (!$transaction) $this->error403();
        $user = $_SESSION['user'];
        if ($user['role'] !== 'admin' && $user['role'] !== 'manager' && !$model->belongsToUser($id, (int)$user['id'])) {
            $this->error403();
        }
        $model->delete($id);
        $this->model('AuditLog')->log($user['id'], 'transaction_delete', json_encode(['id'=>$id]));
        echo "<script>showPopup('Transaction deleted!','success'); setTimeout(()=>{window.location='index.php?url=dashboard/index'}, 1500);</script>";
    }
    public function all() {
        session_start();
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin','manager'])) $this->error403();
        $transactions = $this->model('Transaction')->getAll();
        require_once '../app/views/transaction/all.php';
    }
    // Manager/Admin approve or reject pending transactions
    public function approve($id) {
        session_start();
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin','manager'])) $this->error403();
        $this->model('Transaction')->updateStatus((int)$id, 'completed');
        $this->model('AuditLog')->log($_SESSION['user']['id'], 'transaction_approved', json_encode(['id'=>(int)$id]));
        echo "<script>showPopup('Transaction approved','success'); setTimeout(()=>{window.location='index.php?url=transaction/all'}, 1200);</script>";
    }
    public function reject($id) {
        session_start();
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin','manager'])) $this->error403();
        $this->model('Transaction')->updateStatus((int)$id, 'failed');
        $this->model('AuditLog')->log($_SESSION['user']['id'], 'transaction_rejected', json_encode(['id'=>(int)$id]));
        echo "<script>showPopup('Transaction rejected','info'); setTimeout(()=>{window.location='index.php?url=transaction/all'}, 1200);</script>";
    }

    public function exportcsv() {
        session_start();
        if (!isset($_SESSION['user'])) header('Location: index.php?url=auth/login');
        $user = $_SESSION['user'];
        $account_id = isset($_GET['account_id']) ? (int) $_GET['account_id'] : 0;
        if (!$account_id) exit('No account specified');
        if ($user['role'] !== 'admin' && $user['role'] !== 'manager') {
            if (!$this->model('Transaction')->accountBelongsToUser($account_id, (int)$user['id'])) {
                $this->error403();
            }
        }
        $transactions = $this->model('Transaction')->getByAccount($account_id);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="transactions.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['ID', 'Type', 'Amount', 'Status', 'Created', 'Updated']);
        foreach ($transactions as $tx) {
            fputcsv($out, [$tx['id'], $tx['type'], $tx['amount'], $tx['status'], $tx['created_at'], $tx['updated_at']]);
        }
        fclose($out);
        exit;
    }
    // Real-time status endpoint for AJAX polling
    public function status() {
        session_start();
        if (!isset($_SESSION['user'])) exit(json_encode([]));
        $user = $_SESSION['user'];
        $account_id = isset($_GET['account_id']) ? (int) $_GET['account_id'] : 0;
        $type = isset($_GET['type']) ? trim($_GET['type']) : '';
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        if (!$account_id) exit(json_encode([]));
        if ($user['role'] !== 'admin' && $user['role'] !== 'manager') {
            if (!$this->model('Transaction')->accountBelongsToUser($account_id, (int)$user['id'])) {
                $this->error403();
            }
        }
        $transactions = $this->model('Transaction')->getByAccount($account_id, $type, $status);
        header('Content-Type: application/json');
        echo json_encode($transactions);
    }
}
