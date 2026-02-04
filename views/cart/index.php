<?php $title = 'Shopping Cart - Chaser Marketplace'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1>Корзина покупок</h1>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <?php if (!empty($cartItems)): ?>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Товар</th>
                                                <th>Ціна</th>
                                                <th>Кількість</th>
                                                <th>Підсумок</th>
                                                <th>Дії</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($cartItems as $item): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <?php if ($item['image_url']): ?>
                                                            <img src="<?= htmlspecialchars($item['image_url']) ?>" 
                                                                 alt="<?= htmlspecialchars($item['name']) ?>" 
                                                                 class="me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                        <?php endif; ?>
                                                        <div>
                                                            <h6 class="mb-0"><?= htmlspecialchars($item['name']) ?></h6>
                                                            <small class="text-muted">
                                                                Доступно: <?= $item['stock_quantity'] ?> шт.
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?= number_format($item['price'], 2) ?> ₴</td>
                                                <td>
                                                    <form method="POST" action="<?= baseUrl('cart/update') ?>" class="d-inline">
                                                        <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                                                        <div class="input-group" style="width: 120px;">
                                                            <input type="number" name="quantity" 
                                                                   value="<?= $item['quantity'] ?>" 
                                                                   min="1" max="<?= $item['stock_quantity'] ?>"
                                                                   class="form-control form-control-sm">
                                                            <button type="submit" class="btn btn-outline-secondary btn-sm">
                                                                <i class="fas fa-sync"></i>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </td>
                                                <td>
                                                    <strong><?= number_format($item['subtotal'], 2) ?> ₴</strong>
                                                </td>
                                                <td>
                                                    <form method="POST" action="<?= baseUrl('cart/remove') ?>" class="d-inline">
                                                        <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                onclick="return confirm('Видалити товар з корзини?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <a href="<?= baseUrl('products') ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-arrow-left"></i> Продовжити покупки
                                    </a>
                                    <form method="POST" action="<?= baseUrl('cart/clear') ?>" class="d-inline">
                                        <button type="submit" class="btn btn-outline-danger"
                                                onclick="return confirm('Очистити всю корзину?')">
                                            <i class="fas fa-trash"></i> Очистити корзину
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Підсумок замовлення</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Кількість товарів:</span>
                                    <span><?= $cartCount ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Підсумок:</span>
                                    <strong><?= number_format($cartTotal, 2) ?> ₴</strong>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <h5>Загальна сума:</h5>
                                    <h5 class="text-primary"><?= number_format($cartTotal, 2) ?> ₴</h5>
                                </div>
                                
                                <?php if (auth()): ?>
                                    <a href="<?= baseUrl('checkout') ?>" class="btn btn-primary btn-lg w-100">
                                        Оформити замовлення
                                    </a>
                                <?php else: ?>
                                    <div class="text-center">
                                        <p class="mb-3">Для оформлення замовлення увійдіть в акаунт</p>
                                        <a href="<?= baseUrl('login') ?>" class="btn btn-primary btn-lg w-100">
                                            Увійти
                                        </a>
                                        <p class="mt-2 mb-0">
                                            <small>
                                                Немає акаунта? 
                                                <a href="<?= baseUrl('register') ?>">Зареєструватися</a>
                                            </small>
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <h3>Корзина порожня</h3>
                    <p class="text-muted mb-4">Додайте товари до корзини, щоб продовжити покупки</p>
                    <a href="<?= baseUrl('products') ?>" class="btn btn-primary">
                        <i class="fas fa-shopping-bag"></i> Перейти до покупок
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
