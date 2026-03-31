<?php
require 'includes/header.php';
$limit = (int)($settings['public_products_display_limit'] ?? 0);
$query = "SELECT * FROM products ORDER BY id DESC";
if ($limit > 0) { $query .= " LIMIT " . $limit; }
$result = $db->query($query);
?>

<section class="content-section">
    <h2 class="section-title">Il Nostro Catalogo</h2>
    <div class="section-divider"></div>

    <?php if (isset($_GET['status']) && $_GET['status'] === 'cart_updated'): ?>
        <div style="text-align:center; background-color: #d4edda; color: #155724; padding: 1rem; border-radius: 5px; margin-bottom: 2rem;">
            Carrello aggiornato con successo!
        </div>
    <?php endif; ?>

    <div class="products-grid">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($product = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <a href="prodotto.php?id=<?php echo $product['id']; ?>">
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </a>
                    <div class="product-card-content">
                         <p class="product-category">Categoria</p>
                         <a href="prodotto.php?id=<?php echo $product['id']; ?>">
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                         </a>
                        <p class="product-price">
                            <?php if (!empty($product['old_price']) && $product['old_price'] > 0): ?>
                                <span style="text-decoration: line-through; color: #999; font-size: 1rem;">€ <?php echo number_format($product['old_price'], 2, ',', '.'); ?></span>
                            <?php endif; ?>
                            € <?php echo number_format($product['price'], 2, ',', '.'); ?>
                        </p>
                        <form action="cart_actions.php" method="POST" style="margin-top: 1rem;">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <input type="hidden" name="redirect_url" value="prodotti.php">
                            <button type="submit" class="btn-primary" style="padding: 0.6rem 1.5rem;">Aggiungi al Carrello</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="section-paragraph">Nessun prodotto disponibile al momento.</p>
        <?php endif; ?>
    </div>
</section>

<?php require 'includes/footer.php'; ?>