<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="search-header mb-4">
                <h1 class="h3">Результати пошуку</h1>
                <?php if (!empty($_GET['q'])): ?>
                    <p class="text-muted">
                        Результати для: <strong>"<?= htmlspecialchars($_GET['q']) ?>"</strong>
                        <?php if (!empty($products)): ?>
                            <span class="badge bg-primary ms-2"><?= count($products) ?> товарів</span>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($products)): ?>
                <div class="row">
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-4 col-lg-3 mb-4">
                            <?php include __DIR__ . '/../partials/product-card.php'; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <div class="empty-search mb-4">
                        <i class="fas fa-search fa-4x text-muted mb-3"></i>
                        <h3>Товари не знайдено</h3>
                        <p class="text-muted mb-4">
                            <?php if (!empty($_GET['q'])): ?>
                                На жаль, за запитом "<?= htmlspecialchars($_GET['q']) ?>" нічого не знайдено.
                            <?php else: ?>
                                Введіть пошуковий запит у поле вище.
                            <?php endif; ?>
                        </p>
                        
                        <div class="search-suggestions">
                            <h5>Спробуйте:</h5>
                            <ul class="list-unstyled">
                                <li>• Перевірити правопис</li>
                                <li>• Використати більш загальні терміни</li>
                                <li>• Використати синоніми</li>
                                <li>• Зменшити кількість слів</li>
                            </ul>
                        </div>
                        
                        <div class="mt-4">
                            <a href="<?= baseUrl('products') ?>" class="btn btn-primary me-3">
                                <i class="fas fa-th-large me-2"></i>Переглянути всі товари
                            </a>
                            <a href="<?= baseUrl('') ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-home me-2"></i>На головну
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.search-header {
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 1rem;
}

.empty-search {
    max-width: 500px;
    margin: 0 auto;
}

.search-suggestions ul {
    text-align: left;
    display: inline-block;
}

.search-suggestions li {
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

@media (max-width: 576px) {
    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .me-3 {
        margin-right: 0 !important;
    }
}
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>