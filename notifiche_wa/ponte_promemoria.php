<?php
/**
 * Ponte di accesso per Notifiche WA / Promemoria
 */

// 1. Carichiamo WordPress (il percorso ../ torna indietro di una cartella fino alla root)
$wp_path = '../wp-load.php'; 
if (file_exists($wp_path)) {
    require_once($wp_path);
} else {
    die("Errore: WordPress non trovato nel percorso specificato.");
}

// 2. Controlliamo se l'utente è loggato in WordPress ed è un Titolare (Editore/Admin)
if ( is_user_logged_in() && current_user_can('edit_others_posts') ) {
    
    // 3. Attiviamo la sessione per lo script delle notifiche
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    
    // Creiamo una "chiave" di sessione che useremo per proteggere i file WA
    $_SESSION['auth_notifiche'] = true; 
    $_SESSION['user_wp'] = wp_get_current_user()->user_login;

    // 4. DECOLLO: Lo mandiamo alla pagina principale dei promemoria
    // Assicurati che il nome del file sia corretto (es. promemoria_massa.php)
    header("Location: promemoria_massa.php"); 
    exit;
    
} else {
    // Se non è loggato o non è autorizzato, lo mandiamo al login di WordPress
    wp_redirect( wp_login_url( home_url('/notifiche_wa/ponte_promemoria.php') ) );
    exit;
}