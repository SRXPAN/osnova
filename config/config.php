<?php

return [
    'app' => [
        'name' => 'Chaser Marketplace',
        'url' => 'http://localhost/osnova',
        'base_path' => '/osnova',
        'debug' => true,
    ],
    
    'database' => [
        'host' => 'localhost',
        'dbname' => 'chaser_marketplace',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
    ],
    
    'session' => [
        'name' => 'chaser_session',
        'lifetime' => 7200, // 2 hours
        'path' => '/osnova',
        'secure' => false,
        'httponly' => true,
    ],
    
    'upload' => [
        'max_size' => 5242880, // 5MB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'path' => __DIR__ . '/../public/uploads/',
        'url' => '/osnova/public/uploads/',
    ],
];
