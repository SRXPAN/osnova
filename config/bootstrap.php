<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Europe/Kiev');

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../src/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Environment helper function
if (!function_exists('env')) {
    function env($key, $default = null) {
        $value = getenv($key);
        return $value !== false ? $value : $default;
    }
}

// Base URL helper
if (!function_exists('baseUrl')) {
    function baseUrl($path = '') {
        $base = '/osnova';
        return $base . ($path ? '/' . ltrim($path, '/') : '');
    }
}

// Asset helper
if (!function_exists('asset')) {
    function asset($path) {
        return baseUrl('assets/' . ltrim($path, '/'));
    }
}

// CSRF token helper
if (!function_exists('csrf_token')) {
    function csrf_token() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

// CSRF field helper
if (!function_exists('csrf_field')) {
    function csrf_field() {
        return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
    }
}

// Old input helper
if (!function_exists('old')) {
    function old($key, $default = '') {
        return $_SESSION['old'][$key] ?? $default;
    }
}

// Errors helper
if (!function_exists('errors')) {
    function errors($key = null) {
        if ($key) {
            return $_SESSION['errors'][$key] ?? [];
        }
        return $_SESSION['errors'] ?? [];
    }
}

// Flash message helper
if (!function_exists('flash')) {
    function flash($key, $default = null) {
        $value = $_SESSION['flash'][$key] ?? $default;
        unset($_SESSION['flash'][$key]);
        return $value;
    }
}

// Redirect helper
if (!function_exists('redirect')) {
    function redirect($url) {
        header("Location: $url");
        exit;
    }
}

// Auth helper
if (!function_exists('auth')) {
    function auth() {
        return $_SESSION['user'] ?? null;
    }
}

// Admin check helper
if (!function_exists('is_admin')) {
    function is_admin() {
        $user = auth();
        return $user && $user['role'] === 'admin';
    }
}
