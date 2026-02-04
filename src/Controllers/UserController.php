<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\Order;

class UserController {
    private $userModel;
    private $orderModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->orderModel = new Order();
        
        // Check if user is logged in
        if (!auth()) {
            header('Location: ' . baseUrl('login'));
            exit;
        }
    }

    public function profile() {
        $user = auth();
        $recentOrders = $this->orderModel->getByUserId($user['id'], 5);
        
        include __DIR__ . '/../../views/user/profile.php';
    }
    
    public function settings() {
        $user = auth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            switch ($action) {
                case 'update_profile':
                    $this->updateProfile();
                    break;
                case 'change_password':
                    $this->changePassword();
                    break;
            }
        }
        
        include __DIR__ . '/../../views/user/settings.php';
    }
    
    public function orders() {
        $user = auth();
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        
        $orders = $this->orderModel->getByUserId($user['id'], null, $page, $perPage);
        $totalOrders = $this->orderModel->countByUserId($user['id']);
        $totalPages = ceil($totalOrders / $perPage);
        
        include __DIR__ . '/../../views/user/orders.php';
    }
    
    public function logout() {
        session_destroy();
        header('Location: ' . baseUrl(''));
        exit;
    }
    
    private function updateProfile() {
        $user = auth();
        $data = [
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'address' => $_POST['address'] ?? ''
        ];
        
        $errors = [];
        
        // Validation
        if (empty($data['first_name'])) {
            $errors['first_name'] = 'Ім\'я обов\'язкове';
        }
        
        if (empty($data['last_name'])) {
            $errors['last_name'] = 'Прізвище обов\'язкове';
        }
        
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Невірний формат email';
        }
        
        // Check if email is unique (excluding current user)
        if ($data['email'] !== $user['email'] && $this->userModel->emailExists($data['email'])) {
            $errors['email'] = 'Email вже використовується';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            return;
        }
        
        if ($this->userModel->update($user['id'], $data)) {
            // Update session data
            $_SESSION['user'] = array_merge($_SESSION['user'], $data);
            $_SESSION['success'] = 'Профіль успішно оновлено';
        } else {
            $_SESSION['error'] = 'Помилка при оновленні профілю';
        }
    }
    
    private function changePassword() {
        $user = auth();
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        $errors = [];
        
        // Validation
        if (empty($currentPassword)) {
            $errors['current_password'] = 'Поточний пароль обов\'язковий';
        } elseif (!password_verify($currentPassword, $user['password'])) {
            $errors['current_password'] = 'Невірний поточний пароль';
        }
        
        if (empty($newPassword) || strlen($newPassword) < 6) {
            $errors['new_password'] = 'Новий пароль повинен містити мінімум 6 символів';
        }
        
        if ($newPassword !== $confirmPassword) {
            $errors['confirm_password'] = 'Паролі не співпадають';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            return;
        }
        
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        if ($this->userModel->update($user['id'], ['password' => $hashedPassword])) {
            $_SESSION['success'] = 'Пароль успішно змінено';
        } else {
            $_SESSION['error'] = 'Помилка при зміні паролю';
        }
    }
}
