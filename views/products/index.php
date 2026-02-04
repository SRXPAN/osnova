<?php
$title = 'Products | Chaser Marketplace';
include __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-md-3">
            <div class="filters-sidebar">
                <h4>Фільтри</h4>
                
                <form method="GET" action="/osnova/products" id="filtersForm">
                    <!-- Category Filter -->
                    <div class="filter-group mb-4">
                        <h6>Категорія</h6>
                        <select name="category" class="form-select" onchange="this.form.submit()">
                            <option value="">Всі категорії</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" 
                                    <?= ($_GET['category'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Search -->
                    <div class="filter-group mb-4">
                        <h6>Пошук</h6>
                        <input type="text" name="search" class="form-control" 
                               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" 
                               placeholder="Назва, смак...">
                    </div>

                    <!-- Price Range -->
                    <div class="filter-group mb-4">
                        <h6>Ціна (грн)</h6>
                        <div class="row">
                            <div class="col-6">
                                <input type="number" name="min_price" class="form-control" 
                                       placeholder="Від" value="<?= $_GET['min_price'] ?? '' ?>">
                            </div>
                            <div class="col-6">
                                <input type="number" name="max_price" class="form-control" 
                                       placeholder="До" value="<?= $_GET['max_price'] ?? '' ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Sort -->
                    <div class="filter-group mb-4">
                        <h6>Сортування</h6>
                        <select name="sort_by" class="form-select" onchange="this.form.submit()">
                            <option value="created_at" <?= ($_GET['sort_by'] ?? '') == 'created_at' ? 'selected' : '' ?>>Новинки</option>
                            <option value="price" <?= ($_GET['sort_by'] ?? '') == 'price' ? 'selected' : '' ?>>Ціна</option>
                            <option value="rating" <?= ($_GET['sort_by'] ?? '') == 'rating' ? 'selected' : '' ?>>Рейтинг</option>
                            <option value="name" <?= ($_GET['sort_by'] ?? '') == 'name' ? 'selected' : '' ?>>Назва</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Застосувати</button>
                    <a href="/osnova/products" class="btn btn-outline-secondary w-100 mt-2">Скинути</a>
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-md-9">
            <div class="products-header mb-4">
                <h2>Продукти</h2>
                <p class="text-muted">Знайдено <?= count($products) ?> товарів</p>
            </div>

            <?php if (empty($products)): ?>
                <div class="text-center py-5">
                    <h4>Товари не знайдено</h4>
                    <p>Спробуйте змінити фільтри пошуку</p>
                    <a href="/osnova/products" class="btn btn-primary">Переглянути всі товари</a>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($products as $product): ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <?php include __DIR__ . '/../partials/product-card.php'; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav aria-label="Products pagination">
                        <ul class="pagination justify-content-center">
                            <!-- Previous -->
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="/osnova/products?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <!-- Page Numbers -->
                            <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                    <a class="page-link" href="/osnova/products?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <!-- Next -->
                            <?php if ($page < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="/osnova/products?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.filters-sidebar {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    position: sticky;
    top: 20px;
}
.filter-group h6 {
    font-weight: 600;
    margin-bottom: 0.5rem;
}
.products-header {
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 1rem;
}
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
