<?php
// Creator: ghost1473

class AuditLog {
    private $db;
    public function __construct() {
        $this->db = (new Database())->getConnection();
    }
    public function log($user_id, $action, $details = null) {
        $stmt = $this->db->prepare('INSERT INTO audit_logs (user_id, action, details) VALUES (?, ?, ?)');
        return $stmt->execute([$user_id, $action, $details]);
    }
    public function getAll() {
        $stmt = $this->db->query('SELECT audit_logs.*, users.username FROM audit_logs LEFT JOIN users ON audit_logs.user_id = users.id ORDER BY created_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
