<?php
class Users {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function register($name, $email, $role, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, email, role, password) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name, $email, $role, $hashedPassword]);
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>