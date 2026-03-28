<?php
require_once 'config/database.php';
require_once 'app/Core/Database.php';

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT * FROM passwords");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Row count: " . count($rows) . "\n";
    foreach ($rows as $row) {
        echo "ID: " . $row['id'] . " | Title: " . $row['title'] . " | Category: " . $row['category'] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
