<?php
require 'includes/header.php';

// Elenco di tutte le chiavi di impostazione gestite da questa pagina
$personalization_keys = [
    'public_color_primary', 
    'public_color_secondary',
    'public_color_text', 
    'public_color_background',
    'public_color_footer',
    'public_products_display_limit',
    'public_services_display_limit',
    'contact_whatsapp_number',
	'public_business_name', // <-- AGGIUNGI QUESTA RIGA
    'public_p_iva', // NUOVA CHIAVE
    'social_instagram_url', // NUOVA CHIAVE
    'social_facebook_url' // NUOVA CHIAVE
	
];

// Logica di salvataggio
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $db->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
    foreach ($personalization_keys as $key) {
        if (isset($_POST[$key])) {
            $value = $_POST[$key];
            $stmt->bind_param("ss", $value, $key);
            $stmt->execute();
        }
    }
    $stmt->close();
    $success_message = "Impostazioni di personalizzazione salvate con successo!";
    $settings_result = $db->query("SELECT * FROM settings");
    $settings = [];
    while ($row = $settings_result->fetch_assoc()) { $settings[$row['setting_key']] = $row['setting_value']; }
}
?>
<style>
    .form-control { width: 100%; padding: 0.75rem; border: 1px solid #ced4da; border-radius: 0.25rem; box-sizing: border-box; }
    .form-group { margin-bottom: 1.5rem; }
    .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
    small { color: #6c757d; font-size: 0.875em; }
</style>

<div class="container">
    <h1>Personalizzazione Sito Pubblico</h1>
    <p>Modifica l'aspetto, lo stile e i dati di contatto del tuo sito web.</p>

    <?php if (isset($success_message)): ?>
        <div style="padding: 1rem; margin-bottom: 1rem; border: 1px solid #c3e6cb; background-color: #d4edda; color: #155724; border-radius: .25rem;">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="personalizzazione.php">
        
        <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 2rem;">
            <h2>Stile e Colori</h2>
            <?php if (file_exists(__DIR__ . '/settings-parts/02_styles.php')) { include __DIR__ . '/settings-parts/02_styles.php'; } ?>
        </div>
        
        <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 2rem;">
            <h2>Limite Visualizzazione Voci</h2>
             <div class="form-group"><label>Numero massimo di prodotti da mostrare</label><input type="number" min="0" class="form-control" name="public_products_display_limit" value="<?php echo htmlspecialchars($settings['public_products_display_limit'] ?? '0'); ?>"></div>
            <div class="form-group"><label>Numero massimo di servizi da mostrare</label><input type="number" min="0" class="form-control" name="public_services_display_limit" value="<?php echo htmlspecialchars($settings['public_services_display_limit'] ?? '0'); ?>"></div>
        </div>

        <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 2rem;">
            <h2>Dati Aziendali e Social</h2>
		
			 <div class="form-group">
        <label>Nome Attività (Pubblico)</label>
        <input type="text" class="form-control" name="public_business_name" value="<?php echo htmlspecialchars($settings['public_business_name'] ?? ''); ?>">
        <small>Questo è il nome che appare come logo testuale nel tuo sito.</small>
    </div>
			
            <div class="form-group">
                <label>Partita IVA</label>
                <input type="text" class="form-control" name="public_p_iva" value="<?php echo htmlspecialchars($settings['public_p_iva'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Numero WhatsApp</label>
                <input type="text" class="form-control" name="contact_whatsapp_number" value="<?php echo htmlspecialchars($settings['contact_whatsapp_number'] ?? ''); ?>" placeholder="Es: 393331234567">
            </div>
             <div class="form-group">
                <label>URL Pagina Instagram</label>
                <input type="url" class="form-control" name="social_instagram_url" value="<?php echo htmlspecialchars($settings['social_instagram_url'] ?? ''); ?>" placeholder="https://www.instagram.com/tuonome">
            </div>
            <div class="form-group">
                <label>URL Pagina Facebook</label>
                <input type="url" class="form-control" name="social_facebook_url" value="<?php echo htmlspecialchars($settings['social_facebook_url'] ?? ''); ?>" placeholder="https://www.facebook.com/tuonome">
            </div>
        </div>

        <div class="text-right">
            <button type="submit" class="btn btn-primary">Salva Personalizzazione</button>
        </div>
    </form>
</div>
<?php require 'includes/footer.php'; ?>