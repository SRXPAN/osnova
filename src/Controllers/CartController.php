<?php
namespace App\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Exception; // ✅ Додано імпорт Exception

class CartController {
    private $cartModel;
    private $productModel;
    
    public function __construct() {
        $this->cartModel = new Cart();
        $this->productModel = new Product();
    }
    
    public function index() {
        $userId = auth() ? auth()['id'] : null;
        $sessionId = $userId ? null : session_id();
        
        $cartItems = $this->cartModel->getItems($userId, $sessionId);
        $cartTotal = $this->cartModel->getCartTotal($userId, $sessionId);
        $cartCount = $this->cartModel->getCartItemCount($userId, $sessionId);
        
        include __DIR__ . '/../../views/cart/index.php';
    }
    
    public function add() {
         if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . baseUrl('cart'));
            exit;
        }
        
        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity = max(1, (int)($_POST['quantity'] ?? 1));
        
        if (!$productId) {
            $_SESSION['error'] = 'Невірний товар';
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? baseUrl('products')));
            exit;
        }
        
        // Check if product exists and is active
        $product = $this->productModel->getById($productId);
        if (!$product || !$product['is_active']) {
            $_SESSION['error'] = 'Товар недоступний';
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? baseUrl('products')));
            exit;
        }
        
        // Check stock
        if ($product['stock_quantity'] < $quantity) {
            $_SESSION['error'] = 'Недостатньо товару на складі';
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? baseUrl('products')));
            exit;
        }
        
        $userId = auth() ? auth()['id'] : null;
        $sessionId = $userId ? null : session_id();
        
        try {
            if ($this->cartModel->addItem($userId, $sessionId, $productId, $quantity)) {
                $_SESSION['success'] = 'Товар додано до корзини';
            } else {
                $_SESSION['error'] = 'Помилка при додаванні товару до корзини';
            }
        } catch (Exception $e) { // ✅ Тепер працює
            error_log("Cart add error: " . $e->getMessage());
            $_SESSION['error'] = 'Помилка при додаванні товару до корзини';
        }
        
        // Return JSON response for AJAX requests
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => isset($_SESSION['success']),
                'message' => $_SESSION['success'] ?? $_SESSION['error'] ?? 'Unknown error',
                'cart_count' => $this->cartModel->getCartItemCount($userId, $sessionId)
            ]);
            exit;
        }
        
        header('Location: ' . baseUrl('cart'));
        exit;
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . baseUrl('cart'));
            exit;
        }
        
        $cartId = (int)($_POST['cart_id'] ?? 0);
        $quantity = max(0, (int)($_POST['quantity'] ?? 0));
        
        if (!$cartId) {
            $_SESSION['error'] = 'Невірний елемент корзини';
            header('Location: ' . baseUrl('cart'));
            exit;
        }
        
        if ($this->cartModel->updateQuantity($cartId, $quantity)) {
            $_SESSION['success'] = $quantity > 0 ? 'Кількість оновлено' : 'Товар видалено з корзини';
        } else {
            $_SESSION['error'] = 'Помилка при оновленні корзини';
        }
        
        header('Location: ' . baseUrl('cart'));
        exit;
    }
    
    public function remove() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . baseUrl('cart'));
            exit;
        }
        
        $cartId = (int)($_POST['cart_id'] ?? 0);
        
        if (!$cartId) {
            $_SESSION['error'] = 'Невірний елемент корзини';
            header('Location: ' . baseUrl('cart'));
            exit;
        }
        
        if ($this->cartModel->removeItem($cartId)) {
            $_SESSION['success'] = 'Товар видалено з корзини';
        } else {
            $_SESSION['error'] = 'Помилка при видаленні товару';
        }
        
        header('Location: ' . baseUrl('cart'));
        exit;
    }
    
    public function clear() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . baseUrl('cart'));
            exit;
        }
        
        $userId = auth() ? auth()['id'] : null;
        $sessionId = $userId ? null : session_id();
        
        if ($this->cartModel->clearCart($userId, $sessionId)) {
            $_SESSION['success'] = 'Корзина очищена';
        } else {
            $_SESSION['error'] = 'Помилка при очищенні корзини';
        }
        
        header('Location: ' . baseUrl('cart'));
        exit;
    }
    
    public function getCartData() {
        $userId = auth() ? auth()['id'] : null;
        $sessionId = $userId ? null : session_id();
        
        header('Content-Type: application/json');
        echo json_encode([
            'count' => $this->cartModel->getCartItemCount($userId, $sessionId),
            'total' => $this->cartModel->getCartTotal($userId, $sessionId)
        ]);
        exit;
    }
}