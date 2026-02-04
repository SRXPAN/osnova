<?php
namespace App\Models;

use App\Core\Database;

class Product {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAll($filters = []) {
        $sql = "SELECT p.*, c.name as category_name, 
                       AVG(r.rating) as avg_rating,
                       COUNT(r.id) as review_count
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN reviews r ON p.id = r.product_id AND r.is_approved = 1
                WHERE p.is_active = 1";
        
        $params = [];
        
        if (!empty($filters['category_id'])) {
            $sql .= " AND p.category_id = :category_id";
            $params['category_id'] = $filters['category_id'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (MATCH(p.name, p.description, p.flavor) AGAINST(:search IN NATURAL LANGUAGE MODE)
                         OR p.name LIKE :search_like OR p.flavor LIKE :search_like)";
            $params['search'] = $filters['search'];
            $params['search_like'] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($filters['min_price'])) {
            $sql .= " AND p.price >= :min_price";
            $params['min_price'] = $filters['min_price'];
        }
        
        if (!empty($filters['max_price'])) {
            $sql .= " AND p.price <= :max_price";
            $params['max_price'] = $filters['max_price'];
        }
        
        if (!empty($filters['nicotine_content'])) {
            $sql .= " AND p.nicotine_content = :nicotine_content";
            $params['nicotine_content'] = $filters['nicotine_content'];
        }
        
        $sql .= " GROUP BY p.id";
        
        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'DESC';
        
        switch ($sortBy) {
            case 'price':
                $sql .= " ORDER BY p.price {$sortOrder}";
                break;
            case 'rating':
                $sql .= " ORDER BY avg_rating {$sortOrder}";
                break;
            case 'name':
                $sql .= " ORDER BY p.name {$sortOrder}";
                break;
            default:
                $sql .= " ORDER BY p.created_at {$sortOrder}";
        }
        
        // Pagination
        if (!empty($filters['limit'])) {
            $sql .= " LIMIT :limit";
            $params['limit'] = (int)$filters['limit'];
            
            if (!empty($filters['offset'])) {
                $sql .= " OFFSET :offset";
                $params['offset'] = (int)$filters['offset'];
            }
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getFeatured($limit = 8) {
        $sql = "SELECT p.*, c.name as category_name, 
                       AVG(r.rating) as avg_rating,
                       COUNT(r.id) as review_count
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN reviews r ON p.id = r.product_id AND r.is_approved = 1
                WHERE p.is_active = 1 
                GROUP BY p.id
                ORDER BY p.created_at DESC 
                LIMIT ?";
        
        return $this->db->query($sql, [$limit])->fetchAll();
    }
    
    public function getFiltered($categoryId = null, $search = null, $page = 1, $perPage = 12) {
        $offset = ($page - 1) * $perPage;
        $params = [];
        
        $sql = "SELECT p.*, c.name as category_name, 
                       AVG(r.rating) as avg_rating,
                       COUNT(r.id) as review_count
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN reviews r ON p.id = r.product_id AND r.is_approved = 1
                WHERE p.is_active = 1";
        
        if ($categoryId) {
            $sql .= " AND p.category_id = ?";
            $params[] = $categoryId;
        }
        
        if ($search) {
            $sql .= " AND (p.name LIKE ? OR p.description LIKE ? OR p.flavor LIKE ?)";
            $searchParam = "%{$search}%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        $sql .= " GROUP BY p.id ORDER BY p.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;
        
        return $this->db->query($sql, $params)->fetchAll();
    }
    
    public function getFilteredCount($categoryId = null, $search = null) {
        $params = [];
        
        $sql = "SELECT COUNT(*) as count FROM products p WHERE p.is_active = 1";
        
        if ($categoryId) {
            $sql .= " AND p.category_id = ?";
            $params[] = $categoryId;
        }
        
        if ($search) {
            $sql .= " AND (p.name LIKE ? OR p.description LIKE ? OR p.flavor LIKE ?)";
            $searchParam = "%{$search}%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        $result = $this->db->query($sql, $params)->fetch();
        return $result['count'];
    }
    
    public function getById($id) {
        $sql = "SELECT p.*, c.name as category_name, 
                       AVG(r.rating) as avg_rating,
                       COUNT(r.id) as review_count
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN reviews r ON p.id = r.product_id AND r.is_approved = 1
                WHERE p.id = ? AND p.is_active = 1
                GROUP BY p.id";
        
        return $this->db->query($sql, [$id])->fetch();
    }
    
    public function getBySlug($slug) {
        $sql = "SELECT p.*, c.name as category_name,
                       AVG(r.rating) as avg_rating,
                       COUNT(r.id) as review_count
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN reviews r ON p.id = r.product_id AND r.is_approved = 1
                WHERE p.slug = :slug AND p.is_active = 1
                GROUP BY p.id";
        
        return $this->db->fetch($sql, ['slug' => $slug]);
    }
    
    public function create($data) {
        return $this->db->insert('products', $data);
    }
    
    public function update($id, $data) {
        return $this->db->update('products', $data, 'id = :id', ['id' => $id]);
    }
    
    public function delete($id) {
        return $this->db->update('products', ['is_active' => 0], 'id = :id', ['id' => $id]);
    }
    
    public function updateStock($id, $quantity) {
        $sql = "UPDATE products SET stock_quantity = stock_quantity - :quantity WHERE id = :id";
        return $this->db->query($sql, ['id' => $id, 'quantity' => $quantity]);
    }
    
    public function getImages($productId) {
        $sql = "SELECT * FROM product_images WHERE product_id = :product_id ORDER BY is_primary DESC, id ASC";
        return $this->db->fetchAll($sql, ['product_id' => $productId]);
    }
    
    public function getFeaturedProducts($limit = 6) {
        $sql = "SELECT p.*, c.name as category_name,
                       AVG(r.rating) as avg_rating,
                       COUNT(r.id) as review_count
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN reviews r ON p.id = r.product_id AND r.is_approved = 1
                WHERE p.is_active = 1
                GROUP BY p.id
                ORDER BY avg_rating DESC, review_count DESC
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, ['limit' => $limit]);
    }
    
    public function getRelated($categoryId, $excludeId, $limit = 4) {
        $sql = "SELECT p.*, c.name as category_name, 
                       AVG(r.rating) as avg_rating
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN reviews r ON p.id = r.product_id AND r.is_approved = 1
                WHERE p.category_id = ? AND p.id != ? AND p.is_active = 1
                GROUP BY p.id
                ORDER BY RAND() 
                LIMIT ?";
        
        return $this->db->query($sql, [$categoryId, $excludeId, $limit])->fetchAll();
    }
    
    public function getReviews($productId) {
        $sql = "SELECT r.*, u.first_name, u.last_name,
                       CONCAT(u.first_name, ' ', u.last_name) as user_name
                FROM reviews r
                JOIN users u ON r.user_id = u.id
                WHERE r.product_id = ? AND r.is_approved = 1
                ORDER BY r.created_at DESC";
        
        return $this->db->query($sql, [$productId])->fetchAll();
    }
    
    public function search($query, $limit = 20) {
        $searchParam = "%{$query}%";
        
        $sql = "SELECT p.*, c.name as category_name, 
                       AVG(r.rating) as avg_rating,
                       COUNT(r.id) as review_count
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN reviews r ON p.id = r.product_id AND r.is_approved = 1
                WHERE p.is_active = 1 
                AND (p.name LIKE :search 
                     OR p.description LIKE :search 
                     OR p.flavor LIKE :search
                     OR c.name LIKE :search)
                GROUP BY p.id
                ORDER BY p.name ASC
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, [
            'search' => $searchParam,
            'limit' => $limit
        ]);
    }

    /* ===================== */
    /* ADMIN METHODS         */
    /* ===================== */

    public function count() {
        $sql = "SELECT COUNT(*) as total FROM products";
        $result = $this->db->query($sql)->fetch();
        return (int)$result['total'];
    }

    public function getLowStock($limit = 10) {
        $sql = "
            SELECT * FROM products
            WHERE stock_quantity <= 5 AND is_active = 1
            ORDER BY stock_quantity ASC
            LIMIT ?
        ";
        return $this->db->query($sql, [$limit])->fetchAll();
    }

    public function getForAdmin($search = '', $page = 1, $perPage = 20) {
        $offset = ($page - 1) * $perPage;
        $params = [];

        $sql = "SELECT p.*, c.name AS category_name
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE 1=1";

        if (!empty($search)) {
            $sql .= " AND (p.name LIKE ? OR p.flavor LIKE ?)";
            $searchParam = "%{$search}%";
            $params[] = $searchParam;
            $params[] = $searchParam;
        }

        $sql .= " ORDER BY p.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;

        return $this->db->query($sql, $params)->fetchAll();
    }

    public function countForAdmin($search = '') {
        $params = [];
        $sql = "SELECT COUNT(*) as total FROM products WHERE 1=1";

        if (!empty($search)) {
            $sql .= " AND (name LIKE ? OR flavor LIKE ?)";
            $searchParam = "%{$search}%";
            $params[] = $searchParam;
            $params[] = $searchParam;
        }

        $result = $this->db->query($sql, $params)->fetch();
        return (int)$result['total'];
    }
}
