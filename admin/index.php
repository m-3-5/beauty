<?php
// /gestionale/admin/index.php

// Questo file sarà l'inizio di ogni pagina protetta
require 'includes/header.php';
?>

<div class="container">
    <h1>Dashboard</h1>
    <p>Benvenuto nel tuo pannello di controllo, <?php echo htmlspecialchars($_SESSION['user_email']); ?>!</p>
    <p>Da qui potrai gestire prodotti, servizi, clienti e molto altro.</p>
</div>

<?php
// Questo file sarà la fine di ogni pagina
require 'includes/footer.php';
?>