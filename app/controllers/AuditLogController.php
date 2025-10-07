<?php
// Creator: ghost1473

class AuditLogController extends Controller {
    public function index() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') header('Location: index.php?url=auth/login');
        $logs = $this->model('AuditLog')->getAll();
        require_once '../app/views/auditlog/index.php';
    }
    public function exportcsv() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') header('Location: index.php?url=auth/login');
        $logs = $this->model('AuditLog')->getAll();
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="audit_logs.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['User', 'Action', 'Details', 'Timestamp']);
        foreach ($logs as $log) {
            fputcsv($out, [$log['username'], $log['action'], $log['details'], $log['created_at']]);
        }
        fclose($out);
        exit;
    }
}
