<?php
require_once __DIR__ . '/app/Core/Database.php';

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    $db->exec("ALTER TABLE passwords ADD COLUMN category VARCHAR(100) DEFAULT 'Khác' AFTER title");
    echo "Column 'category' added successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
