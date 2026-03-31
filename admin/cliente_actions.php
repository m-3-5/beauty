<?php
require '../config.php';

$action = $_REQUEST['action'] ?? '';

switch ($action) {
    case 'create':
        $stmt = $db->prepare("INSERT INTO customers (name, email, phone, address, notes) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", 
            $_POST['name'], 
            $_POST['email'], 
            $_POST['phone'],
            $_POST['address'],
            $_POST['notes']
        );
        $stmt->execute();
        break;

    case 'update':
        $id = $_POST['id'];
        $stmt = $db->prepare("UPDATE customers SET name=?, email=?, phone=?, address=?, notes=? WHERE id=?");
        $stmt->bind_param("sssssi", 
            $_POST['name'], 
            $_POST['email'], 
            $_POST['phone'],
            $_POST['address'],
            $_POST['notes'],
            $id
        );
        $stmt->execute();
        break;

    case 'delete':
        $id = $_GET['id'];
        $stmt = $db->prepare("DELETE FROM customers WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        break;
}

header('Location: clienti.php');
exit();