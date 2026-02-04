<?php $title = 'Контакти - Chaser Marketplace'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <!-- Hero Section -->
    <div class="hero-contact text-center py-5 mb-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h1 class="display-4 mb-4">Зв'яжіться з нами</h1>
                <p class="lead">Ми завжди готові допомогти вам з будь-якими питаннями</p>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Contact Form -->
        <div class="col-lg-8 mb-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-envelope me-2"></i>Надішліть нам повідомлення</h4>
                </div>
                <div class="card-body p-4">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <form method="POST" action="<?= baseUrl('contact') ?>">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Ім'я <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= old('name') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= old('email') ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="subject" class="form-label">Тема</label>
                            <select class="form-select" id="subject" name="subject">
                                <option value="">Оберіть тему</option>
                                <option value="general">Загальні питання</option>
                                <option value="order">Питання щодо замовлення</option>
                                <option value="product">Питання про товар</option>
                                <option value="support">Технічна підтримка</option>
                                <option value="complaint">Скарга</option>
                                <option value="suggestion">Пропозиція</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">Повідомлення <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="message" name="message" rows="6" 
                                      placeholder="Опишіть ваше питання або проблему детально..." required><?= old('message') ?></textarea>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="privacy" required>
                            <label class="form-check-label" for="privacy">
                                Я погоджуюсь з <a href="<?= baseUrl('privacy') ?>" target="_blank">політикою конфіденційності</a>
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane me-2"></i>Відправити повідомлення
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="col-lg-4">
            <!-- Contact Details -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Контактна інформація</h5>
                </div>
                <div class="card-body">
                    <div class="contact-item mb-4">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-map-marker-alt text-primary me-3 mt-1"></i>
                            <div>
                                <h6>Адреса</h6>
                                <p class="text-muted mb-0">
                                    вул. Хрещатик, 1<br>
                                    Київ, 01001<br>
                                    Україна
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="contact-item mb-4">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-phone text-success me-3 mt-1"></i>
                            <div>
                                <h6>Телефон</h6>
                                <p class="text-muted mb-0">
                                    <a href="tel:+380441234567" class="text-decoration-none">+38 (044) 123-45-67</a><br>
                                    <a href="tel:+380671234567" class="text-decoration-none">+38 (067) 123-45-67</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="contact-item mb-4">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-envelope text-info me-3 mt-1"></i>
                            <div>
                                <h6>Email</h6>
                                <p class="text-muted mb-0">
                                    <a href="mailto:info@chaser.com" class="text-decoration-none">info@chaser.com</a><br>
                                    <a href="mailto:support@chaser.com" class="text-decoration-none">support@chaser.com</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-clock text-warning me-3 mt-1"></i>
                            <div>
                                <h6>Години роботи</h6>
                                <p class="text-muted mb-0">
                                    Пн-Пт: 9:00 - 19:00<br>
                                    Сб: 10:00 - 16:00<br>
                                    Нд: Вихідний
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-share-alt me-2"></i>Соціальні мережі</h5>
                </div>
                <div class="card-body text-center">
                    <div class="d-flex justify-content-center gap-3">
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="btn btn-outline-info btn-sm">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="fab fa-telegram"></i>
                        </a>
                        <a href="#" class="btn btn-outline-success btn-sm">
                            <i class="fab fa-viber"></i>
                        </a>
                    </div>
                    <p class="text-muted mt-3 mb-0">Слідкуйте за нашими новинами та акціями</p>
                </div>
            </div>

            <!-- FAQ Link -->
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="fas fa-question-circle fa-3x text-primary mb-3"></i>
                    <h5>Не знайшли відповідь?</h5>
                    <p class="text-muted">Перевірте наш розділ часто задаваних питань</p>
                    <a href="<?= baseUrl('faq') ?>" class="btn btn-outline-primary">
                        Переглянути FAQ
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-map me-2"></i>Наше розташування</h4>
                </div>
                <div class="card-body p-0">
                    <div class="map-container">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2540.7678833063973!2d30.5215!3d50.4501!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40d4ce562a19a5bb%3A0x8ecb2d5876db7509!2z0KXRgNC10YnQsNGC0LjQuiwg0JrQuNGX0LIsIDAyMDAw!5e0!3m2!1suk!2sua!4v1652456789012!5m2!1suk!2sua" 
                            width="100%" 
                            height="400" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hero-contact {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    border-radius: 15px;
}

.contact-item {
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 1rem;
}

.contact-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.gap-3 {
    gap: 1rem !important;
}

.map-container {
    position: relative;
    overflow: hidden;
}

.map-container iframe {
    border-radius: 0 0 0.375rem 0.375rem;
}

@media (max-width: 768px) {
    .d-flex.gap-3 {
        flex-wrap: wrap;
        justify-content: center;
    }
}
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
