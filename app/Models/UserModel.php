<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class UserModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Tìm user bằng username hoặc email
     */
    public function findByLogin($identity) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE (username = :user OR email = :mail) LIMIT 1");
        $stmt->execute([':user' => $identity, ':mail' => $identity]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Cập nhật Remember Token
     */
    public function updateRememberToken($id, $token) {
        $stmt = $this->db->prepare("UPDATE users SET remember_token = :token WHERE id = :id");
        return $stmt->execute([':token' => $token, ':id' => $id]);
    }

    /**
     * Tìm user bằng remember token
     */
    public function findByToken($token) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE remember_token = :token LIMIT 1");
        $stmt->execute([':token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Kiểm tra mật khẩu
     */
    public function verifyPassword($password, $hashedPassword) {
        return password_verify($password, $hashedPassword);
    }
}
