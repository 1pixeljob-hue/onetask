<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class SnippetModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->createTableIfNotExists();
    }

    private function createTableIfNotExists() {
        $sql = "CREATE TABLE IF NOT EXISTS `snippets` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `language` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
            `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `code` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
            `line_count` int(11) DEFAULT 0,
            `char_count` int(11) DEFAULT 0,
            `created_at` timestamp NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        
        $this->db->exec($sql);
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM snippets ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM snippets WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save($data) {
        if (isset($data['id']) && !empty($data['id'])) {
            $sql = "UPDATE snippets SET 
                    title = :title, 
                    language = :language, 
                    description = :description, 
                    code = :code, 
                    line_count = :line_count, 
                    char_count = :char_count 
                    WHERE id = :id";
        } else {
            $sql = "INSERT INTO snippets (title, language, description, code, line_count, char_count) 
                    VALUES (:title, :language, :description, :code, :line_count, :char_count)";
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM snippets WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
