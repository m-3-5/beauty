<?php
require '../config.php';

$action = $_REQUEST['action'] ?? '';

switch ($action) {
    case 'create':
        $imageUrl = uploadImage($_FILES['image'] ?? null);
        if (!$imageUrl) {
            // Reindirizza con un messaggio di errore se l'immagine è obbligatoria
            header('Location: prodotto_form.php?error=no_image');
            exit('Errore: Immagine principale non fornita.');
        }

        // --- CORREZIONE QUI ---
        // Prima creiamo le variabili per tutti i campi, gestendo quelli opzionali.
        $name = $_POST['name'];
        $description = $_POST['description'] ?? '';
        $price = $_POST['price'];
        $old_price = $_POST['old_price'] ?: null;
        $quantity = $_POST['quantity'] ?: null;
        $sku = $_POST['sku'] ?? '';
        
        $stmt = $db->prepare("INSERT INTO products (name, description, price, old_price, quantity, sku, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
        // Ora passiamo a bind_param solo le variabili
        $stmt->bind_param("ssddiss", $name, $description, $price, $old_price, $quantity, $sku, $imageUrl);
        $stmt->execute();
        break;

    case 'update':
        $id = $_POST['id'];
        $imageUrl = uploadImage($_FILES['image'] ?? null);

        // --- CORREZIONE QUI ---
        $name = $_POST['name'];
        $description = $_POST['description'] ?? '';
        $price = $_POST['price'];
        $old_price = $_POST['old_price'] ?: null;
        $quantity = $_POST['quantity'] ?: null;
        $sku = $_POST['sku'] ?? '';

        if ($imageUrl) {
            $stmt = $db->prepare("UPDATE products SET name=?, description=?, price=?, old_price=?, quantity=?, sku=?, image_url=? WHERE id=?");
            $stmt->bind_param("ssddissi", $name, $description, $price, $old_price, $quantity, $sku, $imageUrl, $id);
        } else {
            $stmt = $db->prepare("UPDATE products SET name=?, description=?, price=?, old_price=?, quantity=?, sku=? WHERE id=?");
            $stmt->bind_param("ssddisi", $name, $description, $price, $old_price, $quantity, $sku, $id);
        }
        $stmt->execute();
        break;

    case 'delete':
        $id = $_GET['id'];
        // TODO: Aggiungere logica per cancellare l'immagine dal server
        $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        break;
}

header('Location: prodotti.php');
exit();