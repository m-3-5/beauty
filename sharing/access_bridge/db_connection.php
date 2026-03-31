<?php
/**
 * Logica di connessione isolata per lo Sharing
 * Utilizza le costanti S_DB per evitare conflitti con WordPress
 */

function getSharingConnection() {
    $dsn = "mysql:host=" . S_DB_HOST . ";dbname=" . S_DB_NAME . ";charset=utf8mb4";
    
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        // Restituisce l'oggetto PDO usando le credenziali S_
        return new PDO($dsn, S_DB_USER, S_DB_PASS, $options);
    } catch (\PDOException $e) {
        // In caso di errore, blocca e spiega il problema
        die("Errore connessione Sharing: " . $e->getMessage());
    }
}