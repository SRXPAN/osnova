<?php
namespace App\Models;

use App\Core\Database;

class Category {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getActive() {
        $sql = "SELECT * FROM categories WHERE is_active = 1 ORDER BY name";
        return $this->db->query($sql)->fetchAll();
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM categories WHERE id = ? AND is_active = 1";
        return $this->db->query($sql, [$id])->fetch();
    }
    
    public function create($data) {
        return $this->db->insert('categories', $data);
    }
    
    public function update($id, $data) {
        return $this->db->update('categories', $data, 'id = :id', ['id' => $id]);
    }
    
    public function delete($id) {
        return $this->db->update('categories', ['is_active' => 0], 'id = :id', ['id' => $id]);
    }
}
