<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class CustomerModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Lấy toàn bộ danh sách khách hàng
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM customers ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tìm khách hàng theo ID
     */
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM customers WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm khách hàng mới
     */
    public function create($data) {
        $sql = "INSERT INTO customers (type, name, phone, email, tax_id, representative, address, company, notes) 
                VALUES (:type, :name, :phone, :email, :tax_id, :representative, :address, :company, :notes)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':type' => $data['type'] ?? 'individual',
            ':name' => $data['name'],
            ':phone' => $data['phone'] ?? '',
            ':email' => $data['email'] ?? '',
            ':tax_id' => $data['tax_id'] ?? '',
            ':representative' => $data['representative'] ?? '',
            ':address' => $data['address'] ?? '',
            ':company' => $data['company'] ?? '',
            ':notes' => $data['notes'] ?? ''
        ]);
    }

    /**
     * Cập nhật thông tin khách hàng
     */
    public function update($id, $data) {
        $sql = "UPDATE customers SET 
                type = :type,
                name = :name, 
                phone = :phone, 
                email = :email, 
                tax_id = :tax_id,
                representative = :representative,
                address = :address, 
                company = :company, 
                notes = :notes 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':type' => $data['type'] ?? 'individual',
            ':name' => $data['name'],
            ':phone' => $data['phone'] ?? '',
            ':email' => $data['email'] ?? '',
            ':tax_id' => $data['tax_id'] ?? '',
            ':representative' => $data['representative'] ?? '',
            ':address' => $data['address'] ?? '',
            ':company' => $data['company'] ?? '',
            ':notes' => $data['notes'] ?? ''
        ]);
    }

    /**
     * Xóa khách hàng
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM customers WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Xóa nhiều khách hàng cùng lúc
     */
    public function deleteBulk($ids) {
        if (empty($ids)) return true;
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        $stmt = $this->db->prepare("DELETE FROM customers WHERE id IN ($placeholders)");
        return $stmt->execute(array_values($ids));
    }
    /**
     * Tìm khách hàng theo tên (kiểm tra trùng)
     */
    public function findByName($name, $excludeId = null) {
        $sql = "SELECT * FROM customers WHERE LOWER(name) = LOWER(:name)";
        $params = [':name' => $name];
        
        if ($excludeId) {
            $sql .= " AND id != :excludeId";
            $params[':excludeId'] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
