<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Lớp quản lý kết nối Database bằng PDO
 */
class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        // Nạp file cấu hình nếu chưa có
        if (!defined('DB_HOST')) {
            require_once __DIR__ . '/../../config/database.php';
        }

        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die("Lỗi kết nối bộ cơ sở dữ liệu: " . $e->getMessage());
        }
    }

    /**
     * Lấy thực thể duy nhất của kết nối (Singleton Pattern)
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Trả về đối tượng PDO để thực hiện truy vấn
     */
    public function getConnection() {
        return $this->connection;
    }
}
