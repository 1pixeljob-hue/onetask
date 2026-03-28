<?php

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class PasswordModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->checkAndCreateTable();
    }

    private function checkAndCreateTable() {
        if (!$this->db) return;
        try {
            $sql = "CREATE TABLE IF NOT EXISTS passwords (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                category VARCHAR(100) DEFAULT 'Khác',
                username VARCHAR(255) DEFAULT '',
                password VARCHAR(255) DEFAULT '',
                url VARCHAR(255) DEFAULT '',
                notes TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            $this->db->exec($sql);
        } catch (PDOException $e) {
            // Silently fail or log
        }
    }

    /**
     * Lấy toàn bộ danh sách mật khẩu
     */
    public function getAll() {
        if (!$this->db) return [];
        try {
            $stmt = $this->db->query("SELECT * FROM passwords ORDER BY created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Thêm mật khẩu mới
     */
    public function create($data) {
        if (!$this->db) return false;
        try {
            $sql = "INSERT INTO passwords (title, category, username, password, url, notes) 
                    VALUES (:title, :category, :username, :password, :url, :notes)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':title' => $data['title'],
                ':category' => $data['category'] ?? 'Khác',
                ':username' => $data['username'] ?? '',
                ':password' => $data['password'] ?? '',
                ':url' => $data['url'] ?? '',
                ':notes' => $data['notes'] ?? ''
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Cập nhật mật khẩu
     */
    public function update($id, $data) {
        if (!$this->db) return false;
        try {
            $sql = "UPDATE passwords SET 
                    title = :title, 
                    category = :category, 
                    username = :username, 
                    password = :password, 
                    url = :url, 
                    notes = :notes 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':title' => $data['title'],
                ':category' => $data['category'] ?? 'Khác',
                ':username' => $data['username'] ?? '',
                ':password' => $data['password'] ?? '',
                ':url' => $data['url'] ?? '',
                ':notes' => $data['notes'] ?? ''
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Xóa mật khẩu
     */
    public function delete($id) {
        if (!$this->db) return false;
        try {
            $stmt = $this->db->prepare("DELETE FROM passwords WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
