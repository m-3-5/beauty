<?php
// /page-sections/hero.php
// Questo blocco visualizza la sezione principale con immagine di sfondo.
// Si aspetta di trovare 'background_image_url', 'title', 'subtitle', 'button_text', 'button_link' nel JSON.
?>
<section class="hero-section" style="background-image: url('<?php echo htmlspecialchars($sectionData['background_image_url'] ?? ''); ?>');">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1><?php echo htmlspecialchars($sectionData['title'] ?? 'Titolo Mancante'); ?></h1>
        <p><?php echo htmlspecialchars($sectionData['subtitle'] ?? ''); ?></p>
        <?php if (!empty($sectionData['button_text']) && !empty($sectionData['button_link'])): ?>
            <a href="<?php echo htmlspecialchars($sectionData['button_link']); ?>" class="btn-primary"><?php echo htmlspecialchars($sectionData['button_text']); ?></a>
        <?php endif; ?>
    </div>
</section>