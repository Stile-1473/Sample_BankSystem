<?php
// Creator: ghost1473

class Account {
    private $db;
    public function __construct() {
        $this->db = (new Database())->getConnection();
    }
    public function getPending() {
        $stmt = $this->db->query("SELECT accounts.*, users.username FROM accounts JOIN users ON accounts.user_id = users.id WHERE accounts.status = 'pending'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function approve($id) {
        $stmt = $this->db->prepare("UPDATE accounts SET status = 'active' WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getByUser($user_id) {
        $stmt = $this->db->prepare('SELECT * FROM accounts WHERE user_id = ? ORDER BY created_at DESC');
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActiveByUser($user_id) {
        $stmt = $this->db->prepare("SELECT * FROM accounts WHERE user_id = ? AND status = 'active' ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createPending($user_id, $initialBalance = 0.0) {
        $stmt = $this->db->prepare("INSERT INTO accounts (user_id, balance, status) VALUES (?, ?, 'pending')");
        return $stmt->execute([$user_id, $initialBalance]);
    }

    public function getAllWithUsers() {
        $sql = 'SELECT accounts.*, users.username FROM accounts JOIN users ON accounts.user_id = users.id ORDER BY accounts.created_at DESC';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function exists(int $id): bool {
        $stmt = $this->db->prepare('SELECT 1 FROM accounts WHERE id = ?');
        $stmt->execute([$id]);
        return (bool)$stmt->fetchColumn();
    }

    public function isActive(int $id): bool {
        $stmt = $this->db->prepare("SELECT status FROM accounts WHERE id = ?");
        $stmt->execute([$id]);
        $status = $stmt->fetchColumn();
        return $status === 'active';
    }
}
