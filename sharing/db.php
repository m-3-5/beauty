<?php
/**
 * Beauty Sharing - Database Connection
 * Utilizza i parametri definiti in config.php
 */
require_once 'config.php';

// Parametri DSN (Data Source Name)
$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";

// Opzioni PDO per sicurezza e prestazioni
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Trasforma errori SQL in eccezioni PHP
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // I risultati sono array associativi
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Disattiva l'emulazione per prevenire SQL Injection
];

try {
    // Tentativo di connessione con le credenziali fornite 
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (\PDOException $e) {
    // In caso di errore, blocca l'esecuzione e mostra il problema (solo se display_errors è ON)
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>