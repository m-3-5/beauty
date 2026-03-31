<?php
// /gestionale/promozione.php (NUOVA VERSIONE SEMPLIFICATA)
require 'includes/header.php';

// Recupera e valida l'ID della promozione dall'URL
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    echo "<section class='content-section'><h1>Promozione non valida.</h1></section>";
    require 'includes/footer.php';
    exit();
}
$promo_id = (int)$_GET['id'];

// Recupera i dati della promozione dal database
$stmt = $db->prepare("SELECT * FROM promotions WHERE id = ? AND is_active = 1");
$stmt->bind_param("i", $promo_id);
$stmt->execute();
$result = $stmt->get_result();
$promo = $result->fetch_assoc();

if (!$promo) {
    echo "<section class='content-section'><h1>Promozione non trovata o non più attiva.</h1></section>";
    require 'includes/footer.php';
    exit();
}
?>

<section class="content-section">

    <div class="promo-detail-container">

        <div class="promo-detail-header-image">
            <img src="<?php echo htmlspecialchars($promo['image_url'] ?? 'assets/images/placeholder.png'); ?>" alt="<?php echo htmlspecialchars($promo['title']); ?>">
        </div>

        <div class="promo-detail-body">
            
            <?php if (!empty($promo['label_text'])): ?>
                <span class="promo-label" style="position: static; display: inline-block; margin-bottom: 1rem;"><?php echo htmlspecialchars($promo['label_text']); ?></span>
            <?php endif; ?>

            <h1 class="page-title" style="text-align: left; margin-top:0;"><?php echo htmlspecialchars($promo['title']); ?></h1>
            
            <div class="promo-full-description">
                <?php echo nl2br(htmlspecialchars($promo['description'])); ?>
            </div>

            <hr>
            
            <div class="promo-detail-actions">
                <?php
                $whatsapp_number = $settings['contact_whatsapp_number'] ?? '';
                if (!empty($whatsapp_number)):
                    $clean_whatsapp_number = preg_replace('/[^0-9]/', '', $whatsapp_number);
                    $whatsapp_message = "Ciao, sono interessato alla promozione: \"" . $promo['title'] . "\"";
                ?>
                    <a href="https://wa.me/<?php echo $clean_whatsapp_number; ?>?text=<?php echo urlencode($whatsapp_message); ?>" target="_blank" class="btn-secondary">
                        <i class="fab fa-whatsapp"></i> Contattaci per questa Promo
                    </a>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>

<?php require 'includes/footer.php'; ?>