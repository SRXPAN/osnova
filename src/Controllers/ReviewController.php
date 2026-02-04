<?php
namespace App\Controllers;

use App\Models\Review;

class ReviewController {
    private $reviewModel;
    
    public function __construct() {
        $this->reviewModel = new Review();
    }
    
    public function store($productId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }
        
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Увійдіть, щоб залишити відгук';
            header('Location: /osnova/login');
            exit;
        }
        
        $rating = (int)($_POST['rating'] ?? 0);
        $comment = trim($_POST['comment'] ?? '');
        
        // Validation
        if ($rating < 1 || $rating > 5) {
            $_SESSION['error'] = 'Оберіть оцінку від 1 до 5 зірок';
            header('Location: /osnova/products/' . $productId);
            exit;
        }
        
        if (empty($comment)) {
            $_SESSION['error'] = 'Коментар обов\'язковий';
            header('Location: /osnova/products/' . $productId);
            exit;
        }
        
        $data = [
            'product_id' => $productId,
            'user_id' => $_SESSION['user_id'],
            'rating' => $rating,
            'comment' => $comment,
            'is_approved' => 0 // Reviews need approval
        ];
        
        if ($this->reviewModel->create($data)) {
            $_SESSION['success'] = 'Ваш відгук відправлено на модерацію. Дякуємо!';
        } else {
            $_SESSION['error'] = 'Помилка при збереженні відгуку';
        }
        
        header('Location: /osnova/products/' . $productId);
        exit;
    }
}
