<?php $title = 'Реєстрація продавця - Chaser Marketplace'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Реєстрація продавця</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            Заявка на реєстрацію відправлена! Очікуйте підтвердження від адміністрації.
                        </div>
                    <?php endif; ?>
                    
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
                        <input type="hidden" name="role" value="seller">
                        
                        <h5 class="text-success mb-3">Особисті дані</h5>
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
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= old('email') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Телефон *</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?= old('phone') ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Пароль *</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">Підтвердження пароля *</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        <h5 class="text-success mb-3">Бізнес інформація</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="company_name" class="form-label">Назва компанії *</label>
                                <input type="text" class="form-control" id="company_name" name="company_name" 
                                       value="<?= old('company_name') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tax_number" class="form-label">Податковий номер *</label>
                                <input type="text" class="form-control" id="tax_number" name="tax_number" 
                                       value="<?= old('tax_number') ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="business_type" class="form-label">Тип бізнесу *</label>
                            <select class="form-select" id="business_type" name="business_type" required>
                                <option value="">Оберіть тип бізнесу</option>
                                <option value="individual" <?= old('business_type') === 'individual' ? 'selected' : '' ?>>
                                    Фізична особа підприємець
                                </option>
                                <option value="llc" <?= old('business_type') === 'llc' ? 'selected' : '' ?>>
                                    ТОВ
                                </option>
                                <option value="corporation" <?= old('business_type') === 'corporation' ? 'selected' : '' ?>>
                                    Корпорація
                                </option>
                                <option value="other" <?= old('business_type') === 'other' ? 'selected' : '' ?>>
                                    Інше
                                </option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Адреса *</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required><?= old('address') ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Опис діяльності *</label>
                            <textarea class="form-control" id="description" name="description" rows="4" 
                                      placeholder="Опишіть свою діяльність та товари, які ви плануєте продавати" required><?= old('description') ?></textarea>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                Я погоджуюся з <a href="<?= baseUrl('/terms') ?>" target="_blank">умовами користування</a> 
                                та <a href="<?= baseUrl('/privacy') ?>" target="_blank">політикою конфіденційності</a> *
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100">Подати заявку</button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <p>Хочете зареєструватися як покупець? <a href="<?= baseUrl('/register/customer') ?>">Реєстрація покупця</a></p>
                        <p>Вже маєте акаунт? <a href="<?= baseUrl('/login') ?>">Увійти</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
