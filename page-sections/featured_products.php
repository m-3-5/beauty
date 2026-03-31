<?php
// /page-sections/featured_products.php (VERSIONE CORRETTA)
global $db; // Accediamo alla connessione DB globale

$limit = intval($sectionData['product_limit'] ?? 3);

// Modifichiamo la query per prendere anche l'immagine principale direttamente
$products_result = $db->query("SELECT id, name, price, image_url FROM products WHERE is_active = 1 ORDER BY id DESC LIMIT $limit");
?>
<section class="content-section">
    <h2 class="section-title"><?php echo htmlspecialchars($sectionData['title'] ?? 'Prodotti in Evidenza'); ?></h2>
    <div class="section-divider"></div>
    <div class="products-grid">
        <?php while($product = $products_result->fetch_assoc()): ?>
            <div class="product-card">
                <a href="prodotto.php?id=<?php echo $product['id']; ?>">
                    <?php
                        // NUOVA LOGICA CORRETTA
                        $imageUrl = 'assets/images/placeholder.png'; // Immagine di default

                        // 1. Controlla se c'è un'immagine principale nel prodotto stesso
                        if (!empty($product['image_url'])) {
                            $imageUrl = $product['image_url'];
                        } 
                        // 2. Altrimenti, cerca nella galleria (come faceva prima)
                        else {
                            $image_stmt = $db->prepare("SELECT image_url FROM product_images WHERE product_id = ? ORDER BY id ASC LIMIT 1");
                            $image_stmt->bind_param("i", $product['id']);
                            $image_stmt->execute();
                            $image_result = $image_stmt->get_result();
                            if ($image = $image_result->fetch_assoc()) {
                                $imageUrl = $image['image_url'];
                            }
                        }
                    ?>
                    <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <div class="product-card-content">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="product-price"><?php echo number_format($product['price'], 2, ',', '.'); ?>€</p>
                    </div>
                </a>
            </div>
        <?php endwhile; ?>
    </div>
    <?php if (!empty($sectionData['button_text']) && !empty($sectionData['button_link'])): ?>
        <div class="text-center" style="margin-top: 2rem;">
            <a href="<?php echo htmlspecialchars($sectionData['button_link']); ?>" class="btn-secondary"><?php echo htmlspecialchars($sectionData['button_text']); ?></a>
        </div>
    <?php endif; ?>
</section>