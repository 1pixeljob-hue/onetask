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
        try {
            $sql = "SELECT 
                        p.id, p.name, p.link, p.customer, p.phone, p.customer_id,
                        p.value, 
                        p.date, p.status, 
                        p.description as `desc`, 
                        p.admin_url as adminUrl, 
                        p.admin_user as adminUser, 
                        p.admin_pass as adminPass,
                        c.name as customer_name,
                        p.date as created_at_alt,
                        (SELECT COALESCE(SUM(amount), 0) FROM project_payments WHERE project_id = p.id AND status = 'paid') as total_paid
                    FROM projects p
                    LEFT JOIN customers c ON p.customer_id = c.id
                    ORDER BY p.date DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in ProjectModel::getAll: " . $e->getMessage());
            return [];
        }
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
        $sql = "INSERT INTO projects (name, link, status, description, date, customer, phone, customer_id, admin_url, admin_user, admin_pass, value) 
                VALUES (:name, :link, :status, :description, :date, :customer, :phone, :customer_id, :admin_url, :admin_user, :admin_pass, :value)";
        $stmt = $this->db->prepare($sql);
        // Lấy admin_url từ nhiều nguồn để tránh bị rỗng (ưu tiên admin_url -> adminUrl -> link)
        $adminUrl = !empty($data['admin_url']) ? $data['admin_url'] : (!empty($data['adminUrl']) ? $data['adminUrl'] : ($data['link'] ?? ''));
        $adminUser = !empty($data['admin_user']) ? $data['admin_user'] : ($data['adminUser'] ?? '');
        $adminPass = !empty($data['admin_pass']) ? $data['admin_pass'] : ($data['adminPass'] ?? '');

        return $stmt->execute([
            ':name' => $data['name'],
            ':link' => !empty($data['link']) ? $data['link'] : $adminUrl,
            ':status' => $data['status'],
            ':description' => $data['desc'] ?? '',
            ':date' => $data['date'],
            ':customer' => $data['customer'] ?? '',
            ':phone' => $data['phone'] ?? '',
            ':customer_id' => !empty($data['customer_id']) ? $data['customer_id'] : null,
            ':admin_url' => $adminUrl,
            ':admin_user' => $adminUser,
            ':admin_pass' => $adminPass,
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
                customer_id = :customer_id,
                admin_url = :admin_url, 
                admin_user = :admin_user, 
                admin_pass = :admin_pass, 
                value = :value 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        // Lấy admin_url từ nhiều nguồn để tránh bị rỗng (ưu tiên admin_url -> adminUrl -> link)
        $adminUrl = !empty($data['admin_url']) ? $data['admin_url'] : (!empty($data['adminUrl']) ? $data['adminUrl'] : ($data['link'] ?? ''));
        $adminUser = !empty($data['admin_user']) ? $data['admin_user'] : ($data['adminUser'] ?? '');
        $adminPass = !empty($data['admin_pass']) ? $data['admin_pass'] : ($data['adminPass'] ?? '');

        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':link' => !empty($data['link']) ? $data['link'] : $adminUrl,
            ':status' => $data['status'],
            ':description' => $data['desc'] ?? '',
            ':date' => $data['date'],
            ':customer' => $data['customer'] ?? '',
            ':phone' => $data['phone'] ?? '',
            ':customer_id' => !empty($data['customer_id']) ? $data['customer_id'] : null,
            ':admin_url' => $adminUrl,
            ':admin_user' => $adminUser,
            ':admin_pass' => $adminPass,
            ':value' => $data['value'] ?? 0
        ]);
    }

    /**
     * Cập nhật trạng thái dự án
     */
    public function updateStatus($id, $status) {
        $sql = "UPDATE projects SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':status' => $status
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
     * Lấy doanh thu theo từng tháng trong năm (Dựa trên đợt thanh toán thực tế)
     */
    public function getMonthlyRevenue(int $year) {
        $monthlyData = array_fill(1, 12, 0);

        $sql = "SELECT MONTH(paid_at) as month, SUM(amount) as total 
                FROM project_payments 
                WHERE YEAR(paid_at) = :year AND status = 'paid'
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
            p.id, p.name, p.link, p.customer, p.phone, p.customer_id,
            p.value, 
            p.date, p.status, 
            p.description as `desc`, 
            p.admin_url as adminUrl, 
            p.admin_user as adminUser, 
            p.admin_pass as adminPass,
            c.name as customer_name,
            c.phone as customer_phone,
            c.email as customer_email,
            c.type as customer_type,
            c.company as customer_company
            FROM projects p
            LEFT JOIN customers c ON p.customer_id = c.id
            WHERE p.id = :id");
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
     * Tính năng mới: Tự động hoàn thành dự án nếu tất cả các đợt đã thanh toán
     */
    public function confirmPayment($paymentId) {
        // 1. Lấy project_id từ đợt thanh toán
        $stmt = $this->db->prepare("SELECT project_id FROM project_payments WHERE id = :id");
        $stmt->execute([':id' => $paymentId]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$payment) return false;
        $projectId = $payment['project_id'];

        // 2. Cập nhật trạng thái đợt thanh toán
        $sql = "UPDATE project_payments SET status = 'paid', paid_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([':id' => $paymentId]);

        if ($success) {
            // 3. Kiểm tra xem tất cả các đợt của dự án này đã thanh toán chưa
            $stmt = $this->db->prepare("SELECT COUNT(*) as unpaid FROM project_payments WHERE project_id = :pid AND status != 'paid'");
            $stmt->execute([':pid' => $projectId]);
            $unpaidCount = $stmt->fetch(PDO::FETCH_ASSOC)['unpaid'];

            $projectCompleted = false;
            if ($unpaidCount == 0) {
                // 4. Cập nhật trạng thái dự án sang 'done'
                $stmt = $this->db->prepare("UPDATE projects SET status = 'done' WHERE id = :pid");
                $stmt->execute([':pid' => $projectId]);
                $projectCompleted = true;
            }

            return [
                'success' => true,
                'projectCompleted' => $projectCompleted,
                'projectId' => $projectId
            ];
        }

        return false;
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

    /**
     * Lấy toàn bộ đợt thanh toán đã trả (Cho báo cáo)
     */
    public function getAllPaidPayments() {
        $stmt = $this->db->query("SELECT p.name as project_name, pay.* 
                                 FROM project_payments pay 
                                 JOIN projects p ON pay.project_id = p.id 
                                 WHERE pay.status = 'paid' 
                                 ORDER BY pay.paid_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
