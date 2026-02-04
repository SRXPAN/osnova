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
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
                </ul>
                
                <!-- Search form -->
                <form class="d-flex me-3" method="GET" action="/osnova/search">
                    <input class="form-control me-2" type="search" placeholder="Пошук товарів..." name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                    <button class="btn btn-outline-light" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/osnova/cart">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="badge bg-primary cart-count">0</span>
                        </a>
                    </li>
                    
                    <?php if (auth()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?= htmlspecialchars(trim(auth()['first_name'] . ' ' . auth()['last_name'])) ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/osnova/profile">Профіль</a></li>
                                <li><a class="dropdown-item" href="/osnova/orders">Замовлення</a></li>
                                <?php if (is_admin()): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="/osnova/admin">Адмін панель</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="/osnova/logout" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="dropdown-item">Вийти</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/osnova/login">Увійти</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/osnova/register">Реєстрація</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <?php if ($errors = errors()): ?>
        <div class="container mt-3">
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if ($success = flash('success')): ?>
        <div class="container mt-3">
            <div class="alert alert-success">
                <?= htmlspecialchars($success) ?>
            </div>
        </div>
    <?php endif; ?>

    <main class="main">
