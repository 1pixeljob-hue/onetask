<?php
require_once 'config/database.php';
require_once 'app/Core/BaseController.php';
require_once 'app/Core/Database.php';

try {
    $db = App\Core\Database::getInstance()->getConnection();
    // Test users table
    $stmt = $db->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "SUCCESS: Users table exists.\n";
    } else {
        echo "FAILURE: Users table NOT found. Please run database/users.sql\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
