<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class LogModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->checkAndCreateTable();
    }

    private function checkAndCreateTable()
    {
        if (!$this->db)
            return;
        try {
            // Auto-migration: Check if table even exists
            $tableExists = $this->db->query("SHOW TABLES LIKE 'activity_logs'")->fetch();
            
            if ($tableExists) {
                // Check if column 'data' exists (Restore feature)
                $checkData = $this->db->query("SHOW COLUMNS FROM activity_logs LIKE 'data'");
                if (!$checkData->fetch()) {
                    // Update schema for existing table - Add 'data' column
                    $this->db->exec("ALTER TABLE activity_logs ADD COLUMN data LONGTEXT DEFAULT NULL AFTER item_name;");
                }
            }

            $sql = "CREATE TABLE IF NOT EXISTS activity_logs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                action VARCHAR(255) NOT NULL,
                module VARCHAR(50) DEFAULT NULL,
                user_name VARCHAR(100) DEFAULT 'quydev',
                item_name VARCHAR(255) DEFAULT NULL,
                data LONGTEXT DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            $this->db->exec($sql);
        } catch (\PDOException $e) {
            // Ghi lỗi nếu cần nhưng không làm hỏng ứng dụng
            error_log("Migration error: " . $e->getMessage());
        }
    }

    /**
     * Tìm một bản ghi log theo ID
     */
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM activity_logs WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lưu một bản ghi log mới
     * @param string $module Tên module (Project, Hosting, Passwords, CodeX)
     * @param string $action Hành động (Tạo mới, Cập nhật, Xoá)
     * @param string $itemName Tên item bị tác động
     * @param string $userName Người thực hiện (mặc định 'quydev')
     * @param string $data Dữ liệu JSON sao lưu (cho hành động Xoá)
     * @return bool
     */
    public function addLog($module, $action, $itemName, $userName = 'quydev', $data = null)
    {
        if (!$this->db)
            return false;
        try {
            $sql = "INSERT INTO activity_logs (module, action, item_name, user_name, data) 
                    VALUES (:module, :action, :item_name, :user_name, :data)";
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                ':module' => $module,
                ':action' => $action,
                ':item_name' => $itemName,
                ':user_name' => $userName,
                ':data' => $data
            ]);

            if ($success) {
                $this->pruneLogs(200);
            }

            return $success;
        } catch (\Exception $e) {
            // Ghi log lỗi vào file hệ thống, không echo ra màn hình
            error_log("Add Log Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Giới hạn số lượng bản ghi logs để tránh làm phình Database
     */
    public function pruneLogs($limit = 200)
    {
        try {
            // Xóa các bản ghi cũ nhất nếu vượt quá giới hạn
            $sql = "DELETE FROM activity_logs 
                    WHERE id NOT IN (
                        SELECT id FROM (
                            SELECT id FROM activity_logs 
                            ORDER BY created_at DESC 
                            LIMIT :limit
                        ) AS tmp
                    )";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\Exception $e) {
            error_log("Prune Log Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy danh sách log với lọc và phân trang
     */
    public function getAll($filters = [], $limit = 10, $offset = 0)
    {
        $where = [];
        $params = [];

        if (!empty($filters['module'])) {
            $where[] = "module = :module";
            $params[':module'] = $filters['module'];
        }

        if (!empty($filters['action'])) {
            $where[] = "action = :action";
            $params[':action'] = $filters['action'];
        }

        if (!empty($filters['search'])) {
            $where[] = "(item_name LIKE :search OR user_name LIKE :search)";
            $params[':search'] = "%" . $filters['search'] . "%";
        }

        $sql = "SELECT * FROM activity_logs";
        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        // PDO bindValue for LIMIT and OFFSET because execute with array treats them as strings which MySQL might reject in some modes
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy tổng số bản ghi log (để phân trang)
     */
    public function getCount($filters = [])
    {
        $where = [];
        $params = [];

        if (!empty($filters['module'])) {
            $where[] = "module = :module";
            $params[':module'] = $filters['module'];
        }

        if (!empty($filters['action'])) {
            $where[] = "action = :action";
            $params[':action'] = $filters['action'];
        }

        if (!empty($filters['search'])) {
            $where[] = "(item_name LIKE :search OR user_name LIKE :search)";
            $params[':search'] = "%" . $filters['search'] . "%";
        }

        $sql = "SELECT COUNT(*) as total FROM activity_logs";
        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return (int) $result['total'];
    }

    /**
     * Xóa một bản ghi log
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM activity_logs WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Dọn dẹp log cũ (Tuỳ chọn)
     */
    public function clearOldLogs($days = 30)
    {
        $stmt = $this->db->prepare("DELETE FROM activity_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL :days DAY)");
        return $stmt->execute([':days' => $days]);
    }
}
