<?php
// /gestionale/cart_actions.php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }

$action = $_POST['action'] ?? '';
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

switch ($action) {
    case 'add':
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        if ($product_id > 0 && $quantity > 0) {
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$product_id] = ['quantity' => $quantity];
            }
        }
        break;
    case 'update':
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
        if ($product_id > 0 && isset($_SESSION['cart'][$product_id])) {
            if ($quantity > 0) {
                $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            } else {
                unset($_SESSION['cart'][$product_id]);
            }
        }
        break;
    case 'remove':
        if ($product_id > 0) {
            unset($_SESSION['cart'][$product_id]);
        }
        break;
}

// --- MODIFICA CHIAVE QUI ---
// Ora reindirizziamo alla pagina specificata nel form, non più sempre al carrello.
$redirect_url = $_POST['redirect_url'] ?? 'prodotti.php';
// Aggiungiamo un parametro per il messaggio di successo
$redirect_url .= (strpos($redirect_url, '?') === false ? '?' : '&') . 'status=cart_updated';

header('Location: ' . $redirect_url);
exit();