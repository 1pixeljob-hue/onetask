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
            p.id, p.name, p.link, p.customer, p.phone, 
            p.value, 
            p.date, p.status, 
            p.description as `desc`, 
            p.admin_url as adminUrl, 
            p.admin_user as adminUser, 
            p.admin_pass as adminPass,
            COALESCE(SUM(CASE WHEN pay.status = 'paid' THEN pay.amount ELSE 0 END), 0) as total_paid,
            COALESCE(SUM(pay.amount), 0) as total_milestone_value
            FROM projects p
            LEFT JOIN project_payments pay ON p.id = pay.project_id
            GROUP BY p.id
            ORDER BY p.date DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thống kê dự án cho Dashboard
     */
    public function getDashboardStats() {
        $stats = [
            'total_value' => 0,
            'planning' => 0,
            'doing' => 0,
            'testing' => 0,
            'done' => 0,
            'paused' => 0
        ];

        $stmt = $this->db->query("SELECT status, SUM(value) as total_val, COUNT(*) as count FROM projects GROUP BY status");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $stats['total_value'] += $row['total_val'];
            $status = (string)$row['status'];
            if (isset($stats[$status])) {
                $stats[$status] = (int)$row['count'];
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
        $stmt = $this->db->prepare("SELECT 
            id, name, link, customer, phone, 
            value, 
            date, status, 
            description as `desc`, 
            admin_url as adminUrl, 
            admin_user as adminUser, 
            admin_pass as adminPass 
            FROM projects WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy toàn bộ đợt thanh toán của dự án
     */
    public function getPayments($projectId) {
        $stmt = $this->db->prepare("SELECT * FROM project_payments WHERE project_id = :project_id ORDER BY id ASC");
        $stmt->execute([':project_id' => $projectId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lưu danh sách đợt thanh toán (Sync)
     * Xóa các đợt cũ và thêm mới (hoặc cập nhật)
     */
    public function savePayments($projectId, $payments) {
        // Lấy danh sách ID hiện tại
        $stmt = $this->db->prepare("SELECT id FROM project_payments WHERE project_id = :pid");
        $stmt->execute([':pid' => $projectId]);
        $existingIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $newIds = [];
        foreach ($payments as $payment) {
            if (isset($payment['id']) && !empty($payment['id'])) {
                // Cập nhật đợt hiện có
                $sql = "UPDATE project_payments SET 
                        milestone_name = :name, 
                        amount = :amount, 
                        notes = :notes 
                        WHERE id = :id AND project_id = :pid";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    ':id' => $payment['id'],
                    ':pid' => $projectId,
                    ':name' => $payment['name'],
                    ':amount' => $payment['amount'] ?? 0,
                    ':notes' => $payment['notes'] ?? ''
                ]);
                $newIds[] = $payment['id'];
            } else {
                // Thêm mới đợt
                $sql = "INSERT INTO project_payments (project_id, milestone_name, amount, notes, status) 
                        VALUES (:pid, :name, :amount, :notes, 'pending')";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    ':pid' => $projectId,
                    ':name' => $payment['name'],
                    ':amount' => $payment['amount'] ?? 0,
                    ':notes' => $payment['notes'] ?? ''
                ]);
            }
        }

        // Xóa các đợt không còn trong danh sách mới
        $toDelete = array_diff($existingIds, $newIds);
        if (!empty($toDelete)) {
            $placeholders = str_repeat('?,', count($toDelete) - 1) . '?';
            $stmt = $this->db->prepare("DELETE FROM project_payments WHERE id IN ($placeholders) AND project_id = ?");
            $params = array_values($toDelete);
            $params[] = $projectId;
            $stmt->execute($params);
        }

        return true;
    }

    /**
     * Xác nhận thanh toán cho một đợt
     */
    public function confirmPayment($paymentId) {
        $sql = "UPDATE project_payments SET status = 'paid', paid_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $paymentId]);
    }

    /**
     * Xóa một đợt thanh toán
     */
    public function deletePayment($id) {
        $stmt = $this->db->prepare("DELETE FROM project_payments WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Lấy thống kê thanh toán của dự án
     */
    public function getPaymentStats($projectId) {
        $stmt = $this->db->prepare("SELECT 
            SUM(CASE WHEN status = 'paid' THEN amount ELSE 0 END) as total_paid,
            SUM(amount) as total_milestone_value,
            COUNT(*) as milestone_count
            FROM project_payments WHERE project_id = :pid");
        $stmt->execute([':pid' => $projectId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
