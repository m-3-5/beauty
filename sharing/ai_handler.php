<?php
/**
 * Beauty AI Handler - Versione Testo Pulito
 * Sviluppato per: Pasquale (beautyofimage.com)
 */
require_once 'db.php';

header('Content-Type: application/json');

// 1. Configurazione API
$apiKey = 'AIzaSyA9t0z6VzULsi8AsVvQ5Pp5rDoJeKuzSwE'; //

// 2. Istruzioni di Sistema (Niente Markdown, solo testo)
$systemInstructions = "Sei l'assistente di Beauty Sharing. Rispondi in italiano.
REGOLE:
- SCRIVI SOLO TESTO SEMPLICE.
- NON USARE asterischi (*), cancelletti (#) o grassetti.
- Sii diretto e sintetico.
- Se fai elenchi, usa un trattino (-) e vai a capo.";

// 3. Controllo Sessione
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['answer' => 'Effettua il login per continuare.']);
    exit;
}

// 4. Input Utente
$input = json_decode(file_get_contents('php://input'), true);
$userQuestion = isset($input['question']) ? trim($input['question']) : '';
$chatHistory = isset($input['history']) ? $input['history'] : [];

if (empty($userQuestion)) {
    echo json_encode(['answer' => 'Chiedimi pure quello che ti serve.']);
    exit;
}

// 5. Chiamata API
$contents = [
    ["role" => "user", "parts" => [["text" => "ISTRUZIONI: " . $systemInstructions]]],
    ["role" => "model", "parts" => [["text" => "Ho capito, userò solo testo semplice senza simboli."]]]
];

foreach ($chatHistory as $msg) {
    $contents[] = ["role" => $msg['role'], "parts" => [["text" => $msg['content']]]];
}
$contents[] = ["role" => "user", "parts" => [["text" => $userQuestion]]];

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview:generateContent?key=" . $apiKey;

$payload = [
    "contents" => $contents,
    "generationConfig" => [
        "temperature" => 0.6,
        "maxOutputTokens" => 800, //
        "topP" => 0.9
    ]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    echo json_encode(['answer' => 'Errore di connessione all\'IA.']);
    exit;
}

$result = json_decode($response, true);
$aiText = $result['candidates'][0]['content']['parts'][0]['text'] ?? "Riprova con un'altra domanda.";

// Pulizia forzata degli asterischi e simboli Markdown
$aiText = str_replace(['*', '#'], '', $aiText);

echo json_encode(['answer' => $aiText]);