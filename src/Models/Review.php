<?php
namespace App\Models;

use App\Core\Database;

class Review {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getByProductId($productId, $filters = []) {
        $sql = "SELECT r.*, u.first_name, u.last_name,
                       CONCAT(u.first_name, ' ', u.last_name) as user_name
                FROM reviews r 
                LEFT JOIN users u ON r.user_id = u.id 
                WHERE r.product_id = :product_id";
        
        $params = ['product_id' => $productId];
        
        if (isset($filters['is_approved'])) {
            $sql .= " AND r.is_approved = :is_approved";
            $params['is_approved'] = $filters['is_approved'];
        }
        
        $sql .= " ORDER BY r.created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function create($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('reviews', $data);
    }
    
    public function approve($id) {
        return $this->db->update('reviews', ['is_approved' => 1], 'id = :id', ['id' => $id]);
    }
    
    public function reject($id) {
        return $this->db->update('reviews', ['is_approved' => 0], 'id = :id', ['id' => $id]);
    }
}
