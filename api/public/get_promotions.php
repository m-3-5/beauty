<?php
// /gestionale/api/public/get_promotions.php (VERSIONE CORRETTA)

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../../config.php';

$result = $db->query("SELECT title, description, image_url, label_text, whatsapp_link FROM promotions WHERE is_active = 1 ORDER BY created_at DESC");

$promotions = [];
if ($result && $result->num_rows > 0) {
    while($promo = $result->fetch_assoc()) {
        
        // --- LOGICA DI PULIZIA DEL PERCORSO ---
        $image_path = $promo['image_url'];
        // Se il percorso inizia con '/gestionale/', rimuoviamo quella parte per evitare duplicati.
        if (strpos($image_path, '/gestionale/') === 0) {
            $image_path = substr($image_path, strlen('/gestionale/'));
        }
        // --- FINE LOGICA DI PULIZIA ---

        // Costruiamo l'URL per il proxy usando il percorso pulito
        $promo['image_url'] = 'https://' . $_SERVER['HTTP_HOST'] . '/gestionale/api/public/image_proxy.php?path=' . urlencode($image_path);
        
        $promotions[] = $promo;
    }
}

echo json_encode($promotions);