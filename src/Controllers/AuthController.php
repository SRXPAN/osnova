<?php
namespace App\Controllers;

use App\Models\User;

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function loginForm() {
        if (auth()) {
            redirect(baseUrl('/'));
            return;
        }
        require_once __DIR__ . '/../../views/auth/login.php';
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(baseUrl('/login'));
            return;
        }
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Email та пароль обов\'язкові';
            redirect(baseUrl('/login'));
            return;
        }
        
        $user = $this->userModel->findByEmail($email);
        
        if ($user && $this->userModel->verifyPassword($password, $user['password'])) {
            // Remove password from session data
            unset($user['password']);
            $_SESSION['user'] = $user;
            
            $_SESSION['success'] = 'Ласкаво просимо, ' . $user['first_name'] . '!';
            
            // Redirect admin to admin panel, others to home
            if ($user['role'] === 'admin') {
                redirect(baseUrl('/admin/dashboard'));
            } else {
                redirect(baseUrl('/'));
            }
        } else {
            $_SESSION['error'] = 'Невірний email або пароль';
            redirect(baseUrl('/login'));
        }
    }
    
    public function registerForm() {
        if (auth()) {
            redirect(baseUrl('/'));
            return;
        }
        require_once __DIR__ . '/../../views/auth/register.php';
    }
    
    public function customerRegisterForm() {
        if (auth()) {
            redirect(baseUrl('/'));
            return;
        }
        require_once __DIR__ . '/../../views/auth/register-customer.php';
    }
    
    public function sellerRegisterForm() {
        if (auth()) {
            redirect(baseUrl('/'));
            return;
        }
        require_once __DIR__ . '/../../views/auth/register-seller.php';
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(baseUrl('/register'));
            return;
        }
        
        $role = $_POST['role'] ?? 'customer';
        
        if ($role === 'seller') {
            return $this->registerSeller();
        } else {
            return $this->registerCustomer();
        }
    }
    
    private function registerCustomer() {
        $first_name = $_POST['first_name'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        
        $errors = $this->validateBasicRegistration($first_name, $last_name, $email, $password, $confirmPassword);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            redirect(baseUrl('/register'));
            return;
        }
        
        $userId = $this->userModel->create([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'password' => $password,
            'phone' => $phone,
            'address' => $address,
            'role' => 'customer'
        ]);
        
        $user = $this->userModel->findById($userId);
        unset($user['password']);
        $_SESSION['user'] = $user;
        
        $_SESSION['success'] = 'Реєстрація успішна! Ласкаво просимо, ' . $user['first_name'] . '!';
        redirect(baseUrl('/'));
    }
    
    private function registerSeller() {
        $first_name = $_POST['first_name'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        $company_name = $_POST['company_name'] ?? '';
        $tax_number = $_POST['tax_number'] ?? '';
        $business_type = $_POST['business_type'] ?? '';
        $description = $_POST['description'] ?? '';
        
        $errors = $this->validateBasicRegistration($first_name, $last_name, $email, $password, $confirmPassword);
        
        // Additional validation for sellers
        if (empty($company_name)) $errors[] = 'Назва компанії обов\'язкова';
        if (empty($tax_number)) $errors[] = 'Податковий номер обов\'язковий';
        if (empty($business_type)) $errors[] = 'Тип бізнесу обов\'язковий';
        if (empty($description)) $errors[] = 'Опис діяльності обов\'язковий';
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            redirect(baseUrl('/register/seller'));
            return;
        }
        
        $userId = $this->userModel->create([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'password' => $password,
            'phone' => $phone,
            'address' => $address,
            'role' => 'seller',
            'company_name' => $company_name,
            'tax_number' => $tax_number,
            'business_type' => $business_type,
            'description' => $description,
            'seller_status' => 'pending'
        ]);
        
        $_SESSION['success'] = 'Заявка на реєстрацію продавця відправлена! Очікуйте підтвердження від адміністрації.';
        redirect(baseUrl('/register/seller?success=1'));
    }
    
    private function validateBasicRegistration($first_name, $last_name, $email, $password, $confirmPassword) {
        $errors = [];
        
        if (empty($first_name)) $errors[] = 'Ім\'я обов\'язкове';
        if (empty($last_name)) $errors[] = 'Прізвище обов\'язкове';
        if (empty($email)) $errors[] = 'Email обов\'язковий';
        if (empty($password)) $errors[] = 'Пароль обов\'язковий';
        if (strlen($password) < 6) $errors[] = 'Пароль повинен містити мінімум 6 символів';
        if ($password !== $confirmPassword) $errors[] = 'Паролі не співпадають';
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Невірний формат email';
        }
        
        if ($this->userModel->findByEmail($email)) {
            $errors[] = 'Користувач з таким email вже існує';
        }
        
        return $errors;
    }
    
    public function logout() {
        if (auth()) {
            $_SESSION['success'] = 'Ви успішно вийшли з акаунта';
        }
        session_destroy();
        redirect(baseUrl('/'));
    }
}
