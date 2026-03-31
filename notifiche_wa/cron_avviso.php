<?php
// 1. CARICAMENTO MOTORE WORDPRESS (Percorso Assoluto Certificato)
$wp_path = '/var/www/vhosts/beautyofimage.com/httpdocs/wp-load.php';
require_once($wp_path);

// 2. CONFIGURAZIONE DESTINATARI
$telefono_beauty = "393487564418"; // Tuo numero per monitoraggio
$apikey_beauty = "5458750"; 
$email_destinatario = "info@beautyofimage.com";

// 3. FORZATURA MITTENTE (Per allinearsi al plugin SMTP)
add_filter('wp_mail_from', function($email) { return 'info@beautyofimage.com'; });
add_filter('wp_mail_from_name', function($name) { return 'Beauty of Image'; });

// --- FASE A: INVIO WHATSAPP (CallMeBot) ---
$testo_wa = "🚨 *PROMEMORIA BEAUTY OF IMAGE* 🚨\n\n";
$testo_wa .= "Beauty, mancano pochi giorni all'evento del 22 Marzo!\n";
$testo_wa .= "🔗 Gestione Inviti (Pass: Beauty2026): https://beautyofimage.com/notifiche_wa/promemoria_massa.php";

$url = "https://api.callmebot.com/whatsapp.php?phone=$telefono_beauty&text=" . urlencode($testo_wa) . "&apikey=$apikey_beauty";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_exec($ch);
curl_close($ch);

// --- FASE B: INVIO EMAIL (WordPress wp_mail) ---
$subject = "⚠️ Promemoria: Inviare inviti per l'inaugurazione del 22 Marzo";
$message = "
<div style='font-family: Arial, sans-serif; border: 2px solid #d4af37; padding: 25px; border-radius: 10px; max-width: 600px;'>
    <h2 style='color: #d9509a;'>Ciao Beauty,</h2>
    <p>Questo è il promemoria automatico per l'inaugurazione in <strong>Corso Garibaldi, 7</strong>.</p>
    <p>È il momento di avvisare tutte le iscritte! Clicca sul tasto qui sotto per accedere alla lista e inviare i promemoria WhatsApp:</p>
    <p style='text-align: center; margin: 30px 0;'>
        <a href='https://beautyofimage.com/notifiche_wa/promemoria_massa.php' 
           style='background-color: #d4af37; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 18px;'>
           APRI DASHBOARD
        </a>
    </p>
    <p>Password di accesso: <strong>Beauty2026</strong></p>
    <hr style='border: 0; border-top: 1px solid #eee; margin-top: 20px;'>
    <p style='font-size: 12px; color: #999;'>Sistema di automazione creato per Beauty of Image - Senise</p>
</div>";

$headers = array('Content-Type: text/html; charset=UTF-8');

// Invio tramite il motore di WordPress
wp_mail($email_destinatario, $subject, $message, $headers);

echo "Procedura completata con successo: WhatsApp inviato e Email consegnata via WordPress.";
?>