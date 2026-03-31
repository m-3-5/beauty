<?php
require '../config.php'; // Carica la configurazione e il file functions.php

// La funzione uploadImage() non è più qui, viene caricata globalmente.

if (isset($_POST['action']) && $_POST['action'] === 'save_content') {
    $slug = $_POST['page_slug'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Recupera il contenuto JSON esistente per non perdere dati
    $stmt = $db->prepare("SELECT content FROM pages WHERE page_slug = ?");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result();
    $page = $result->fetch_assoc();
    $content = json_decode($page['content'], true);

    // Aggiorna i valori testuali dal form
    if(isset($_POST['content'])) {
        foreach ($_POST['content'] as $key => $value) { 
            if (isset($content[$key])) { 
                $content[$key] = $value; 
            } 
        }
    }

    // Gestisce gli upload delle immagini
    if (isset($_FILES['content'])) {
        foreach ($_FILES['content']['name'] as $key => $name) {
            if (isset($content[$key]) && $_FILES['content']['error'][$key] == UPLOAD_ERR_OK) {
                
                $file_details = [
                    'name' => $_FILES['content']['name'][$key],
                    'type' => $_FILES['content']['type'][$key],
                    'tmp_name' => $_FILES['content']['tmp_name'][$key],
                    'error' => $_FILES['content']['error'][$key],
                    'size' => $_FILES['content']['size'][$key]
                ];

                // --- CHIAMATA ALLA FUNZIONE GLOBALE AGGIORNATA ---
                // Passiamo 'null' come terzo parametro per NON ridimensionare l'immagine,
                // e una qualità di 85.
                if ($imageUrl = uploadImage($file_details, $slug, null, 85)) { 
                    $content[$key] = $imageUrl; 
                }
            }
        }
    }

    // Codifica di nuovo in JSON e salva nel database
    $updated_json_content = json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    $stmt_update = $db->prepare("UPDATE pages SET is_active = ?, content = ? WHERE page_slug = ?");
    $stmt_update->bind_param("iss", $is_active, $updated_json_content, $slug);
    $stmt_update->execute();
}

// Reindirizza l'utente alla lista delle pagine
header('Location: pagine.php');
exit();