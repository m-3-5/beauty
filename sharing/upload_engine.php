<?php
/**
 * Beauty Sharing - Upload Engine
 * Gestisce l'upload fisico e la registrazione DB
 */
require_once 'db.php';

// Protezione: Solo admin (Pasquale) può caricare
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Accesso negato. Solo l'amministratore può caricare file.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['files'])) {
    $targetDir = "uploads/";
    $uploadedBy = $_SESSION['user_id'];

    // 1. Identifichiamo l'utente 'Pubblico' per i file generali
    $stmtPub = $pdo->prepare("SELECT id FROM users WHERE username = 'Pubblico' LIMIT 1");
    $stmtPub->execute();
    $pubUser = $stmtPub->fetch();
    $publicId = $pubUser['id'] ?? null;

    // 2. Determiniamo il destinatario
    // Se non selezionato nel form, assegniamo a 'Pubblico'
    $targetUser = (!empty($_POST['target_user_id'])) ? $_POST['target_user_id'] : $publicId;

    // Controllo di sicurezza: se ancora non abbiamo un ID valido, blocchiamo per evitare errori SQL
    if (!$targetUser) {
        die("Errore: Utente 'Pubblico' non trovato nel database. Crealo prima di procedere.");
    }

    foreach ($_FILES['files']['name'] as $key => $val) {
        // Verifica errori di caricamento PHP (es. file troppo grande)
        if ($_FILES['files']['error'][$key] !== UPLOAD_ERR_OK) continue;

        $fileName = basename($_FILES['files']['name'][$key]);
        // Sanificazione: timestamp + nome pulito per evitare sovrascritture
        $safeFileName = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $fileName);
        $targetFilePath = $targetDir . $safeFileName;

        if (move_uploaded_file($_FILES['files']['tmp_name'][$key], $targetFilePath)) {
            try {
                // Inserimento record nella tabella shared_files
                $stmt = $pdo->prepare("INSERT INTO shared_files (filename, filepath, uploaded_by, target_user_id) VALUES (?, ?, ?, ?)");
                $stmt->execute([$fileName, $targetFilePath, $uploadedBy, $targetUser]);
            } catch (PDOException $e) {
                // Log errore database
                error_log("Errore DB Upload: " . $e->getMessage());
            }
        }
    }
    header("Location: index.php?status=success");
    exit;
}