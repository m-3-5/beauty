<?php
require '../config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $order_id = (int)$_POST['order_id'];
    $new_status = $_POST['status'];
    
    // Lista di stati validi per sicurezza
    $valid_stati = ['In attesa', 'In lavorazione', 'Spedito', 'Completato', 'Annullato'];

    if ($order_id > 0 && in_array($new_status, $valid_stati)) {
        $stmt = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $order_id);
        $stmt->execute();
    }
    
    // Reindirizza alla pagina di dettaglio per vedere la modifica
    header('Location: ordine_dettaglio.php?id=' . $order_id);
    exit();
}

// Fallback, reindirizza alla lista ordini
header('Location: ordini.php');
exit();