<?php
/**
 * Beauty Sharing - Logout
 * Termina la sessione in modo sicuro
 */
require_once 'config.php';

// 1. Svuota tutte le variabili di sessione
$_SESSION = array();

// 2. Se si desidera distruggere completamente la sessione, 
// è necessario cancellare anche il cookie di sessione.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Distrugge la sessione sul server
session_destroy();

// 4. Reindirizza l'utente alla pagina di login (index.php)
header("Location: index.php");
exit;
?>