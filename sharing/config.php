<?php
/**
 * Beauty Sharing - Configuration File
 * Sviluppato per: Pasquale (beautyofimage.com)
 * Lead Developer: Max
 */

// 1. Parametri Database (estratti da Db.txt e SQL dump)
define('DB_HOST', 'localhost');
define('DB_NAME', 'beauty_sharing');      // Nome database [cite: 1]
define('DB_USER', 'beauty_sharing_user'); // Utente database [cite: 3]
define('DB_PASS', '7_PtUnoCec$u97aq');    // Password database [cite: 3]


// 2. Percorsi di Sistema
// Se rinomini la cartella /sharing/, cambia solo questa riga:
define('BASE_URL', '/sharing/'); 

// Percorso fisico per il caricamento file
define('UPLOAD_DIR', __DIR__ . '/uploads/');

// 3. Impostazioni di Sicurezza e Sessione
// Impedisce il dirottamento della sessione
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 4. Gestione Errori (Disattiva in produzione impostando a 0)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>