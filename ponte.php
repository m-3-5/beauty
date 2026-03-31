<?php
// /gestionale/ponte.php
define('WP_USE_THEMES', false);
require_once('../wp-load.php'); // Carica il motore di WordPress dalla root

if (is_user_logged_in()) {
    $current_user = wp_get_current_user();
    $username = strtolower($current_user->user_login);
    
    // Lista utenti autorizzati (presa dal tuo MU-Plugin)
    $autorizzati = array('pasquale', 'emilia', 'rosalia', 'rzsvmeqjjinx');

    // Verifica se l'utente è in lista o se è un amministratore/editore
    if (in_array($username, $autorizzati) || current_user_can('administrator') || current_user_can('editor')) {
        
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Inizializziamo la sessione del gestionale
        // Impostiamo l'ID 1 come default per l'admin principale del gestionale
        $_SESSION['user_id'] = 1; 
        $_SESSION['business_id'] = 1;
        $_SESSION['email'] = $current_user->user_email;
        $_SESSION['role'] = 'Titolare';
        $_SESSION['logged_in'] = true;

        // Reindirizza alla dashboard del gestionale
        header("Location: admin/index.php");
        exit;
    }
}

// Se non autorizzato, torna al login di WordPress
header("Location: ../wp-login.php?redirect_to=" . urlencode($_SERVER['REQUEST_URI']));
exit;