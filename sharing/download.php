<?php
/**
 * Beauty Sharing - Download Tunnel
 * Protegge il percorso reale dei file
 */
require_once 'db.php';

// 1. Verifica login
if (!isset($_SESSION['user_id'])) {
    die("Accesso negato.");
}

// 2. Recupero ID file
if (isset($_GET['id'])) {
    $fileId = (int)$_GET['id'];

    // Recupero info file dal database beauty_sharing
    $stmt = $pdo->prepare("SELECT * FROM shared_files WHERE id = ?");
    $stmt->execute([$fileId]);
    $file = $stmt->fetch();

    if ($file) {
        $path = $file['filepath'];
        
        // Controllo se l'utente ha il permesso di scaricarlo
        // (L'admin vede tutto, l'user solo i suoi o i pubblici)
        if ($_SESSION['role'] !== 'admin' && 
            $file['target_user_id'] !== $_SESSION['user_id'] && 
            $file['target_user_id'] !== null) {
            die("Non hai i permessi per scaricare questo file.");
        }

        if (file_exists($path)) {
            // Header per forzare il download dell'Excel o di altri file
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file['filename']) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($path));
            
            // Pulisce il buffer di sistema per evitare corruzioni del file Excel
            ob_clean();
            flush();
            readfile($path);
            exit;
        } else {
            die("Errore: Il file fisico non è stato trovato sul server.");
        }
    }
}
die("File non specificato.");