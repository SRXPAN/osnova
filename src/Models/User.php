<?php
namespace App\Models;

use App\Core\Database;

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function create($data) {
        // Hash password if provided
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        // Set default role if not provided
        if (!isset($data['role'])) {
            $data['role'] = 'customer';
        }
        
        // Remove any fields that don't exist in the database
        $allowedFields = ['first_name', 'last_name', 'email', 'password', 'phone', 'address', 'role'];
        $filteredData = array_intersect_key($data, array_flip($allowedFields));
        
        $columns = implode(', ', array_keys($filteredData));
        $placeholders = ':' . implode(', :', array_keys($filteredData));
        
        $sql = "INSERT INTO users ({$columns}) VALUES ({$placeholders})";
        
        if ($this->db->execute($sql, $filteredData)) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }
    
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        return $this->db->fetch($sql, ['email' => $email]);
    }
    
    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        return $this->db->fetch($sql, ['id' => $id]);
    }
    
    public function update($id, $data) {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        // Remove any fields that don't exist in the database
        $allowedFields = ['first_name', 'last_name', 'email', 'password', 'phone', 'address', 'role'];
        $filteredData = array_intersect_key($data, array_flip($allowedFields));
        
        if (empty($filteredData)) {
            return false;
        }
        
        $setParts = [];
        foreach ($filteredData as $key => $value) {
            $setParts[] = "{$key} = :{$key}";
        }
        $setClause = implode(', ', $setParts);
        
        $sql = "UPDATE users SET {$setClause}, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $filteredData['id'] = $id;
        
        return $this->db->execute($sql, $filteredData);
    }
    
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    public function getFullName($user) {
        return trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
    }
    
    public function getAllUsers($limit = null, $offset = 0) {
        $sql = "SELECT id, first_name, last_name, email, role, created_at FROM users ORDER BY created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT :limit OFFSET :offset";
            return $this->db->fetchAll($sql, ['limit' => $limit, 'offset' => $offset]);
        }
        
        return $this->db->fetchAll($sql);
    }
    
    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        return $this->db->execute($sql, ['id' => $id]);
    }
    
    public function updateLastLogin($id) {
        $sql = "UPDATE users SET updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        return $this->db->execute($sql, ['id' => $id]);
    }
    
    /**
     * Перевіряє, чи існує email в базі даних (використовується для реєстрації).
     * @param string $email Email для перевірки.
     * @return bool True, якщо email знайдено.
     */
    public function emailExists(string $email): bool {
        // Створюємо псевдонім (alias) для існуючого методу isEmailTaken
        // Це вирішує помилку Undefined method у контролері.
        return $this->isEmailTaken($email);
    }
    
    public function isEmailTaken($email, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM users WHERE email = :email";
        $params = ['email' => $email];
        
        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'] > 0;
    }
}
