<?php
// Load Composer autoloader if available
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

require_once __DIR__ . '/config/bootstrap.php';

use App\Core\Router;
use App\Core\Database;

// Define base path
$basePath = '/osnova';

// Initialize database connection
try {
    $db = Database::getInstance();
} catch (Exception $e) {
    error_log("Database connection failed: " . $e->getMessage());
    http_response_code(500);
    include __DIR__ . '/views/errors/500.php';
    exit;
}

// Initialize router
$router = new Router();

// Define routes
$router->addRoute('GET', '/', 'HomeController@index');
$router->addRoute('GET', '/search', 'HomeController@search');
$router->addRoute('GET', '/about', 'HomeController@about');
$router->addRoute('GET', '/contact', 'HomeController@contact');
$router->addRoute('POST', '/contact', 'HomeController@contact');
$router->addRoute('GET', '/faq', 'HomeController@faq');
$router->addRoute('GET', '/privacy', 'HomeController@privacy');
$router->addRoute('GET', '/terms', 'HomeController@terms');

// Product routes
$router->addRoute('GET', '/products', 'ProductController@index');
$router->addRoute('GET', '/products/{id}', 'ProductController@show');
$router->addRoute('POST', '/products/{id}/reviews', 'ReviewController@store');

// Auth routes
$router->addRoute('GET', '/login', 'AuthController@loginForm');
$router->addRoute('POST', '/login', 'AuthController@login');
$router->addRoute('GET', '/register', 'AuthController@registerForm');
$router->addRoute('GET', '/register/customer', 'AuthController@customerRegisterForm');
$router->addRoute('GET', '/register/seller', 'AuthController@sellerRegisterForm');
$router->addRoute('POST', '/register', 'AuthController@register');
$router->addRoute('GET', '/logout', 'AuthController@logout');
$router->addRoute('POST', '/logout', 'AuthController@logout');

// Cart routes
$router->addRoute('GET', '/cart', 'CartController@index');
$router->addRoute('POST', '/cart/add', 'CartController@add');
$router->addRoute('POST', '/cart/update', 'CartController@update');
$router->addRoute('POST', '/cart/remove', 'CartController@remove');
$router->addRoute('POST', '/cart/clear', 'CartController@clear');
$router->addRoute('GET', '/cart/data', 'CartController@getCartData');

// Checkout routes
$router->addRoute('GET', '/checkout', 'CheckoutController@index');
$router->addRoute('POST', '/checkout', 'CheckoutController@process');

// Order routes
$router->addRoute('GET', '/orders/{id}', 'OrderController@show');

// User profile
$router->addRoute('GET', '/profile', 'UserController@profile');
$router->addRoute('PUT', '/profile', 'UserController@updateProfile');

// Admin routes
$router->addRoute('GET', '/admin', 'AdminController@dashboard');
$router->addRoute('GET', '/admin/dashboard', 'AdminController@dashboard');
$router->addRoute('GET', '/admin/products', 'AdminController@products');
$router->addRoute('GET', '/admin/products/create', 'AdminController@createProduct');
$router->addRoute('POST', '/admin/products/create', 'AdminController@createProduct');
$router->addRoute('GET', '/admin/products/{id}/edit', 'AdminController@editProduct');
$router->addRoute('POST', '/admin/products/{id}/edit', 'AdminController@editProduct');
$router->addRoute('POST', '/admin/products/{id}/delete', 'AdminController@deleteProduct');
$router->addRoute('GET', '/admin/orders', 'AdminController@orders');
$router->addRoute('GET', '/admin/logout', 'AdminController@logout');

// User profile routes
$router->addRoute('GET', '/user/profile', 'UserController@profile');
$router->addRoute('GET', '/user/settings', 'UserController@settings');
$router->addRoute('POST', '/user/settings', 'UserController@settings');
$router->addRoute('GET', '/user/orders', 'UserController@orders');
$router->addRoute('GET', '/user/logout', 'UserController@logout');

// Handle the request
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Remove base path from request URI
if (strpos($requestUri, $basePath) === 0) {
    $requestUri = substr($requestUri, strlen($basePath));
}

// Ensure we have at least a forward slash
if (empty($requestUri) || $requestUri === '') {
    $requestUri = '/';
}

// Debug logging
error_log("Request: {$requestMethod} {$requestUri}");

try {
    $router->dispatch($requestMethod, $requestUri);
} catch (Exception $e) {
    error_log("Router error: " . $e->getMessage());
    http_response_code(404);
    $title = 'Page Not Found';
    $message = 'The requested page could not be found.';
    include __DIR__ . '/views/errors/404.php';
}