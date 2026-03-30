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
        $sql = "INSERT INTO projects (name, link, status, description, date, customer, phone, admin_url, admin_user, admin_pass, value) 
                VALUES (:name, :link, :status, :description, :date, :customer, :phone, :admin_url, :admin_user, :admin_pass, :value)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':link' => $data['link'] ?? '',
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
                link = :link,
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
            ':link' => $data['link'] ?? '',
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

    /**
     * Xóa nhiều dự án cùng lúc
     */
    public function deleteBulk($ids) {
        if (empty($ids)) return true;
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        $stmt = $this->db->prepare("DELETE FROM projects WHERE id IN ($placeholders)");
        return $stmt->execute(array_values($ids));
    }

    /**
     * Lấy doanh thu theo từng tháng trong năm
     */
    public function getMonthlyRevenue(int $year) {
        $monthlyData = array_fill(1, 12, 0);

        $sql = "SELECT MONTH(date) as month, SUM(value) as total 
                FROM projects 
                WHERE YEAR(date) = :year 
                GROUP BY month 
                ORDER BY month ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':year' => $year]);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $monthlyData[(int)$row['month']] = (float)$row['total'];
        }

        return array_values($monthlyData); // Trả về mảng 12 phần tử (index 0-11)
    }

    /**
     * Tìm dự án theo ID
     */
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM projects WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
