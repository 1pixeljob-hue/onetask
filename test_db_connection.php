<?php
/**
 * Script kiểm tra kết nối Database
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/Core/Database.php';

use App\Core\Database;

echo "--- ĐANG KIỂM TRA KẾT NỐI DATABASE ---\n";
echo "Host: " . DB_HOST . "\n";
echo "DB Name: " . DB_NAME . "\n";
echo "User: " . DB_USER . "\n";

try {
    $db = Database::getInstance()->getConnection();
    echo "✅ KẾT NỐI THÀNH CÔNG!\n";
    
    // Kiểm tra danh sách bảng
    echo "--- CÁC BẢNG TRONG DATABASE ---\n";
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "⚠️ Database trống (chưa có bảng nào).\n";
    } else {
        foreach ($tables as $table) {
            echo "- " . $table . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ KẾT NỐI THẤT BẠI!\n";
    echo "Lỗi: " . $e->getMessage() . "\n";
}
