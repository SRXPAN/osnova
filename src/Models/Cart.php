<?php
namespace App\Models;

use App\Core\Database;
// Add the global Exception class to handle database errors correctly
use \Exception; 

class Cart {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function addItem($userId, $sessionId, $productId, $quantity = 1) {
        // Ensure quantity is positive
        if ($quantity <= 0) {
            return false;
        }

        // Check if item already exists in cart
        $existing = $this->getItem($userId, $sessionId, $productId);
        
        if ($existing) {
            // Update quantity
            return $this->updateQuantity($existing['id'], $existing['quantity'] + $quantity);
        } else {
            // Add new item
            $data = [
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId,
                'quantity' => $quantity
            ];
            
            // Remove null values to avoid database issues
            $data = array_filter($data, function($value) {
                return $value !== null;
            });
            
            try {
                $result = $this->db->insert('cart', $data);
                return $result !== false;
            // FIX: Qualified Exception used here.
            } catch (Exception $e) { 
                error_log("Cart insert error: " . $e->getMessage());
                return false;
            }
        }
    }
    
    public function getItems($userId = null, $sessionId = null) {
        $sql = "SELECT c.*, p.name, p.price, p.image_url, p.stock_quantity,
                       (c.quantity * p.price) as subtotal
                FROM cart c 
                JOIN products p ON c.product_id = p.id 
                WHERE p.is_active = 1";
        
        $params = [];
        
        if ($userId) {
            $sql .= " AND c.user_id = :user_id";
            $params['user_id'] = $userId;
        } elseif ($sessionId) {
            $sql .= " AND c.session_id = :session_id AND c.user_id IS NULL";
            $params['session_id'] = $sessionId;
        }
        
        $sql .= " ORDER BY c.created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getItem($userId, $sessionId, $productId) {
        $sql = "SELECT * FROM cart WHERE product_id = :product_id";
        $params = ['product_id' => $productId];
        
        if ($userId) {
            $sql .= " AND user_id = :user_id";
            $params['user_id'] = $userId;
        } else {
            $sql .= " AND session_id = :session_id AND user_id IS NULL";
            $params['session_id'] = $sessionId;
        }
        
        return $this->db->fetch($sql, $params);
    }
    
    public function updateQuantity($cartId, $quantity) {
        if ($quantity <= 0) {
            return $this->removeItem($cartId);
        }
        
        return $this->db->update('cart', 
            ['quantity' => $quantity], 
            'id = :id', 
            ['id' => $cartId]
        );
    }
    
    public function removeItem($cartId) {
        return $this->db->delete('cart', 'id = :id', ['id' => $cartId]);
    }
    
    public function clearCart($userId = null, $sessionId = null) {
        if ($userId) {
            return $this->db->delete('cart', 'user_id = :user_id', ['user_id' => $userId]);
        } elseif ($sessionId) {
            return $this->db->delete('cart', 'session_id = :session_id AND user_id IS NULL', ['session_id' => $sessionId]);
        }
        return false;
    }
    
    public function getCartTotal($userId = null, $sessionId = null) {
        $items = $this->getItems($userId, $sessionId);
        $total = 0;
        
        foreach ($items as $item) {
            $total += $item['subtotal'];
        }
        
        return $total;
    }
    
    public function getCartItemCount($userId = null, $sessionId = null) {
        $sql = "SELECT SUM(quantity) as total_items FROM cart WHERE 1=1";
        $params = [];
        
        if ($userId) {
            $sql .= " AND user_id = :user_id";
            $params['user_id'] = $userId;
        } elseif ($sessionId) {
            $sql .= " AND session_id = :session_id AND user_id IS NULL";
            $params['session_id'] = $sessionId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['total_items'] ?? 0;
    }
    
    /**
     * FIX: Merges the session cart items into the user's permanent cart.
     * This correctly handles merging product quantities if the user already has items.
     */
    public function transferSessionCartToUser($sessionId, $userId) {
        // 1. Get all items in the guest session cart
        $sessionItems = $this->getItems(null, $sessionId);
        
        $success = true;
        
        // 2. Iterate and add each item to the user's cart (using addItem handles merging/updating)
        foreach ($sessionItems as $item) {
            // addItem uses userId and checks if the item already exists in the user's cart
            if (!$this->addItem($userId, null, $item['product_id'], $item['quantity'])) {
                $success = false;
                // Continue to try adding remaining items or break on first failure
            }
        }
        
        // 3. Clear the original session cart regardless of merge success
        if (!$this->clearCart(null, $sessionId)) {
            $success = false;
        }

        return $success;
    }
}