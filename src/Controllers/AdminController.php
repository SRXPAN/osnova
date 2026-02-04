<?php
namespace App\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;

class AdminController {
    private $productModel;
    private $categoryModel;
    private $orderModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->orderModel = new Order();
        
        // Check if user is admin
        if (!is_admin()) {
            header('Location: ' . baseUrl('login'));
            exit;
        }
    }
    
    public function dashboard() {
        $stats = [
            'total_products' => $this->productModel->count(),
            'total_orders' => $this->orderModel->count(),
            'pending_orders' => $this->orderModel->countByStatus('pending'),
            'total_revenue' => $this->orderModel->getTotalRevenue()
        ];
        
        $recentOrders = $this->orderModel->getRecent(5);
        $lowStockProducts = $this->productModel->getLowStock(10);
        
        include __DIR__ . '/../../views/admin/dashboard.php';
    }
    
    public function products() {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 20;
        $search = $_GET['search'] ?? '';
        
        $products = $this->productModel->getForAdmin($search, $page, $perPage);
        $totalProducts = $this->productModel->countForAdmin($search);
        $totalPages = ceil($totalProducts / $perPage);
        
        include __DIR__ . '/../../views/admin/products/index.php';
    }
    
    public function createProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'slug' => $this->generateSlug($_POST['name'] ?? ''),
                'description' => $_POST['description'] ?? '',
                'flavor' => $_POST['flavor'] ?? '',
                'volume' => (int)($_POST['volume'] ?? 0),
                'nicotine_content' => (float)($_POST['nicotine_content'] ?? 0),
                'price' => (float)($_POST['price'] ?? 0),
                'stock_quantity' => (int)($_POST['stock_quantity'] ?? 0),
                'category_id' => (int)($_POST['category_id'] ?? 0),
                'image_url' => $_POST['image_url'] ?? '',
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            
            if ($this->validateProduct($data)) {
                if ($this->productModel->create($data)) {
                    $_SESSION['success'] = 'Товар успішно створено';
                    header('Location: ' . baseUrl('admin/products'));
                    exit;
                } else {
                    $_SESSION['error'] = 'Помилка при створенні товару';
                }
            }
        }
        
        $categories = $this->categoryModel->getAll();
        include __DIR__ . '/../../views/admin/products/create.php';
    }
    
    public function editProduct($id) {
        $product = $this->productModel->getById($id);
        
        if (!$product) {
            $_SESSION['error'] = 'Товар не знайдено';
            header('Location: ' . baseUrl('admin/products'));
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'slug' => $this->generateSlug($_POST['name'] ?? ''),
                'description' => $_POST['description'] ?? '',
                'flavor' => $_POST['flavor'] ?? '',
                'volume' => (int)($_POST['volume'] ?? 0),
                'nicotine_content' => (float)($_POST['nicotine_content'] ?? 0),
                'price' => (float)($_POST['price'] ?? 0),
                'stock_quantity' => (int)($_POST['stock_quantity'] ?? 0),
                'category_id' => (int)($_POST['category_id'] ?? 0),
                'image_url' => $_POST['image_url'] ?? '',
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            
            if ($this->validateProduct($data)) {
                if ($this->productModel->update($id, $data)) {
                    $_SESSION['success'] = 'Товар успішно оновлено';
                    header('Location: ' . baseUrl('admin/products'));
                    exit;
                } else {
                    $_SESSION['error'] = 'Помилка при оновленні товару';
                }
            }
        }
        
        $categories = $this->categoryModel->getAll();
        include __DIR__ . '/../../views/admin/products/edit.php';
    }
    
    public function deleteProduct($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->productModel->delete($id)) {
                $_SESSION['success'] = 'Товар успішно видалено';
            } else {
                $_SESSION['error'] = 'Помилка при видаленні товару';
            }
        }
        
        header('Location: ' . baseUrl('admin/products'));
        exit;
    }
    
    public function orders() {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 20;
        $status = $_GET['status'] ?? '';
        
        $orders = $this->orderModel->getForAdmin($status, $page, $perPage);
        $totalOrders = $this->orderModel->countForAdmin($status);
        $totalPages = ceil($totalOrders / $perPage);
        
        include __DIR__ . '/../../views/admin/orders/index.php';
    }
    
    public function logout() {
        session_destroy();
        header('Location: ' . baseUrl(''));
        exit;
    }
    
    private function generateSlug($name) {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }
    
    private function validateProduct($data) {
        $errors = [];
        
        if (empty($data['name'])) {
            $errors['name'] = 'Назва товару обов\'язкова';
        }
        
        if (empty($data['price']) || $data['price'] <= 0) {
            $errors['price'] = 'Ціна повинна бути більше 0';
        }
        
        if (empty($data['category_id'])) {
            $errors['category_id'] = 'Категорія обов\'язкова';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            return false;
        }
        
        return true;
    }
}
