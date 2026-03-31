<?php
// /gestionale/admin/auth.php

require '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Accetta solo richieste POST
    header('Location: login.php');
    exit();
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header('Location: login.php?error=1');
    exit();
}

try {
    $stmt = $db->prepare("SELECT id, business_id, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Password corretta, creo la sessione
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['business_id'] = $user['business_id'];
        
        header('Location: index.php'); // Reindirizzo alla dashboard
        exit();
    } else {
        // Credenziali errate
        header('Location: login.php?error=1');
        exit();
    }

} catch (Exception $e) {
    error_log($e->getMessage());
    exit('Si è verificato un errore durante il login.');
}