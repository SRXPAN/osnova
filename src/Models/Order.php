<?php
namespace App\Models;

use App\Core\Database;

class Order {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /* =======================
       ТРАНЗАКЦІЇ
       ======================= */

    public function beginTransaction(): void {
        $this->db->beginTransaction();
    }

    public function commit(): void {
        $this->db->commit();
    }

    public function rollback(): void {
        $this->db->rollback();
    }

    /* =======================
       СТВОРЕННЯ ЗАМОВЛЕННЯ
       ======================= */

    public function create($data): int {
        $data['created_at'] = date('Y-m-d H:i:s');
        return (int)$this->db->insert('orders', $data);
    }

    /* =======================
       ПОЗИЦІЇ ЗАМОВЛЕННЯ
       ======================= */

    public function addItem(int $orderId, int $productId, int $quantity, float $price): void {
        $sql = "
            INSERT INTO order_items (order_id, product_id, quantity, price)
            VALUES (:order_id, :product_id, :quantity, :price)
        ";

        $this->db->execute($sql, [
            'order_id'   => $orderId,
            'product_id' => $productId,
            'quantity'   => $quantity,
            'price'      => $price
        ]);
    }

    /* =======================
       ОТРИМАННЯ
       ======================= */

    public function getById($id) {
        return $this->db->fetch(
            "SELECT * FROM orders WHERE id = :id",
            ['id' => $id]
        );
    }

    public function getRecent($limit = 5) {
        return $this->db->fetchAll(
            "SELECT * FROM orders ORDER BY created_at DESC LIMIT :limit",
            ['limit' => $limit]
        );
    }

    public function getByUserId($userId, $limit = null, $page = 1, $perPage = 10) {
        $params = ['user_id' => $userId];
        $sql = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC";

        if ($limit !== null) {
            $sql .= " LIMIT :limit";
            $params['limit'] = (int)$limit;
        } else {
            $offset = ($page - 1) * $perPage;
            $sql .= " LIMIT :offset, :perPage";
            $params['offset'] = $offset;
            $params['perPage'] = $perPage;
        }

        return $this->db->fetchAll($sql, $params);
    }

    /* =======================
       ПІДРАХУНКИ
       ======================= */

    public function count(): int {
        return $this->db->fetch(
            "SELECT COUNT(*) as total FROM orders"
        )['total'] ?? 0;
    }

    public function countByStatus(string $status): int {
        return $this->db->fetch(
            "SELECT COUNT(*) as total FROM orders WHERE status = :status",
            ['status' => $status]
        )['total'] ?? 0;
    }

    public function countForAdmin(string $status = ''): int {
        $where = '';
        $params = [];

        if ($status) {
            $where = "WHERE status = :status";
            $params['status'] = $status;
        }

        return $this->db->fetch(
            "SELECT COUNT(*) as total FROM orders $where",
            $params
        )['total'] ?? 0;
    }

    /* =======================
       АДМІНКА
       ======================= */

    public function getForAdmin(string $status = '', int $page = 1, int $perPage = 20) {
        $offset = ($page - 1) * $perPage;
        $params = [];
        $where = '';

        if ($status) {
            $where = "WHERE status = :status";
            $params['status'] = $status;
        }

        $params['offset'] = $offset;
        $params['perPage'] = $perPage;

        return $this->db->fetchAll(
            "SELECT * FROM orders $where ORDER BY created_at DESC LIMIT :offset, :perPage",
            $params
        );
    }

    /* =======================
       СТАТИСТИКА
       ======================= */

    public function getTotalRevenue(): float {
        return (float)($this->db->fetch(
            "SELECT SUM(total_amount) as revenue FROM orders WHERE status = 'completed'"
        )['revenue'] ?? 0);
    }
}
