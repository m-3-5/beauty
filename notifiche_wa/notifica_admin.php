<?php
// --- CONFIGURAZIONE TEST PASQUALE ---
$telefono_destinatario = "393487564418"; //
$apikey_wa = "5458750";                //

// Preparazione del messaggio
$testo_notifica = "🌸 *Nuova Iscritta Beauty of Image* 🌸\n\n";
$testo_notifica .= "*Nome:* $nome\n";
$testo_notifica .= "*Tel:* $telefono\n\n";
$testo_notifica .= "Controlla la lista (Pass: *Beauty2026*):\n";
$testo_notifica .= "https://beautyofimage.com/admin_leads.php";

// URL per l'invio
$url = "https://api.callmebot.com/whatsapp.php?phone=$telefono_destinatario&text=" . urlencode($testo_notifica) . "&apikey=$apikey_wa";

// UTILIZZO DI CURL (Più potente e meno soggetto a blocchi del server)
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignora problemi di certificato SSL
$response = curl_exec($ch);
curl_close($ch);

// Debug opzionale: se vuoi vedere cosa risponde il server del bot, 
// puoi togliere il commento alla riga sotto durante i test
// echo "Risposta Server: " . $response; 
?>