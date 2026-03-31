<?php
// /page-sections/about_intro.php
// Mostra un titolo e un paragrafo di testo centrato.
?>
<section class="content-section">
    <h2 class="section-title"><?php echo htmlspecialchars($sectionData['title'] ?? 'Titolo Sezione'); ?></h2>
    <div class="section-divider"></div>
    <p class="section-paragraph">
        <?php echo nl2br(htmlspecialchars($sectionData['text_content'] ?? '')); ?>
    </p>
</section>