<?php
namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class CodeCategoryModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->checkAndCreateTable();
    }

    private function checkAndCreateTable() {
        if (!$this->db) return;
        try {
            $sql = "CREATE TABLE IF NOT EXISTS snippet_categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL UNIQUE,
                color VARCHAR(20) DEFAULT '#fef9c3',
                text_color VARCHAR(20) DEFAULT '#854d0e',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            $this->db->exec($sql);

            // Seed some defaults if empty
            $stmt = $this->db->query("SELECT COUNT(*) FROM snippet_categories");
            if ($stmt->fetchColumn() == 0) {
                $defaults = [
                    ['name' => 'JavaScript', 'color' => '#fef9c3', 'text_color' => '#854d0e'],
                    ['name' => 'TypeScript', 'color' => '#eff6ff', 'text_color' => '#1e40af'],
                    ['name' => 'PHP', 'color' => '#f5f3ff', 'text_color' => '#5b21b6'],
                    ['name' => 'HTML', 'color' => '#fff7ed', 'text_color' => '#9a3412'],
                    ['name' => 'CSS', 'color' => '#ecfdf5', 'text_color' => '#065f46'],
                    ['name' => 'SQL', 'color' => '#fdf2f8', 'text_color' => '#9d174d'],
                    ['name' => 'Khác', 'color' => '#f1f5f9', 'text_color' => '#475569']
                ];
                $ins = $this->db->prepare("INSERT INTO snippet_categories (name, color, text_color) VALUES (:name, :color, :text_color)");
                foreach ($defaults as $d) {
                    $ins->execute($d);
                }
            }
        } catch (PDOException $e) {
            // Log error
        }
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM snippet_categories ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        try {
            $stmt = $this->db->prepare("INSERT INTO snippet_categories (name, color, text_color) VALUES (:name, :color, :text_color)");
            if ($stmt->execute([
                ':name' => $data['name'],
                ':color' => $data['color'] ?? '#fef9c3',
                ':text_color' => $data['text_color'] ?? '#854d0e'
            ])) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function update($id, $data) {
        try {
            $stmt = $this->db->prepare("UPDATE snippet_categories SET name = :name, color = :color, text_color = :text_color WHERE id = :id");
            return $stmt->execute([
                ':id' => $id,
                ':name' => $data['name'],
                ':color' => $data['color'],
                ':text_color' => $data['text_color']
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM snippet_categories WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function findByName($name) {
        $stmt = $this->db->prepare("SELECT * FROM snippet_categories WHERE name = ?");
        $stmt->execute([$name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
