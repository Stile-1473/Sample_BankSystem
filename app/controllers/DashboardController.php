<?php
// Creator: ghost1473

class DashboardController extends Controller {
    public function index() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?url=auth/login');
            exit;
        }
        $role = $_SESSION['user']['role'];
        require_once '../app/views/dashboard/' . $role . '.php';
    }
}
