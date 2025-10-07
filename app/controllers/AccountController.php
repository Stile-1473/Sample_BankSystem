<?php
// Creator: ghost1473

class AccountController extends Controller {

    // Change user role
    public function changeRole($id) {
        session_start();
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin','manager'])) $this->error403();
        $role = $_POST['role'] ?? '';
        $validRoles = ['admin','manager','cashier','customer'];
        if (!in_array($role, $validRoles)) {
            header('Location: index.php?url=account/users');
            exit;
        }
        $this->model('User')->changeRole((int)$id, $role);
        $this->model('AuditLog')->log($_SESSION['user']['id'], 'role_changed', json_encode(['user_id'=>$id, 'new_role'=>$role]));
        header('Location: index.php?url=account/users');
        exit;
    }

    public function pending() {
        session_start();
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin','manager'])) $this->error403();
        $accounts = $this->model('Account')->getPending();
        require_once '../app/views/account/pending.php';
    }
    public function all() {
        session_start();
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin'])) $this->error403();
        $accounts = $this->model('Account')->getAllWithUsers();
        require_once '../app/views/account/all.php';
    }
    public function approve($id) {
        session_start();
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin','manager'])) $this->error403();
        $this->model('Account')->approve((int)$id);
        $this->model('AuditLog')->log($_SESSION['user']['id'], 'account_approved', json_encode(['account_id'=>$id]));
        header('Location: index.php?url=account/pending');
        exit;
    }
    public function reject($id) {
        session_start();
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin','manager'])) $this->error403();
        // Set account to closed (schema supports active, pending, closed)
        $db = (new Database())->getConnection();
        $stmt = $db->prepare('UPDATE accounts SET status = "closed" WHERE id = ?');
        $stmt->execute([(int)$id]);
        $this->model('AuditLog')->log($_SESSION['user']['id'], 'account_rejected', json_encode(['account_id'=>$id]));
        header('Location: index.php?url=account/pending');
        exit;
    }

        // List all users for manager/admin
        public function users() {
            session_start();
            if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin','manager'])) $this->error403();
            $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
            $perPage = 10;
            $offset = ($page - 1) * $perPage;
            $users = $this->model('User')->getPaginated($perPage, $offset);
            $totalUsers = $this->model('User')->countAll();
            $totalPages = ceil($totalUsers / $perPage);
            require '../app/views/account/users.php';
        }

        // Block user
        public function block($id) {
            session_start();
            if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin','manager'])) $this->error403();
            if ((int)$id === (int)$_SESSION['user']['id']) {
                header('Location: index.php?url=account/users');
                exit;
            }
            $this->model('User')->block((int)$id);
            $this->model('AuditLog')->log($_SESSION['user']['id'], 'user_blocked', json_encode(['user_id'=>$id]));
            header('Location: index.php?url=account/users');
            exit;
        }

        // Delete user
        public function delete($id) {
            session_start();
            if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin','manager'])) $this->error403();
            if ((int)$id === (int)$_SESSION['user']['id']) {
                header('Location: index.php?url=account/users');
                exit;
            }
            $this->model('User')->delete((int)$id);
            $this->model('AuditLog')->log($_SESSION['user']['id'], 'user_deleted', json_encode(['user_id'=>$id]));
            header('Location: index.php?url=account/users');
            exit;
        }

        // Unlock user
        public function unlock($id) {
            session_start();
            if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin','manager'])) $this->error403();
            $this->model('User')->unlock((int)$id);
            $this->model('AuditLog')->log($_SESSION['user']['id'], 'user_unlocked', json_encode(['user_id'=>$id]));
            header('Location: index.php?url=account/users');
            exit;
        }

        // Customer: view own balances
        public function myBalances() {
            session_start();
            if (!isset($_SESSION['user'])) $this->error403();
            $user = $_SESSION['user'];
            $accounts = $this->model('Account')->getActiveByUser((int)$user['id']);
            require '../app/views/account/myBalances.php';
        }

        // Customer: request an account (GET form)
        public function request() {
            session_start();
            if (!isset($_SESSION['user'])) $this->error403();
            require '../app/views/account/request.php';
        }

        // Customer: submit account request (POST)
        public function submitRequest() {
            session_start();
            if (!isset($_SESSION['user'])) $this->error403();
            $user = $_SESSION['user'];
            $initial = isset($_POST['initial_balance']) ? (float)$_POST['initial_balance'] : 0.0;
            if ($initial < 0) $initial = 0.0;
            $this->model('Account')->createPending((int)$user['id'], $initial);
            $this->model('AuditLog')->log($user['id'], 'account_requested', json_encode(['initial_balance'=>$initial]));
            $_SESSION['flash'] = [
                'message' => 'Account request submitted. Waiting for manager approval.',
                'type' => 'info'
            ];
            header('Location: index.php?url=dashboard/index');
            exit;
        }

        // Cashier: list all customer accounts
        public function customerAccounts() {
            session_start();
            if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'cashier') $this->error403();
            $accounts = $this->model('Account')->getAllWithUsers();
            require '../app/views/account/customerAccounts.php';
        }

        // Cashier: process transactions form (generic)
        public function process() {
            session_start();
            if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'cashier') $this->error403();
            require '../app/views/account/process.php';
        }
}
