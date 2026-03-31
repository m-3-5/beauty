<?php
// /page-sections/contact_map.php
// Mostra informazioni di contatto e una mappa iframe.
?>
<section class="content-section">
    <h2 class="section-title"><?php echo htmlspecialchars($sectionData['title'] ?? 'Vieni a Trovarci'); ?></h2>
    <div class="section-divider"></div>
    <div class="contact-map-grid">
        <div class="contact-info">
            <h3><?php echo htmlspecialchars($sectionData['business_name'] ?? ''); ?></h3>
            <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($sectionData['address'] ?? ''); ?></p>
            <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($sectionData['phone'] ?? ''); ?></p>
            <p><i class="fab fa-whatsapp"></i> <?php echo htmlspecialchars($sectionData['whatsapp'] ?? ''); ?></p>
            <h4>Orari di Apertura:</h4>
            <?php foreach ($sectionData['opening_hours'] as $line): ?>
                <p><?php echo htmlspecialchars($line); ?></p>
            <?php endforeach; ?>
        </div>
        <div class="map-container">
            <?php echo $sectionData['map_iframe_code'] ?? '<p>Codice Iframe Mappa non configurato.</p>'; ?>
        </div>
    </div>
</section>