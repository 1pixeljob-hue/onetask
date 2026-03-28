<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class PasswordModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Lấy toàn bộ danh sách mật khẩu
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM passwords ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm mật khẩu mới
     */
    public function create($data) {
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
    }

    /**
     * Cập nhật mật khẩu
     */
    public function update($id, $data) {
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
    }

    /**
     * Xóa mật khẩu
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM passwords WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
