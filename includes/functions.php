<?php
// /gestionale/includes/functions.php

/**
 * Gestisce l'upload, il ridimensionamento CONDIZIONALE e la conversione in WebP.
 *
 * @param array $file Il file dall'array $_FILES.
 * @param string $prefix Un prefisso per il nome del file.
 * @param int|null $max_width La larghezza massima. Se null, non ridimensiona.
 * @param int $quality La qualità di compressione del WebP.
 * @return string|null Il percorso dell'immagine salvata o null.
 */
function uploadImage($file, $prefix = 'img', $max_width = 1200, $quality = 80) {
    if (!$file || $file['error'] !== UPLOAD_ERR_OK) { return null; }

    $image_info = getimagesize($file["tmp_name"]);
    if ($image_info === false) { return null; }

    $source_image = null;
    switch ($image_info['mime']) {
        case 'image/jpeg': $source_image = imagecreatefromjpeg($file["tmp_name"]); break;
        case 'image/png':  $source_image = imagecreatefrompng($file["tmp_name"]); break;
        case 'image/gif':  $source_image = imagecreatefromgif($file["tmp_name"]); break;
        default: return null;
    }

    if (!$source_image) { return null; }

    $orig_w = imagesx($source_image);
    $orig_h = imagesy($source_image);
    
    // --- MODIFICA CHIAVE QUI ---
    // Esegue il ridimensionamento solo se è specificata una larghezza massima.
    if ($max_width !== null && $orig_w > $max_width) {
        $ratio = $orig_w / $orig_h;
        $new_w = $max_width;
        $new_h = $new_w / $ratio;

        $resized_image = imagecreatetruecolor($new_w, $new_h);
        imagecopyresampled($resized_image, $source_image, 0, 0, 0, 0, $new_w, $new_h, $orig_w, $orig_h);
    } else {
        // Se non ridimensioniamo, usiamo l'immagine originale
        $resized_image = $source_image;
    }

    $targetDir = __DIR__ . "/../uploads/";
    if (!is_dir($targetDir)) { mkdir($targetDir, 0755, true); }
    
    $file_name_without_ext = pathinfo(basename($file["name"]), PATHINFO_FILENAME);
    $newFileName = $prefix . '_' . uniqid() . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $file_name_without_ext) . '.webp';
    $targetFile = $targetDir . $newFileName;

    imagewebp($resized_image, $targetFile, $quality);

    // Libera la memoria (se è stata creata una nuova immagine)
    if ($resized_image !== $source_image) {
        imagedestroy($resized_image);
    }
    imagedestroy($source_image);

return "/gestionale/uploads/" . $newFileName;

}