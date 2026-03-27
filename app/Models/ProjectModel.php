<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class ProjectModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Lấy toàn bộ danh sách dự án
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT 
            id, name, link, customer, phone, 
            value as valRaw, 
            date, status, 
            description as `desc`, 
            admin_url as adminLink, 
            admin_user as adminUser, 
            admin_pass as adminPass 
            FROM projects ORDER BY date DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thống kê dự án cho Dashboard
     */
    public function getDashboardStats() {
        $stats = [
            'total_value' => 0,
            'doing' => 0,
            'testing' => 0,
            'done' => 0
        ];

        $stmt = $this->db->query("SELECT status, SUM(value) as total_val, COUNT(*) as count FROM projects GROUP BY status");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $stats['total_value'] += $row['total_val'];
            if (isset($stats[$row['status']])) {
                $stats[$row['status']] = $row['count'];
            }
        }

        return $stats;
    }
}
