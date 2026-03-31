<?php
// includes/menu.php
// Questo file viene incluso nell'header e gestisce la navigazione principale dell'Hub
?>
<nav class="main-nav">
    <a href="/" class="nav-link site-back-link"><i class="fas fa-arrow-left"></i> SITO WEB</a>
    
    <a href="/gestionale/index.php">HUB HOME</a>

    <?php if (!empty($settings['products_section_enabled']) && $settings['products_section_enabled'] == 1): ?>
        <a href="/gestionale/prodotti.php">SHOP</a>
    <?php endif; ?>

    <?php if (!empty($settings['services_section_enabled']) && $settings['services_section_enabled'] == 1): ?>
        <a href="/gestionale/servizi.php">SERVIZI</a>
    <?php endif; ?>

    <?php if ($promo_count > 0 || (!empty($settings['promotions_section_enabled']) && $settings['promotions_section_enabled'] == 1)): ?>
        <a href="/gestionale/promozioni.php">PROMO</a>
    <?php endif; ?>
</nav>

<div class="header-main-right">
    <a href="/gestionale/carrello.php" class="cart-icon">
        <i class="fas fa-shopping-cart"></i>
        <?php if ($cart_item_count > 0): ?>
            <span class="cart-count"><?php echo $cart_item_count; ?></span>
        <?php endif; ?>
    </a>
</div>