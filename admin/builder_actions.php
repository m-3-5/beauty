<?php
require '../config.php';

// Sicurezza: assicurati che l'utente sia loggato
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Recupera i parametri dall'URL in modo sicuro
$action = $_GET['action'] ?? '';
$section_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$page_slug = $_GET['slug'] ?? '';

if ($section_id > 0 && $page_slug != '') {
    switch ($action) {
        case 'toggle_active':
            // Inverte lo stato attuale: se è 1 diventa 0, se è 0 diventa 1
            $stmt = $db->prepare("UPDATE page_sections SET is_active = 1 - is_active WHERE id = ?");
            $stmt->bind_param("i", $section_id);
            $stmt->execute();
            break;

        case 'delete':
            // TODO: Se il blocco contiene un'immagine, dovremmo cancellare anche il file
            $stmt = $db->prepare("DELETE FROM page_sections WHERE id = ?");
            $stmt->bind_param("i", $section_id);
            $stmt->execute();
            break;
    }
}

// Dopo ogni operazione, reindirizza l'utente alla pagina del builder
header('Location: builder.php?slug=' . urlencode($page_slug));
exit();