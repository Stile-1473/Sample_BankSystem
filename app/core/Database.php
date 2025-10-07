<?php
// Creator: ghost1473

class Database {
    private $host = 'localhost';
    private $db_name = 'goft';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $exception) {
            die(json_encode(['error' => 'Database connection error: ' . $exception->getMessage()]));
            echo json_encode(['error'=> $exception->getMessage()]);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        }
        return $this->conn;
    }
}
