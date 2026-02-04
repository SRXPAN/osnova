<?php $title = 'Головна - Chaser Marketplace'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="hero-section bg-primary text-white py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 mb-3">Chaser Marketplace</h1>
                <p class="lead mb-4">Найкращі вейпи та аксесуари для справжніх поціновувачів</p>
                <a href="/osnova/products" class="btn btn-light btn-lg">Переглянути товари</a>
            </div>
            <div class="col-md-6">
                <img src="/osnova/assets/images/hero-vape.jpg" alt="Vape" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Featured Products -->
    <section class="mb-5">
        <h2 class="text-center mb-4">Рекомендовані товари</h2>
        <div class="row">
            <?php if (!empty($featuredProducts)): ?>
                <?php foreach ($featuredProducts as $product): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <?php include __DIR__ . '/../partials/product-card.php'; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">Товари тимчасово недоступні</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Categories -->
    <section class="mb-5">
        <h2 class="text-center mb-4">Категорії</h2>
        <div class="row">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card category-card h-100">
                            <?php if ($category['image_url']): ?>
                                <img src="<?= htmlspecialchars($category['image_url']) ?>" 
                                     class="card-img-top" alt="<?= htmlspecialchars($category['name']) ?>"
                                     style="height: 200px; object-fit: cover;">
                            <?php endif; ?>
                            <div class="card-body text-center d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($category['name']) ?></h5>
                                <p class="card-text flex-grow-1"><?= htmlspecialchars($category['description'] ?? '') ?></p>
                                <a href="/osnova/products?category=<?= $category['id'] ?>" class="btn btn-outline-primary mt-auto">
                                    Переглянути товари
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">Категорії тимчасово недоступні</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-4 text-center mb-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-shipping-fast fa-3x text-primary"></i>
                    </div>
                    <h5>Швидка доставка</h5>
                    <p class="text-muted">Доставка по всій Україні протягом 1-2 днів</p>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-shield-alt fa-3x text-primary"></i>
                    </div>
                    <h5>Гарантія якості</h5>
                    <p class="text-muted">Тільки оригінальні товари від перевірених виробників</p>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-headset fa-3x text-primary"></i>
                    </div>
                    <h5>Підтримка 24/7</h5>
                    <p class="text-muted">Наша команда завжди готова допомогти вам</p>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.category-card {
    transition: transform 0.3s ease;
}
.category-card:hover {
    transform: translateY(-5px);
}
.features-section {
    margin-top: 3rem;
}
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
