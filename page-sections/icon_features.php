<?php
// /page-sections/icon_features.php
// Mostra una griglia di 3 "punti di forza" con icona, titolo e testo.
?>
<section class="content-section">
    <h2 class="section-title"><?php echo htmlspecialchars($sectionData['main_title'] ?? 'I Nostri Punti di Forza'); ?></h2>
    <div class="section-divider"></div>
    <div class="features-grid">
        <?php foreach ($sectionData['features'] as $feature): ?>
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas <?php echo htmlspecialchars($feature['icon_class'] ?? 'fa-star'); ?>"></i>
                </div>
                <h3><?php echo htmlspecialchars($feature['title'] ?? ''); ?></h3>
                <p><?php echo htmlspecialchars($feature['description'] ?? ''); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>