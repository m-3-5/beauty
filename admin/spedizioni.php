<?php
require 'includes/header.php';

// Elenco di tutte le chiavi di impostazione gestite da QUESTA pagina
$shipping_setting_keys = [
    'order_minimum_value',
    'order_enable_tip',
    'shipping_local_cost',
    'shipping_local_free_threshold',
    'shipping_local_postcodes',
    'shipping_national_cost',
    'shipping_national_free_threshold',
    'payment_cod_enabled',
    'payment_cod_fee',
    'business_postcode'
];
// Elenco specifico degli interruttori (checkbox) di questa pagina
$shipping_checkboxes = [
    'order_enable_tip',
    'payment_cod_enabled'
];

// Logica di salvataggio
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $db->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
    foreach ($shipping_setting_keys as $key) {
        if (isset($_POST[$key])) {
            $value = $_POST[$key];
            $stmt->bind_param("ss", $value, $key);
            $stmt->execute();
        }
    }
    foreach ($shipping_checkboxes as $key) {
        if (!isset($_POST[$key])) {
            $zero = '0';
            $stmt->bind_param("ss", $zero, $key);
            $stmt->execute();
        }
    }
    $stmt->close();
    $success_message = "Impostazioni di spedizione salvate con successo!";
    $settings_result = $db->query("SELECT * FROM settings");
    $settings = [];
    while ($row = $settings_result->fetch_assoc()) { $settings[$row['setting_key']] = $row['setting_value']; }
}
?>
<style>
    .form-control { width: 100%; padding: 0.75rem; border: 1px solid #ced4da; border-radius: 0.25rem; }
    .setting-row { display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid #f0f0f0; }
    .setting-row:last-child { border-bottom: none; }
    .setting-label { font-weight: 500; }
    .switch { position: relative; display: inline-block; width: 60px; height: 34px; } .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #dc3545; transition: .4s; border-radius: 34px; }
    .slider:before { position: absolute; content: ""; height: 26px; width: 26px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; }
    input:checked + .slider { background-color: #28a745; } input:checked + .slider:before { transform: translateX(26px); }
    small { color: #6c757d; font-size: 0.875em; }
</style>

<div class="container">
    <h1>Impostazioni Spedizioni e Ordini</h1>
    <?php if (isset($success_message)): ?>
        <div style="padding: 1rem; margin-bottom: 1rem; border: 1px solid #c3e6cb; background-color: #d4edda; color: #155724; border-radius: .25rem;"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <form method="POST" action="spedizioni.php">
        <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            
            <div class="form-group">
                <label>Importo Minimo Ordine (€)</label>
                <input type="number" step="0.01" class="form-control" name="order_minimum_value" value="<?php echo htmlspecialchars($settings['order_minimum_value'] ?? '15.00'); ?>">
                <small>Il cliente non potrà completare l'ordine se il subtotale è inferiore. Metti 0 per disattivare.</small>
            </div>
            <hr style="margin: 2rem 0;">
            
            <h4>Regole Consegna a Domicilio (Locale)</h4>
            <div class="form-group">
                <label>Il CAP della Tua Attività (per Consegna Locale)</label>
                <input type="text" class="form-control" name="business_postcode" value="<?php echo htmlspecialchars($settings['business_postcode'] ?? ''); ?>" placeholder="Es: 85038">
                <small>Necessario per abilitare il pagamento alla consegna.</small>
            </div>
            <div class="form-group">
                <label>Lista CAP Serviti per Consegna Locale (separati da virgola)</label>
                <input type="text" class="form-control" name="shipping_local_postcodes" value="<?php echo htmlspecialchars($settings['shipping_local_postcodes'] ?? ''); ?>" placeholder="Es: 85038, 85030">
            </div>
            <div class="form-group">
                <label>Costo Consegna Locale (€)</label>
                <input type="number" step="0.01" class="form-control" name="shipping_local_cost" value="<?php echo htmlspecialchars($settings['shipping_local_cost'] ?? '3.00'); ?>">
            </div>
            <div class="form-group">
                <label>Soglia per Consegna Locale Gratuita (€)</label>
                <input type="number" step="0.01" class="form-control" name="shipping_local_free_threshold" value="<?php echo htmlspecialchars($settings['shipping_local_free_threshold'] ?? '49.00'); ?>">
            </div>
            <hr style="margin: 2rem 0;">

            <h4>Regole Spedizione Nazionale</h4>
            <div class="form-group">
                <label>Costo Spedizione Nazionale (€)</label>
                <input type="number" step="0.01" class="form-control" name="shipping_national_cost" value="<?php echo htmlspecialchars($settings['shipping_national_cost'] ?? '6.90'); ?>">
            </div>
            <div class="form-group">
                <label>Soglia per Spedizione Nazionale Gratuita (€)</label>
                <input type="number" step="0.01" class="form-control" name="shipping_national_free_threshold" value="<?php echo htmlspecialchars($settings['shipping_national_free_threshold'] ?? '99.00'); ?>">
            </div>
            <hr style="margin: 2rem 0;">
            
            <h4>Opzioni Aggiuntive</h4>
            <div class="setting-row">
                <span class="setting-label">Abilita opzione "Mancia al Fattorino"</span>
                <label class="switch"><input type="checkbox" name="order_enable_tip" value="1" <?php echo ($settings['order_enable_tip'] ?? 0) == 1 ? 'checked' : ''; ?>><span class="slider"></span></label>
            </div>
            <div class="setting-row">
                <span class="setting-label">Abilita "Contanti alla Consegna" (per il CAP dell'attività)</span>
                <label class="switch"><input type="checkbox" name="payment_cod_enabled" value="1" <?php echo ($settings['payment_cod_enabled'] ?? 0) == 1 ? 'checked' : ''; ?>><span class="slider"></span></label>
            </div>
            <div class="form-group" style="margin-top: 1rem;">
                <label>Costo supplemento Contanti alla Consegna (€)</label>
                <input type="number" step="0.01" class="form-control" name="payment_cod_fee" value="<?php echo htmlspecialchars($settings['payment_cod_fee'] ?? '2.50'); ?>">
            </div>

            <div class="text-right" style="margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">Salva Impostazioni Spedizione</button>
            </div>
        </div>
    </form>
</div>
<?php require 'includes/footer.php'; ?>