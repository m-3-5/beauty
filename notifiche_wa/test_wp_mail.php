<?php
// 1. CARICAMENTO WORDPRESS (Percorso Assoluto Server)
$wp_path = '/var/www/vhosts/beautyofimage.com/httpdocs/wp-load.php';

if (!file_exists($wp_path)) {
    die("ERRORE: Non trovo wp-load.php. Verifica il percorso.");
}
require_once($wp_path);

// 2. FORZATURA MITTENTE (Per allinearsi ad Aruba Business)
add_filter('wp_mail_from', function($email) { return 'info@beautyofimage.com'; });
add_filter('wp_mail_from_name', function($name) { return 'Beauty of Image - Test'; });

// 3. DATI INVIO
$to = "info@inm35.net"; // Tua mail di test
$subject = "🚀 TEST FINALE SMTP - " . date('H:i:s');
$message = "
<div style='background:#fff; padding:20px; border:2px solid #d4af37; border-radius:10px; font-family:Arial;'>
    <h2 style='color:#d9509a;'>Test Connessione Certificata</h2>
    <p>Se ricevi questa mail, il plugin <strong>WP Mail SMTP</strong> è configurato correttamente.</p>
    <p>Il server Aruba ha accettato le credenziali di <strong>info@beautyofimage.com</strong>.</p>
    <hr>
    <p style='font-size:0.8em;'>ID Invio: " . uniqid() . "</p>
</div>";

$headers = array('Content-Type: text/html; charset=UTF-8');

// 4. ESECUZIONE TEST
echo "<h2>Diagnostica in corso...</h2>";

$success = wp_mail($to, $subject, $message, $headers);

if ($success) {
    echo "<p style='color:green; font-weight:bold;'>✅ SUCCESSA!</p>";
    echo "La mail è stata accettata dal server SMTP di Aruba. Controlla la Inbox di <strong>$to</strong>.";
} else {
    echo "<p style='color:red; font-weight:bold;'>❌ FALLITO.</p>";
    echo "WordPress non è riuscito a inviare. Controlla i log nel plugin WP Mail SMTP.";
}
?>