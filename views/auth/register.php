<?php $title = 'Реєстрація - Chaser Marketplace'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Оберіть тип реєстрації</h4>
                </div>
                <div class="card-body p-5">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-primary h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-user fa-3x text-primary mb-3"></i>
                                    <h5>Покупець</h5>
                                    <p class="text-muted">Реєструйтеся як покупець для здійснення покупок</p>
                                    <ul class="list-unstyled text-start">
                                        <li><i class="fas fa-check text-success me-2"></i>Швидка реєстрація</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Доступ до всіх товарів</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Персональний кабінет</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Історія замовлень</li>
                                    </ul>
                                    <a href="<?= baseUrl('/register/customer') ?>" class="btn btn-primary">
                                        Реєстрація покупця
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-success h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-store fa-3x text-success mb-3"></i>
                                    <h5>Продавець</h5>
                                    <p class="text-muted">Реєструйтеся як продавець для продажу товарів</p>
                                    <ul class="list-unstyled text-start">
                                        <li><i class="fas fa-check text-success me-2"></i>Власний магазин</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Управління товарами</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Аналітика продажів</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Підтримка платформи</li>
                                    </ul>
                                    <a href="<?= baseUrl('/register/seller') ?>" class="btn btn-success">
                                        Реєстрація продавця
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <p>Вже маєте акаунт? <a href="<?= baseUrl('/login') ?>">Увійти</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
