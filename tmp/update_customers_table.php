<?php
require_once __DIR__ . '/../app/Core/Database.php';

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    // Add columns if they don't exist
    $sql = "ALTER TABLE `customers` 
            ADD COLUMN `type` ENUM('individual', 'company') DEFAULT 'individual' AFTER `id`,
            ADD COLUMN `tax_id` VARCHAR(50) DEFAULT NULL AFTER `email`,
            ADD COLUMN `representative` VARCHAR(255) DEFAULT NULL AFTER `tax_id`
            ON DUPLICATE KEY UPDATE id=id; -- Hack to avoid error if exists if supported, but ALTER TABLE doesn't support this.
            ";
    
    // I'll check if columns exist first for safety
    $columns = $db->query("SHOW COLUMNS FROM `customers`")->fetchAll(PDO::FETCH_COLUMN);
    
    $queries = [];
    if (!in_array('type', $columns)) {
        $queries[] = "ALTER TABLE `customers` ADD COLUMN `type` ENUM('individual', 'company') DEFAULT 'individual' AFTER `id`";
    }
    if (!in_array('tax_id', $columns)) {
        $queries[] = "ALTER TABLE `customers` ADD COLUMN `tax_id` VARCHAR(50) DEFAULT NULL AFTER `email`";
    }
    if (!in_array('representative', $columns)) {
        $queries[] = "ALTER TABLE `customers` ADD COLUMN `representative` VARCHAR(255) DEFAULT NULL AFTER `tax_id`";
    }

    foreach ($queries as $q) {
        $db->exec($q);
        echo "Executed: $q\n";
    }
    
    echo "Table 'customers' updated successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
