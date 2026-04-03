<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class NotificationModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Lấy tất cả thông báo của người dùng (chưa đọc trước)
     */
    public function getAll($limit = 10) {
        $stmt = $this->db->prepare("SELECT * FROM notifications ORDER BY is_read ASC, created_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy số lượng thông báo chưa đọc
     */
    public function getUnreadCount() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM notifications WHERE is_read = 0");
        return $stmt->fetchColumn();
    }

    /**
     * Tạo thông báo mới
     */
    public function create($data) {
        $sql = "INSERT INTO notifications (title, message, type, category, item_id, link) 
                VALUES (:title, :message, :type, :category, :item_id, :link)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':title' => $data['title'],
            ':message' => $data['message'],
            ':type' => $data['type'] ?? 'info',
            ':category' => $data['category'] ?? 'general',
            ':item_id' => $data['item_id'] ?? null,
            ':link' => $data['link'] ?? null
        ]);
    }

    /**
     * Kiểm tra thông báo đã tồn tại chưa (để tránh tạo trùng)
     */
    public function exists($category, $item_id, $title) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM notifications 
                                   WHERE category = :cat AND item_id = :item AND title = :title");
        $stmt->execute([
            ':cat' => $category,
            ':item' => $item_id,
            ':title' => $title
        ]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Đánh dấu đã đọc
     */
    public function markAsRead($id) {
        $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Đánh dấu tất cả đã đọc
     */
    public function markAllAsRead() {
        return $this->db->exec("UPDATE notifications SET is_read = 1");
    }

    /**
     * Xóa thông báo
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM notifications WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Xóa nhiều thông báo cùng lúc
     */
    public function deleteBulk($ids) {
        if (empty($ids)) return false;
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->db->prepare("DELETE FROM notifications WHERE id IN ($placeholders)");
        return $stmt->execute($ids);
    }
}
