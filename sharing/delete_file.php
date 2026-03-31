<?php
/**
 * Beauty Sharing - Delete File
 * Rimuove il file dal database e dal server fisicamente
 */
require_once 'db.php';

// 1. Protezione: Solo Pasquale (Admin) può eliminare
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Azione non consentita.");
}

if (isset($_GET['id'])) {
    $fileId = (int)$_GET['id'];

    // 2. Recupero il percorso del file prima di eliminarlo 
    $stmt = $pdo->prepare("SELECT filepath FROM shared_files WHERE id = ?");
    $stmt->execute([$fileId]);
    $file = $stmt->fetch();

    if ($file) {
        $path = $file['filepath'];

        // 3. Eliminazione fisica dal server
        if (file_exists($path)) {
            unlink($path); 
        }

        // 4. Eliminazione dal database 
        $del = $pdo->prepare("DELETE FROM shared_files WHERE id = ?");
        $del->execute([$fileId]);

        header("Location: index.php?msg=deleted");
        exit;
    }
}
header("Location: index.php");