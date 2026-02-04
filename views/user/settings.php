<?php $title = 'Account Settings - Chaser Marketplace'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
            <?php include __DIR__ . '/../partials/user-sidebar.php'; ?>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4>Налаштування акаунта</h4>
                </div>
                <div class="card-body">
                    <?php if (flash('success')): ?>
                        <div class="alert alert-success"><?= flash('success') ?></div>
                    <?php endif; ?>
                    
                    <?php if (flash('error')): ?>
                        <div class="alert alert-danger"><?= flash('error') ?></div>
                    <?php endif; ?>

                    <!-- Profile Update Form -->
                    <div class="mb-4">
                        <h5>Персональна інформація</h5>
                        <form method="POST">
                            <input type="hidden" name="action" value="update_profile">
                            <?= csrf_field() ?>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">Ім'я</label>
                                    <input type="text" class="form-control <?= errors('first_name') ? 'is-invalid' : '' ?>" 
                                           id="first_name" name="first_name" value="<?= htmlspecialchars(auth()['first_name']) ?>" required>
                                    <?php if (errors('first_name')): ?>
                                        <div class="invalid-feedback"><?= implode(', ', errors('first_name')) ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Прізвище</label>
                                    <input type="text" class="form-control <?= errors('last_name') ? 'is-invalid' : '' ?>" 
                                           id="last_name" name="last_name" value="<?= htmlspecialchars(auth()['last_name']) ?>" required>
                                    <?php if (errors('last_name')): ?>
                                        <div class="invalid-feedback"><?= implode(', ', errors('last_name')) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control <?= errors('email') ? 'is-invalid' : '' ?>" 
                                       id="email" name="email" value="<?= htmlspecialchars(auth()['email']) ?>" required>
                                <?php if (errors('email')): ?>
                                    <div class="invalid-feedback"><?= implode(', ', errors('email')) ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">Номер телефону</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?= htmlspecialchars(auth()['phone'] ?? '') ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">Адреса</label>
                                <textarea class="form-control" id="address" name="address" rows="3"><?= htmlspecialchars(auth()['address'] ?? '') ?></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Оновити профіль</button>
                        </form>
                    </div>

                    <hr>

                    <!-- Password Change Form -->
                    <div class="mb-4">
                        <h5>Зміна паролю</h5>
                        <form method="POST">
                            <input type="hidden" name="action" value="change_password">
                            <?= csrf_field() ?>
                            
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Поточний пароль</label>
                                <input type="password" class="form-control <?= errors('current_password') ? 'is-invalid' : '' ?>" 
                                       id="current_password" name="current_password" required>
                                <?php if (errors('current_password')): ?>
                                    <div class="invalid-feedback"><?= implode(', ', errors('current_password')) ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Новий пароль</label>
                                <input type="password" class="form-control <?= errors('new_password') ? 'is-invalid' : '' ?>" 
                                       id="new_password" name="new_password" required>
                                <?php if (errors('new_password')): ?>
                                    <div class="invalid-feedback"><?= implode(', ', errors('new_password')) ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Підтвердити пароль</label>
                                <input type="password" class="form-control <?= errors('confirm_password') ? 'is-invalid' : '' ?>" 
                                       id="confirm_password" name="confirm_password" required>
                                <?php if (errors('confirm_password')): ?>
                                    <div class="invalid-feedback"><?= implode(', ', errors('confirm_password')) ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <button type="submit" class="btn btn-warning">Змінити пароль</button>
                        </form>
                    </div>

                    <hr>

                    <!-- Logout -->
                    <div class="text-center">
                        <a href="<?= baseUrl('user/logout') ?>" class="btn btn-danger" 
                           onclick="return confirm('Ви впевнені, що хочете вийти з акаунта?')">
                            <i class="fas fa-sign-out-alt"></i> Вийти з акаунта
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
