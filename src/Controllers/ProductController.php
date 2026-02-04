<?php
namespace App\Controllers;

use App\Models\Product;
use App\Models\Category;

class ProductController {
    private $productModel;
    private $categoryModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }
    
    public function index() {
        // Get filters from query string
        $categoryId = $_GET['category'] ?? null;
        $search = $_GET['search'] ?? null;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 12;
        
        // Get products with filters
        $products = $this->productModel->getFiltered($categoryId, $search, $page, $perPage);
        $categories = $this->categoryModel->getActive();
        
        // Get total count for pagination
        $totalProducts = $this->productModel->getFilteredCount($categoryId, $search);
        $totalPages = ceil($totalProducts / $perPage);
        
        include __DIR__ . '/../../views/products/index.php';
    }

    public function show($id) {
        $product = $this->productModel->getById($id);
        
        if (!$product) {
            $this->show404();
            return;
        }
        
        // Get related products and reviews
        $relatedProducts = $this->productModel->getRelated($product['category_id'], $id, 4);
        $reviews = $this->productModel->getReviews($id);
        $images = $this->productModel->getImages($id);
        
        include __DIR__ . '/../../views/products/show.php';
    }
    
    public function create() {
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
                'image_url' => $_POST['image_url'] ?? ''
            ];
            
            if ($this->productModel->create($data)) {
                header('Location: /products?success=created');
                exit;
            } else {
                $error = 'Failed to create product';
            }
        }
        
        $categories = $this->categoryModel->getActive();
        include __DIR__ . '/../../views/products/create.php';
    }
    
    public function edit($id) {
        $product = $this->productModel->getById($id);
        
        if (!$product) {
            $this->show404();
            return;
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
                'image_url' => $_POST['image_url'] ?? ''
            ];
            
            if ($this->productModel->update($id, $data)) {
                header('Location: /products?success=updated');
                exit;
            } else {
                $error = 'Failed to update product';
            }
        }
        
        $categories = $this->categoryModel->getActive();
        include __DIR__ . '/../../views/products/edit.php';
    }
    
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->productModel->delete($id)) {
                header('Location: /products?success=deleted');
            } else {
                header('Location: /products?error=delete_failed');
            }
            exit;
        }
        
        $this->show404();
    }
    
    private function generateSlug($name) {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }
    
    private function show404() {
        http_response_code(404);
        $title = 'Page Not Found';
        $message = 'The requested product could not be found.';
        include __DIR__ . '/../../views/errors/404.php';
    }
}
