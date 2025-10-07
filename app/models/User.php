<?php
// Creator: ghost1473

class User {
    public function getPaginated($limit, $offset) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE status <> ? LIMIT ? OFFSET ?');
        $stmt->bindValue(1, 'deleted');
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll() {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE status <> ?');
        $stmt->execute(['deleted']);
        return $stmt->fetchColumn();
    }
    public function changeRole($id, $role) {
        $stmt = $this->db->prepare('UPDATE users SET role = ? WHERE id = ?');
        return $stmt->execute([$role, $id]);
    }
    private $db;
    public function __construct() {
        $this->db = (new Database())->getConnection();
    }
    public function findByUsername($username) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function create($username, $password, $role, $email = null, $phone = null) {
        $stmt = $this->db->prepare('INSERT INTO users (username, password, role, email, phone) VALUES (?, ?, ?, ?, ?)');
        return $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $role, $email, $phone]);
    }
    public function updateProfile($id, $email, $phone) {
        $stmt = $this->db->prepare('UPDATE users SET email = ?, phone = ? WHERE id = ?');
        return $stmt->execute([$email, $phone, $id]);
    }
    public function updatePassword($id, $newPassword) {
        $stmt = $this->db->prepare('UPDATE users SET password = ? WHERE id = ?');
        return $stmt->execute([password_hash($newPassword, PASSWORD_DEFAULT), $id]);
    }
    public function findByUsernameAndPhone($username, $phone) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = ? AND phone = ?');
        $stmt->execute([$username, $phone]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

        // Get all users (exclude soft-deleted)
        public function getAll() {
            $stmt = $this->db->prepare('SELECT * FROM users WHERE status <> ?');
            $stmt->execute(['deleted']);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Block user (set status to blocked)
        public function block($id) {
            $stmt = $this->db->prepare('UPDATE users SET status = "blocked" WHERE id = ?');
            return $stmt->execute([$id]);
        }

        // Delete user (soft delete)
        public function delete($id) {
            $stmt = $this->db->prepare("UPDATE users SET status = 'deleted' WHERE id = ?");
            return $stmt->execute([$id]);
        }

        // Unlock user (set status to active)
        public function unlock($id) {
            $stmt = $this->db->prepare('UPDATE users SET status = "active" WHERE id = ?');
            return $stmt->execute([$id]);
        }

        // Check if user is locked
        public function isLocked($id) {
            $stmt = $this->db->prepare('SELECT status FROM users WHERE id = ?');
            $stmt->execute([$id]);
            $status = $stmt->fetchColumn();
            return $status === 'blocked';
        }
}
