<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Chaser Marketplace' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/osnova/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/osnova/">
                <i class="fas fa-store"></i> Chaser Marketplace
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/osnova/">Головна</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/osnova/products">Товари</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/osnova/about">Про нас</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/osnova/contact">Контакти</a>
                    </li>
                </ul>
                
                <!-- Search form -->
                <form class="d-flex me-3" method="GET" action="/osnova/search">
                    <div class="input-group">
                        <input class="form-control" type="search" placeholder="Пошук товарів..." name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                        <button class="btn btn-outline-light" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="/osnova/cart">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="badge bg-primary cart-count position-absolute top-0 start-100 translate-middle">0</span>
                        </a>
                    </li>
                    
                    <?php if (auth()): ?>
                        <?php $user = auth(); ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> 
                                <?= htmlspecialchars($user['first_name'] ?? $user['name'] ?? 'Профіль') ?>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if ($user['role'] === 'admin'): ?>
                                    <li><a class="dropdown-item" href="/osnova/admin/dashboard">
                                        <i class="fas fa-tachometer-alt me-2"></i>Адмін панель
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                
                                <li><a class="dropdown-item" href="/osnova/user/profile">
                                    <i class="fas fa-user me-2"></i>Мій профіль
                                </a></li>
                                <li><a class="dropdown-item" href="/osnova/user/settings">
                                    <i class="fas fa-cog me-2"></i>Налаштування
                                </a></li>
                                <li><a class="dropdown-item" href="/osnova/user/orders">
                                    <i class="fas fa-shopping-bag me-2"></i>Мої замовлення
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="/osnova/user/logout" 
                                       onclick="return confirm('Ви впевнені, що хочете вийти?')">
                                        <i class="fas fa-sign-out-alt me-2"></i>Вийти з акаунта
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/osnova/login">
                                <i class="fas fa-sign-in-alt me-1"></i>Увійти
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/osnova/register">
                                <i class="fas fa-user-plus me-1"></i>Реєстрація
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <main class="main">
