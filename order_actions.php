<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action'])) {
    header('Location: index.php');
    exit();
}

$action = $_POST['action'];

if ($action === 'place_order_cod' || $action === 'place_order_paypal') {
    
    // --- SICUREZZA: RICALCOLA TUTTO SUL SERVER ---
    $customer_postcode = $_SESSION['customer_postcode'] ?? '';
    $tip = $_SESSION['tip'] ?? 0;
    $subtotal = 0;
    $shipping_cost = 0;
    
    if (empty($_SESSION['cart'])) {
        header('Location: carrello.php');
        exit();
    }

    $product_ids = array_keys($_SESSION['cart']);
    $products_from_db = [];
    if (!empty($product_ids)) {
        $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
        $types = str_repeat('i', count($product_ids));
        $stmt_products = $db->prepare("SELECT id, price FROM products WHERE id IN ($placeholders)");
        $stmt_products->bind_param($types, ...$product_ids);
        $stmt_products->execute();
        $result = $stmt_products->get_result();
        while($row = $result->fetch_assoc()) {
            $products_from_db[$row['id']] = $row;
        }
    }

    foreach ($_SESSION['cart'] as $product_id => $details) {
        if(isset($products_from_db[$product_id])) {
            $subtotal += $products_from_db[$product_id]['price'] * $details['quantity'];
        }
    }
    
    $local_postcodes = array_map('trim', explode(',', $settings['shipping_local_postcodes'] ?? ''));
    if (in_array($customer_postcode, $local_postcodes)) {
        $shipping_cost = ($subtotal >= floatval($settings['shipping_local_free_threshold'])) ? 0 : floatval($settings['shipping_local_cost']);
    } else {
        $shipping_cost = ($subtotal >= floatval($settings['shipping_national_free_threshold'])) ? 0 : floatval($settings['shipping_national_cost']);
    }

    $payment_method = ($action === 'place_order_cod') ? 'Contanti alla Consegna' : 'PayPal';
    $cod_fee = ($payment_method === 'Contanti alla Consegna') ? floatval($settings['payment_cod_fee'] ?? 0) : 0;
    $total_final = $subtotal + $shipping_cost + $tip + $cod_fee;

    // --- RACCOLTA DATI CLIENTE DAL FORM ---
    $customer_name = trim($_POST['customer_name'] ?? '');
    $customer_email = trim($_POST['customer_email'] ?? '');
    $shipping_address = trim($_POST['shipping_address'] ?? '');
    $shipping_city = trim($_POST['shipping_city'] ?? '');
    $customer_phone = trim($_POST['customer_phone'] ?? '');

    if (empty($customer_name) || empty($customer_email) || empty($shipping_address)) {
        exit('Errore: Dati di spedizione mancanti.');
    }

    // --- SALVATAGGIO NEL DATABASE ---
    try {
        $db->begin_transaction();

        $stmt_order = $db->prepare(
            "INSERT INTO orders (total_amount, status, customer_name, customer_email, shipping_address, shipping_city, shipping_postcode, customer_phone) 
             VALUES (?, 'In attesa', ?, ?, ?, ?, ?, ?)"
        );
        $stmt_order->bind_param("dssssss", $total_final, $customer_name, $customer_email, $shipping_address, $shipping_city, $customer_postcode, $customer_phone);
        $stmt_order->execute();
        $order_id = $db->insert_id;

        // --- SALVATAGGIO PRODOTTI ORDINATI (CODICE MANCANTE) ---
        $stmt_items = $db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($_SESSION['cart'] as $product_id => $details) {
            if(isset($products_from_db[$product_id])) {
                $price_at_order = $products_from_db[$product_id]['price'];
                $stmt_items->bind_param("iiid", $order_id, $product_id, $details['quantity'], $price_at_order);
                $stmt_items->execute();
            }
        }
        
        $db->commit();

    } catch (Exception $e) {
        $db->rollback();
        error_log($e->getMessage());
        exit('Si è verificato un errore durante la creazione dell\'ordine.');
    }

    // Svuota il carrello e reindirizza
    unset($_SESSION['cart'], $_SESSION['customer_postcode'], $_SESSION['tip']);
    header('Location: grazie.php');
    exit();
}