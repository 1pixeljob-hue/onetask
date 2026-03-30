<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class LogModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Lưu một bản ghi log mới
     * @param string $module Tên module (Project, Hosting, Passwords, CodeX)
     * @param string $action Hành động (Tạo mới, Cập nhật, Xoá)
     * @param string $itemName Tên item bị tác động
     * @param string $userName Người thực hiện (mặc định 'quydev')
     * @return bool
     */
    public function addLog($module, $action, $itemName, $userName = 'quydev') {
        $sql = "INSERT INTO activity_logs (module, action, item_name, user_name) 
                VALUES (:module, :action, :item_name, :user_name)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':module' => $module,
            ':action' => $action,
            ':item_name' => $itemName,
            ':user_name' => $userName
        ]);
    }

    /**
     * Lấy danh sách log với lọc và phân trang
     */
    public function getAll($filters = [], $limit = 10, $offset = 0) {
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
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy tổng số bản ghi log (để phân trang)
     */
    public function getCount($filters = []) {
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
        return (int)$result['total'];
    }

    /**
     * Xóa một bản ghi log
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM activity_logs WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Dọn dẹp log cũ (Tuỳ chọn)
     */
    public function clearOldLogs($days = 30) {
        $stmt = $this->db->prepare("DELETE FROM activity_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL :days DAY)");
        return $stmt->execute([':days' => $days]);
    }
}
