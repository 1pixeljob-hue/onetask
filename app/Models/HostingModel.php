<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class HostingModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Lấy toàn bộ danh sách hosting
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT 
            id, name, domain, provider, price, 
            reg_date as regDate, 
            exp_date as expDate, 
            usage_period as `usage` 
            FROM hostings ORDER BY exp_date ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thống kê hosting cho Dashboard
     */
    public function getDashboardStats() {
        $stmt = $this->db->query("SELECT COUNT(*) as count, SUM(price) as total_val FROM hostings");
        return $stmt->fetch();
    }
}
