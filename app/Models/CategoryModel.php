<?php
namespace App\Models;
use App\Core\Database;
use PDO;
use PDOException;

class CategoryModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->checkAndCreateTable();
    }

    private function checkAndCreateTable() {
        if (!$this->db) return;
        try {
            $sql = "CREATE TABLE IF NOT EXISTS categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL UNIQUE,
                color VARCHAR(20) DEFAULT '#2fab91',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            $this->db->exec($sql);
        } catch (PDOException $e) {
            // Silently fail or log
        }
    }

    public function getAll() {
        if (!$this->db) return [];
        try {
            $stmt = $this->db->query("SELECT * FROM categories ORDER BY name ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function create($data) {
        if (!$this->db) return false;
        try {
            $stmt = $this->db->prepare("INSERT INTO categories (name, color) VALUES (:name, :color)");
            return $stmt->execute([
                ':name' => $data['name'],
                ':color' => $data['color']
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function update($id, $data) {
        if (!$this->db) return false;
        try {
            $stmt = $this->db->prepare("UPDATE categories SET name = :name, color = :color WHERE id = :id");
            return $stmt->execute([
                ':name' => $data['name'],
                ':color' => $data['color'],
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete($id) {
        if (!$this->db) return false;
        try {
            $stmt = $this->db->prepare("DELETE FROM categories WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function find($id) {
        if (!$this->db) return null;
        try {
            $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }
}
