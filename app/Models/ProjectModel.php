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
            value, 
            date, status, 
            description as `desc`, 
            admin_url as adminUrl, 
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

    /**
     * Thêm dự án mới
     */
    public function create($data) {
        $sql = "INSERT INTO projects (name, status, description, date, customer, phone, admin_url, admin_user, admin_pass, value) 
                VALUES (:name, :status, :description, :date, :customer, :phone, :admin_url, :admin_user, :admin_pass, :value)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':status' => $data['status'],
            ':description' => $data['desc'] ?? '',
            ':date' => $data['date'],
            ':customer' => $data['customer'],
            ':phone' => $data['phone'] ?? '',
            ':admin_url' => $data['adminUrl'] ?? '',
            ':admin_user' => $data['adminUser'] ?? '',
            ':admin_pass' => $data['adminPass'] ?? '',
            ':value' => $data['value'] ?? 0
        ]);
    }

    /**
     * Cập nhật dự án
     */
    public function update($id, $data) {
        $sql = "UPDATE projects SET 
                name = :name, 
                status = :status, 
                description = :description, 
                date = :date, 
                customer = :customer, 
                phone = :phone, 
                admin_url = :admin_url, 
                admin_user = :admin_user, 
                admin_pass = :admin_pass, 
                value = :value 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':status' => $data['status'],
            ':description' => $data['desc'] ?? '',
            ':date' => $data['date'],
            ':customer' => $data['customer'],
            ':phone' => $data['phone'] ?? '',
            ':admin_url' => $data['adminUrl'] ?? '',
            ':admin_user' => $data['adminUser'] ?? '',
            ':admin_pass' => $data['adminPass'] ?? '',
            ':value' => $data['value'] ?? 0
        ]);
    }

    /**
     * Xóa dự án
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM projects WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
