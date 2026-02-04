<?php
namespace App\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use \Exception; // FIX: Додано імпорт глобального класу Exception

class CheckoutController {
    private $cartModel;
    private $orderModel;
    private $productModel;
    
    public function __construct() {
        // ... (конструктор залишається незмінним) ...
        $this->cartModel = new Cart();
        $this->orderModel = new Order();
        $this->productModel = new Product();
        
        // Check if user is logged in
        if (!auth()) {
            header('Location: ' . baseUrl('login'));
            exit;
        }
    }
    
    // ... (index method) ...
    
    public function process() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . baseUrl('checkout'));
            exit;
        }
        
        $user = auth();
        $userId = $user['id'];
        
        $cartItems = $this->cartModel->getItems($userId);
        
        if (empty($cartItems)) {
            $_SESSION['error'] = 'Кошик порожній';
            header('Location: ' . baseUrl('cart'));
            exit;
        }
        
        // Validate form data
        $errors = [];
        $deliveryAddress = trim($_POST['delivery_address'] ?? '');
        $paymentMethod = $_POST['payment_method'] ?? '';
        $notes = trim($_POST['notes'] ?? '');
        
        if (empty($deliveryAddress)) {
            $errors['delivery_address'] = 'Адреса доставки обов\'язкова';
        }
        
        if (empty($paymentMethod)) {
            $errors['payment_method'] = 'Спосіб оплати обов\'язковий';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: ' . baseUrl('checkout'));
            exit;
        }
        
        // Create order
        $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $cartTotal = $this->cartModel->getCartTotal($userId);
        
        $orderData = [
            'user_id' => $userId,
            'order_number' => $orderNumber,
            'status' => 'pending',
            'total_amount' => $cartTotal,
            'shipping_address' => $deliveryAddress,
            'payment_method' => $paymentMethod,
            'payment_status' => 'pending',
            'notes' => $notes
        ];
        
        try {
            // Запускаємо транзакцію перед виконанням кількох операцій
            $this->orderModel->beginTransaction();
            
            $orderId = $this->orderModel->create($orderData);
            
            // Add order items and update stock
            foreach ($cartItems as $item) {
                $this->orderModel->addItem($orderId, $item['product_id'], $item['quantity'], $item['price']);
                
                // Update product stock
                $newStock = $item['stock_quantity'] - $item['quantity'];
                $this->productModel->updateStock($item['product_id'], $newStock);
            }
            
            // Clear cart
            $this->cartModel->clearCart($userId);
            
            // Підтверджуємо транзакцію
            $this->orderModel->commit();
            
            $_SESSION['success'] = 'Замовлення успішно оформлено!';
            header('Location: ' . baseUrl("orders/{$orderId}"));
            exit;
            
        // РЯДОК 131 (виправлено завдяки use \Exception;)
        } catch (Exception $e) { 
            // Відкочуємо транзакцію в разі помилки
            $this->orderModel->rollback();
            $_SESSION['error'] = 'Помилка при оформленні замовлення';
            // Логування помилки для налагодження
            error_log("Checkout error for user ID {$userId}: " . $e->getMessage()); 
            header('Location: ' . baseUrl('checkout'));
            exit;
        }
    }
}