<?php
// Creator: ghost1473

class FailedLogin {
    private $db;
    public function __construct() {
        $this->db = (new Database())->getConnection();
    }
    
    public function record($username, $ip) {
        $stmt = $this->db->prepare('SELECT * FROM failed_logins WHERE username = ? AND ip_address = ?');
        $stmt->execute([$username, $ip]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $stmt = $this->db->prepare('UPDATE failed_logins SET attempts = attempts + 1, last_attempt = NOW() WHERE id = ?');
            $stmt->execute([$row['id']]);
        } else {
            $stmt = $this->db->prepare('INSERT INTO failed_logins (username, ip_address) VALUES (?, ?)');
            $stmt->execute([$username, $ip]);
        }
    }
    public function getAttempts($username, $ip) {
        $stmt = $this->db->prepare('SELECT attempts FROM failed_logins WHERE username = ? AND ip_address = ?');
        $stmt->execute([$username, $ip]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['attempts'] : 0;
    }
    public function reset($username, $ip) {
        $stmt = $this->db->prepare('DELETE FROM failed_logins WHERE username = ? AND ip_address = ?');
        $stmt->execute([$username, $ip]);
    }
}
