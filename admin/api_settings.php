<?php
require 'includes/header.php';
// ... (Logica di salvataggio simile a spedizioni.php, ma per le chiavi API) ...
?>
<div class="container">
    <h1>API e Integrazioni</h1>
    <p>Inserisci qui le chiavi API per i servizi esterni come sistemi di pagamento e marketplace.</p>
    <form method="POST" action="api_settings.php">
        <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 2rem;">
            <h2>Credenziali di Pagamento</h2>
            <?php include __DIR__ . '/settings-parts/03_payments.php'; ?>
        </div>
        <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 2rem;">
            <h2>Sincronizzazione Marketplace</h2>
            <?php include __DIR__ . '/settings-parts/04_marketplace.php'; ?>
        </div>
        <div class="text-right"><button type="submit" class="btn btn-primary">Salva Chiavi API</button></div>
    </form>
</div>
<?php require 'includes/footer.php'; ?>