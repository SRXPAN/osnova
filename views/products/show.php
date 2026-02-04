<?php
$title = htmlspecialchars($product['name']) . ' | Chaser Marketplace';
include __DIR__ . '/../layouts/header.php';
?>

<div class="container my-5">
    <div class="row">
        <!-- Product Images -->
        <div class="col-md-6">
            <div class="product-images">
                <?php if (!empty($images)): ?>
                    <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($images as $index => $image): ?>
                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                    <img src="<?= htmlspecialchars($image['image_url']) ?>" 
                                         class="d-block w-100 product-main-image" 
                                         alt="<?= htmlspecialchars($product['name']) ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($images) > 1): ?>
                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <img src="<?= htmlspecialchars($product['image_url'] ?? '/osnova/assets/images/no-image.png') ?>" 
                         class="img-fluid product-main-image" 
                         alt="<?= htmlspecialchars($product['name']) ?>">
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-md-6">
            <div class="product-info">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/osnova/">Головна</a></li>
                        <li class="breadcrumb-item"><a href="/osnova/products">Продукти</a></li>
                        <li class="breadcrumb-item active"><?= htmlspecialchars($product['name']) ?></li>
                    </ol>
                </nav>

                <h1 class="product-title"><?= htmlspecialchars($product['name']) ?></h1>
                
                <!-- Rating -->
                <?php if ($product['avg_rating']): ?>
                    <div class="product-rating mb-3">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?= $i <= round($product['avg_rating']) ? 'text-warning' : 'text-muted' ?>"></i>
                        <?php endfor; ?>
                        <span class="ms-2"><?= number_format($product['avg_rating'], 1) ?> (<?= $product['review_count'] ?> відгуків)</span>
                    </div>
                <?php endif; ?>

                <div class="product-price mb-3">
                    <span class="price"><?= number_format($product['price'], 2) ?> грн</span>
                </div>

                <!-- Product Details -->
                <div class="product-details mb-4">
                    <?php if ($product['flavor']): ?>
                        <p><strong>Смак:</strong> <?= htmlspecialchars($product['flavor']) ?></p>
                    <?php endif; ?>
                    
                    <?php if ($product['volume']): ?>
                        <p><strong>Об'єм:</strong> <?= $product['volume'] ?> мл</p>
                    <?php endif; ?>
                    
                    <?php if ($product['nicotine_content']): ?>
                        <p><strong>Вміст нікотину:</strong> <?= $product['nicotine_content'] ?> мг</p>
                    <?php endif; ?>
                    
                    <p><strong>Категорія:</strong> <?= htmlspecialchars($product['category_name']) ?></p>
                    
                    <p class="stock-status">
                        <strong>Наявність:</strong> 
                        <?php if ($product['stock_quantity'] > 0): ?>
                            <span class="text-success">В наявності (<?= $product['stock_quantity'] ?> шт.)</span>
                        <?php else: ?>
                            <span class="text-danger">Немає в наявності</span>
                        <?php endif; ?>
                    </p>
                </div>

                <!-- Add to Cart Form -->
                <?php if ($product['stock_quantity'] > 0): ?>
                    <form action="/osnova/cart/add" method="POST" class="add-to-cart-form mb-4">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <div class="quantity-selector mb-3">
                            <label for="quantity" class="form-label">Кількість:</label>
                            <div class="input-group" style="max-width: 150px;">
                                <button type="button" class="btn btn-outline-secondary" onclick="decreaseQuantity()">-</button>
                                <input type="number" id="quantity" name="quantity" class="form-control text-center" 
                                       value="1" min="1" max="<?= $product['stock_quantity'] ?>">
                                <button type="button" class="btn btn-outline-secondary" onclick="increaseQuantity()">+</button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-shopping-cart me-2"></i>Додати в кошик
                        </button>
                    </form>
                <?php endif; ?>

                <!-- Description -->
                <?php if ($product['description']): ?>
                    <div class="product-description">
                        <h5>Опис</h5>
                        <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h3>Відгуки</h3>
            
            <!-- Add Review Form -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="add-review-form mb-4">
                    <h5>Залишити відгук</h5>
                    <form action="/osnova/products/<?= $product['id'] ?>/reviews" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Оцінка:</label>
                            <div class="rating-input">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>">
                                    <label for="star<?= $i ?>" class="star">★</label>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="comment" class="form-label">Коментар:</label>
                            <textarea name="comment" id="comment" class="form-control" rows="4"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Відправити відгук</button>
                    </form>
                </div>
            <?php else: ?>
                <p><a href="/osnova/login">Увійдіть</a>, щоб залишити відгук.</p>
            <?php endif; ?>

            <!-- Reviews List -->
            <?php if (!empty($reviews)): ?>
                <div class="reviews-list">
                    <?php foreach ($reviews as $review): ?>
                        <div class="review-item">
                            <div class="review-header">
                                <strong><?= htmlspecialchars($review['user_name']) ?></strong>
                                <div class="review-rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?= $i <= $review['rating'] ? 'text-warning' : 'text-muted' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <small class="text-muted"><?= date('d.m.Y', strtotime($review['created_at'])) ?></small>
                            </div>
                            <p><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Поки що немає відгуків про цей товар.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
        <div class="row mt-5">
            <div class="col-12">
                <h3>Схожі товари</h3>
                <div class="row">
                    <?php foreach ($relatedProducts as $relatedProduct): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <?php 
                            $product = $relatedProduct; // For the product-card partial
                            include __DIR__ . '/../partials/product-card.php'; 
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.product-main-image {
    border-radius: 10px;
    max-height: 500px;
    object-fit: cover;
}
.product-title {
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 1rem;
}
.price {
    font-size: 2rem;
    font-weight: bold;
    color: #28a745;
}
.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}
.rating-input input {
    display: none;
}
.rating-input label {
    cursor: pointer;
    width: 30px;
    height: 30px;
    display: block;
    color: #ddd;
    font-size: 30px;
    line-height: 30px;
    text-align: center;
}
.rating-input label:hover,
.rating-input label:hover ~ label,
.rating-input input:checked ~ label {
    color: #ffc107;
}
.review-item {
    border-bottom: 1px solid #dee2e6;
    padding: 1rem 0;
}
.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}
</style>

<script>
function increaseQuantity() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.getAttribute('max'));
    if (parseInt(input.value) < max) {
        input.value = parseInt(input.value) + 1;
    }
}

function decreaseQuantity() {
    const input = document.getElementById('quantity');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
