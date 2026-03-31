<?php
require 'includes/header.php';

// Sicurezza: Recupera e valida l'ID del prodotto
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    echo "<section class='content-section'><h1>Prodotto non valido.</h1></section>";
    require 'includes/footer.php';
    exit();
}
$product_id = (int)$_GET['id'];

// Recupera i dati del prodotto dal database
$stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "<section class='content-section'><h1>Prodotto non trovato.</h1></section>";
    require 'includes/footer.php';
    exit();
}

// Recupera le immagini della galleria (se esistono)
$gallery_stmt = $db->prepare("SELECT image_url FROM product_images WHERE product_id = ?");
$gallery_stmt->bind_param("i", $product_id);
$gallery_stmt->execute();
$gallery_result = $gallery_stmt->get_result();
$gallery_images = [];
while ($row = $gallery_result->fetch_assoc()) {
    $gallery_images[] = $row['image_url'];
}
?>

<section class="content-section">
    <div class="product-detail-grid">
        <div class="product-images">
            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="main-image" id="main-product-image">
            
            <div class="gallery-thumbnails">
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Miniatura" class="thumbnail active">
                <?php foreach ($gallery_images as $img_url): ?>
                    <img src="<?php echo htmlspecialchars($img_url); ?>" alt="Miniatura" class="thumbnail">
                <?php endforeach; ?>
            </div>
        </div>

        <div class="product-details">
            <?php if(!empty($product['brand'])): ?>
                <p class="product-category"><?php echo htmlspecialchars($product['brand']); ?></p>
            <?php endif; ?>
            
            <h1 class="product-title-detail"><?php echo htmlspecialchars($product['name']); ?></h1>
            
            <p class="product-price-detail">
                <?php if (!empty($product['old_price']) && $product['old_price'] > 0): ?>
                    <span class="old-price">€ <?php echo number_format($product['old_price'], 2, ',', '.'); ?></span>
                <?php endif; ?>
                € <?php echo number_format($product['price'], 2, ',', '.'); ?>
            </p>
            
            <div class="product-description">
                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
            </div>
            
            <form action="cart_actions.php" method="POST" class="add-to-cart-section">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="hidden" name="redirect_url" value="prodotto.php?id=<?php echo $product['id']; ?>">
                
                <div class="quantity-selector">
                    <label for="quantity">Quantità:</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" style="padding: 0.5rem; width: 60px;">
                </div>
                
                <button type="submit" class="btn-primary">Aggiungi al Carrello</button>
            </form>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mainImage = document.getElementById('main-product-image');
        const thumbnails = document.querySelectorAll('.thumbnail');
        if(mainImage && thumbnails.length > 0) {
            thumbnails.forEach(thumb => {
                thumb.addEventListener('click', function() {
                    mainImage.src = this.src;
                    thumbnails.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        }
    });
</script>

<?php require 'includes/footer.php'; ?>