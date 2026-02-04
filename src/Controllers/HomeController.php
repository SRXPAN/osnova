<?php
namespace App\Controllers;

use App\Models\Product;
use App\Models\Category;

class HomeController
{
    private $productModel;
    private $categoryModel;

    public function __construct()
    {
        $this->productModel  = new Product();
        $this->categoryModel = new Category();
    }

    public function index()
    {
        $featuredProducts = $this->productModel->getFeaturedProducts(8);
        $categories       = $this->categoryModel->getActive();

        include __DIR__ . '/../../views/home/index.php';
    }

    public function show($id)
    {
        $product = $this->productModel->getById($id);
        if (!$product) {
            $this->error404();
            return;
        }

        $relatedProducts = $this->productModel->getRelated($product['category_id'], $id, 4);
        $reviews         = $this->productModel->getReviews($id);
        $images          = $this->productModel->getImages($id);

        include __DIR__ . '/../../views/products/show.php';
    }

    public function search()
    {
        $query = isset($_GET['q']) ? trim($_GET['q']) : '';
        $products = $query ? $this->productModel->search($query) : [];
        
        include __DIR__ . '/../../views/home/search.php';
    }

    public function about()
    {
        include __DIR__ . '/../../views/home/about.php';
    }

    public function contact()
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle contact form submission
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $subject = $_POST['subject'] ?? '';
            $message = $_POST['message'] ?? '';
            
            // Store old input for form repopulation
            $_SESSION['old'] = [
                'name' => $name,
                'email' => $email,
                'subject' => $subject,
                'message' => $message
            ];
            
            // Simple validation
            if (empty($name) || empty($email) || empty($message)) {
                $_SESSION['error'] = 'Всі обов\'язкові поля повинні бути заповнені';
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = 'Невірний формат email';
            } else {
                // Here you would typically send email or save to database
                // For now, we'll just simulate success
                $_SESSION['success'] = 'Ваше повідомлення відправлено успішно! Ми зв\'яжемося з вами найближчим часом.';
                
                // Clear old input after successful submission
                unset($_SESSION['old']);
                
                // Redirect to prevent form resubmission
                header('Location: ' . baseUrl('contact?sent=1'));
                exit;
            }
        }
        
        include __DIR__ . '/../../views/home/contact.php';
    }

    public function faq()
    {
        include __DIR__ . '/../../views/home/faq.php';
    }

    public function privacy()
    {
        include __DIR__ . '/../../views/home/privacy.php';
    }

    public function terms()
    {
        include __DIR__ . '/../../views/home/terms.php';
    }

    public function error404()
    {
        http_response_code(404);
        $title = 'Page Not Found';
        $message = 'The requested page could not be found.';
        include __DIR__ . '/../../views/errors/404.php';
    }
}