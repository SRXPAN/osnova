<div class="card product-card h-100">
    <div class="card-img-container">
        <img src="<?= htmlspecialchars($product['image_url'] ?? '/osnova/assets/images/no-image.png') ?>" 
             class="card-img-top" 
             alt="<?= htmlspecialchars($product['name']) ?>">
        
        <?php if ($product['stock_quantity'] <= 0): ?>
            <div class="badge bg-danger position-absolute top-0 end-0 m-2">Немає в наявності</div>
        <?php endif; ?>
        
        <?php if (isset($product['avg_rating']) && $product['avg_rating'] > 0): ?>
            <div class="rating-badge position-absolute top-0 start-0 m-2">
                <i class="fas fa-star text-warning"></i>
                <span><?= number_format($product['avg_rating'], 1) ?></span>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="card-body d-flex flex-column">
        <h6 class="card-title"><?= htmlspecialchars($product['name']) ?></h6>
        
        <?php if (!empty($product['flavor'])): ?>
            <p class="card-text text-muted small mb-1">
                <i class="fas fa-leaf me-1"></i><?= htmlspecialchars($product['flavor']) ?>
            </p>
        <?php endif; ?>
        
        <?php if (!empty($product['nicotine_content'])): ?>
            <p class="card-text text-muted small mb-2">
                <i class="fas fa-flask me-1"></i><?= $product['nicotine_content'] ?> мг нікотину
            </p>
        <?php endif; ?>
        
        <div class="mt-auto">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="price h5 mb-0 text-success"><?= number_format($product['price'], 2) ?> грн</span>
                <?php if (isset($product['category_name'])): ?>
                    <small class="text-muted"><?= htmlspecialchars($product['category_name']) ?></small>
                <?php endif; ?>
            </div>
            
            <div class="d-grid gap-2">
                <a href="/osnova/products/<?= $product['id'] ?>" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-eye me-1"></i>Переглянути
                </a>
                
                <?php if ($product['stock_quantity'] > 0): ?>
                    <form action="/osnova/cart/add" method="POST" class="add-to-cart-quick">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-shopping-cart me-1"></i>В кошик
                        </button>
                    </form>
                <?php else: ?>
                    <button class="btn btn-secondary btn-sm w-100" disabled>
                        <i class="fas fa-times me-1"></i>Немає в наявності
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.product-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: 1px solid #dee2e6;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.card-img-container {
    position: relative;
    overflow: hidden;
    height: 200px;
}

.card-img-top {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .card-img-top {
    transform: scale(1.05);
}

.rating-badge {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    padding: 2px 8px;
    font-size: 0.8rem;
}

.price {
    font-weight: 600;
}

.add-to-cart-quick {
    margin: 0;
}
</style>


</style>
