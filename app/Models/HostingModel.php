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

    /**
     * Thêm hosting mới
     */
    public function create($data) {
        $sql = "INSERT INTO hostings (name, domain, provider, price, reg_date, exp_date, usage_period, notes) 
                VALUES (:name, :domain, :provider, :price, :reg_date, :exp_date, :usage_period, :notes)";
        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([
            ':name' => $data['name'],
            ':domain' => $data['domain'] ?? '',
            ':provider' => $data['provider'] ?? '',
            ':price' => $data['price'] ?? 0,
            ':reg_date' => $data['regDate'] ?? null,
            ':exp_date' => $data['expDate'] ?? null,
            ':usage_period' => $data['usage'] ?? '',
            ':notes' => $data['notes'] ?? ''
        ]);

        if ($success) {
            $hostingId = $this->db->lastInsertId();
            // Ghi nhận lần thanh toán đầu tiên
            $this->addRenewal($hostingId, [
                'amount' => $data['price'],
                'regDate' => $data['regDate'],
                'expDate' => $data['expDate'],
                'notes' => 'Khởi tạo hosting'
            ]);
        }
        return $success;
    }

    /**
     * Cập nhật hosting
     */
    public function update($id, $data) {
        $sql = "UPDATE hostings SET 
                name = :name, 
                domain = :domain, 
                provider = :provider, 
                price = :price, 
                reg_date = :reg_date, 
                exp_date = :exp_date, 
                usage_period = :usage_period 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':domain' => $data['domain'] ?? '',
            ':provider' => $data['provider'] ?? '',
            ':price' => $data['price'] ?? 0,
            ':reg_date' => $data['regDate'] ?? null,
            ':exp_date' => $data['expDate'] ?? null,
            ':usage_period' => $data['usage'] ?? ''
        ]);
    }

    /**
     * Xóa hosting
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM hostings WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Xóa nhiều hosting cùng lúc
     */
    public function deleteBulk($ids) {
        if (empty($ids)) return true;
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        $stmt = $this->db->prepare("DELETE FROM hostings WHERE id IN ($placeholders)");
        return $stmt->execute(array_values($ids));
    }

    /**
     * Tìm hosting theo ID
     */
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM hostings WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy toàn bộ lịch sử gia hạn (cho Reports)
     */
    public function getAllRenewals() {
        $stmt = $this->db->query("SELECT * FROM hosting_renewals ORDER BY reg_date DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy lịch sử gia hạn của 1 hosting
     */
    public function getRenewals($hostingId) {
        $stmt = $this->db->prepare("SELECT * FROM hosting_renewals WHERE hosting_id = :hid ORDER BY reg_date DESC");
        $stmt->execute([':hid' => $hostingId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm bản ghi gia hạn mới
     */
    public function addRenewal($hostingId, $data) {
        $sql = "INSERT INTO hosting_renewals (hosting_id, amount, reg_date, exp_date, notes) 
                VALUES (:hid, :amount, :rdate, :edate, :notes)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':hid' => $hostingId,
            ':amount' => $data['amount'] ?? 0,
            ':rdate' => $data['regDate'] ?? null,
            ':edate' => $data['expDate'] ?? null,
            ':notes' => $data['notes'] ?? ''
        ]);
    }
}
