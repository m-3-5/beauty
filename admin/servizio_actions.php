<?php
require '../config.php';

// Funzione di upload immagine (riutilizzata qui) adesso ho creato il file function.php e li si trova la nuova funzione per il caricamenti immagini


$action = $_REQUEST['action'] ?? '';

switch ($action) {
    case 'create':
        $imageUrl = uploadImage($_FILES['image_url'] ?? null);
        $stmt = $db->prepare("INSERT INTO services (name, description, price, price_type, duration, location, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdssss", $_POST['name'], $_POST['description'], $_POST['price'], $_POST['price_type'], $_POST['duration'], $_POST['location'], $imageUrl);
        $stmt->execute();
        break;

    case 'update':
        $id = $_POST['id'];
        $imageUrl = uploadImage($_FILES['image_url'] ?? null);
        if ($imageUrl) {
            $stmt = $db->prepare("UPDATE services SET name=?, description=?, price=?, price_type=?, duration=?, location=?, image_url=? WHERE id=?");
            $stmt->bind_param("ssdssssi", $_POST['name'], $_POST['description'], $_POST['price'], $_POST['price_type'], $_POST['duration'], $_POST['location'], $imageUrl, $id);
        } else {
            $stmt = $db->prepare("UPDATE services SET name=?, description=?, price=?, price_type=?, duration=?, location=? WHERE id=?");
            $stmt->bind_param("ssdsssi", $_POST['name'], $_POST['description'], $_POST['price'], $_POST['price_type'], $_POST['duration'], $_POST['location'], $id);
        }
        $stmt->execute();
        break;

    case 'delete':
        // Aggiungere logica per cancellare file immagine dal server
        $id = $_GET['id'];
        $stmt = $db->prepare("DELETE FROM services WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        break;
}

header('Location: servizi.php');
exit();