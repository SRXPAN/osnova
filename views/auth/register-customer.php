<?php $title = 'Реєстрація покупця - Chaser Marketplace'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Реєстрація покупця</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty(errors())): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (errors() as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?= baseUrl('/register') ?>" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="role" value="customer">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">Ім'я *</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       value="<?= old('first_name') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Прізвище *</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="<?= old('last_name') ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= old('email') ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Телефон</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?= old('phone') ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Пароль *</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Підтвердження пароля *</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Адреса</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?= old('address') ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Зареєструватися</button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <p>Хочете стати продавцем? <a href="<?= baseUrl('/register/seller') ?>">Реєстрація продавця</a></p>
                        <p>Вже маєте акаунт? <a href="<?= baseUrl('/login') ?>">Увійти</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
