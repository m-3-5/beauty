<?php
require 'includes/header.php';
// ... (Logica di salvataggio simile a spedizioni.php, ma solo per i moduli/checkbox) ...
?>
<div class="container">
    <h1>Moduli e Funzionalità</h1>
    <p>Attiva o disattiva le funzionalità principali del tuo gestionale e del sito pubblico.</p>
    <form method="POST" action="impostazioni.php">
        <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <h2>Interruttori Moduli</h2>
            <?php include __DIR__ . '/settings-parts/01_modules.php'; ?>
        </div>
        <div class="text-right" style="margin-top:2rem;"><button type="submit" class="btn btn-primary">Salva Moduli</button></div>
    </form>
</div>
<?php require 'includes/footer.php'; ?>