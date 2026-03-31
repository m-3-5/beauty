<?php
// 1. Carichiamo WordPress
$wp_path = '../wp-load.php'; 
if (file_exists($wp_path)) {
    require_once($wp_path);
}

// 2. Controlliamo se l'utente è loggato in WordPress (Pasquale o Emilia)
if ( is_user_logged_in() && current_user_can('edit_others_posts') ) {
    
    $current_user = wp_get_current_user();
    $username_wp = strtolower($current_user->user_login);

    // --- RECUPERO CREDENZIALI DALLA TUA SICUREZZA ---
    // Invece di scriverle noi, leggiamo il tuo file config.php originale
    // Usiamo un trucco per leggere il file senza mandare in crash WordPress
    $config_content = file_get_contents('config.php');
    
    // Estraiamo il nome DB, Utente e Password dal tuo file originale
    preg_match("/define\('DB_NAME',\s*'(.*)'\)/", $config_content, $db_name);
    preg_match("/define\('DB_USER',\s*'(.*)'\)/", $config_content, $db_user);
    preg_match("/define\('DB_PASS',\s*'(.*)'\)/", $config_content, $db_pass);
    
    $real_db   = $db_name[1];
    $real_user = $db_user[1];
    $real_pass = $db_pass[1];

    try {
        // Proviamo a connetterci con le tue "chiavi forti"
        $db_sharing = new PDO("mysql:host=localhost;dbname=$real_db;charset=utf8mb4", $real_user, $real_pass);
        
        $stmt = $db_sharing->prepare("SELECT id, username, role FROM users WHERE LOWER(username) = ?");
        $stmt->execute([$username_wp]);
        $user_s = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user_s) {
            if (session_status() === PHP_SESSION_NONE) { session_start(); }
            
            $_SESSION['user_id'] = $user_s['id'];
            $_SESSION['username'] = $user_s['username'];
            $_SESSION['role'] = $user_s['role'];

            header("Location: index.php");
            exit;
        } else {
            die("Utente WP riconosciuto, ma non presente nel database sharing.");
        }
    } catch (PDOException $e) {
        die("La tua sicurezza ha bloccato l'accesso. Errore: " . $e->getMessage());
    }
} else {
    // Se non è loggato in WP, lo mandiamo al login
    wp_redirect(wp_login_url( home_url('/sharing/ponte.php') ));
    exit;
}