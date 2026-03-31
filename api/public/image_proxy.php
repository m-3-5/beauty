<?php
// /gestionale/api/public/image_proxy.php (VERSIONE FINALE)

header("Access-Control-Allow-Origin: *");

$imagePath = $_GET['path'] ?? null;
if (!$imagePath) {
    http_response_code(400);
    exit('Percorso immagine mancante.');
}

// --- Misure di Sicurezza ---
$basePath = dirname(__DIR__, 2); // Risale di 2 livelli da /api/public/ a /gestionale/
$fullImagePath = $basePath . '/' . $imagePath;

// Normalizziamo per sicurezza
$realBasePath = realpath($basePath);
$realImagePath = realpath($fullImagePath);

// Controlliamo che il file sia dentro la cartella 'uploads'
if ($realImagePath === false || strpos($realImagePath, $realBasePath . DIRECTORY_SEPARATOR . 'uploads') !== 0) {
    http_response_code(403);
    exit('Accesso non consentito.');
}

if (file_exists($realImagePath)) {
    $imageInfo = getimagesize($realImagePath);
    if ($imageInfo) {
        header("Content-Type: " . $imageInfo['mime']);
        readfile($realImagePath);
        exit();
    }
}

http_response_code(404);
exit('Immagine non trovata.');
?>