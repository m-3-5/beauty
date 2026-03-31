<?php
// /gestionale/includes/functions_v2.php

// Array globale per memorizzare tutti i nostri "agganci" (hooks e filtri)
$hooks = ['actions' => [], 'filters' => []];

function add_action(string $tag, callable $function) {
    global $hooks;
    $hooks['actions'][$tag][] = $function;
}

function do_action(string $tag, ...$args) {
    global $hooks;
    if (!isset($hooks['actions'][$tag])) { return; }
    foreach ($hooks['actions'][$tag] as $function) {
        call_user_func_array($function, $args);
    }
}

/**
 * Aggiunge una funzione a un filtro. Permette di modificare dati.
 */
function add_filter(string $tag, callable $function) {
    global $hooks;
    $hooks['filters'][$tag][] = $function;
}

/**
 * Applica tutti i filtri a un valore.
 */
function apply_filters(string $tag, $value) {
    global $hooks;
    if (!isset($hooks['filters'][$tag])) {
        return $value;
    }
    foreach ($hooks['filters'][$tag] as $function) {
        $value = call_user_func($function, $value);
    }
    return $value;
}