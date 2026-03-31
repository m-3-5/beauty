<?php
// Dentro wp_check.php, per connetterti al DB sharing usa semplicemente:
require_once 'db_connection.php';
$db_sharing = getSharingConnection();

// 1. Carichiamo il cuore di WordPress
$wp_path = '/var/www/vhosts/beautyofimage.com/httpdocs/wp-load.php';
if (file_exists($wp_path)) {
    require_once($wp_path);
}

// 2. Verifichiamo se l'utente è loggato su WP
if ( is_user_logged_in() ) {
    $current_user = wp_get_current_user();
    
    // Trasformiamo lo username in minuscolo per non avere errori tra "Pasquale" e "pasquale"
    $username_wp = strtolower($current_user->user_login);

    // 3. Permettiamo l'accesso solo a Editori e Amministratori
    if ( current_user_can('edit_others_posts') ) {
        
        try {
            // Connessione al DB dello Sharing
            $db_sharing = new PDO("mysql:host=localhost:3306;dbname=beauty_sharing;charset=utf8", "wp_4qwng", "7CG?aIaGoC9Gsj&0");
            
            // Cerchiamo l'utente nel DB Sharing
            $stmt = $db_sharing->prepare("SELECT id, username, role FROM users WHERE LOWER(username) = ?");
            $stmt->execute([$username_wp]);
            $user_sharing = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user_sharing) {
                // 4. Se lo troviamo, attiviamo la sessione dello sharing
                if (session_status() === PHP_SESSION_NONE) { session_start(); }
                
                $_SESSION['user_id'] = $user_sharing['id'];
                $_SESSION['username'] = $user_sharing['username'];
                $_SESSION['role'] = $user_sharing['role'];

                // Evitiamo loop infiniti: se siamo già in index.php non reindirizzare
                if (basename($_SERVER['PHP_SELF']) != 'index.php') {
                    header("Location: index.php"); 
                    exit;
                }
            }
        } catch (PDOException $e) {
            // Se il DB ha problemi, ignoriamo e lasciamo il login manuale
        }
    }
}