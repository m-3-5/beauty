<?php
require '../config.php';
// La nostra funzione di upload immagine è disponibile globalmente
// require_once __DIR__ . '/../includes/functions.php';

if (isset($_POST['action']) && $_POST['action'] === 'save_block') {
    $section_id = (int)$_POST['section_id'];
    $page_slug = $_POST['page_slug'];

    $stmt = $db->prepare("SELECT content FROM page_sections WHERE id = ?");
    $stmt->bind_param("i", $section_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $section = $result->fetch_assoc();
    $content = json_decode($section['content'], true);

    // Aggiorna i valori di testo
    if (isset($_POST['content'])) {
        foreach ($_POST['content'] as $key => $value) {
            if (isset($content[$key]) && !is_array($content[$key])) {
                $content[$key] = $value;
            }
        }
    }

    // Gestisce gli upload di immagini
    if (isset($_FILES['content'])) {
        foreach ($_FILES['content']['name'] as $key => $name) {
            if (isset($content[$key]) && $_FILES['content']['error'][$key] == UPLOAD_ERR_OK) {
                $file_details = ['name' => $_FILES['content']['name'][$key], 'type' => $_FILES['content']['type'][$key], 'tmp_name' => $_FILES['content']['tmp_name'][$key], 'error' => $_FILES['content']['error'][$key], 'size' => $_FILES['content']['size'][$key]];
                if ($imageUrl = uploadImage($file_details, $page_slug, null, 85)) {
                    $content[$key] = $imageUrl;
                }
            }
        }
    }

    $updated_json_content = json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $stmt_update = $db->prepare("UPDATE page_sections SET content = ? WHERE id = ?");
    $stmt_update->bind_param("si", $updated_json_content, $section_id);
    $stmt_update->execute();
}

header('Location: builder.php?slug=' . urlencode($page_slug));
exit();