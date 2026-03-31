<?php
session_start();

// --- CONFIGURAZIONE SICUREZZA ---
$password_corretta = "Beauty2026"; // La password scelta per l'evento

// Gestione Log-out (Opzionale)
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: promemoria_massa.php");
    exit;
}

// Verifica Password al POST
if (isset($_POST['password']) && $_POST['password'] === $password_corretta) {
    $_SESSION['autenticato'] = true;
}

// 1. BLOCCO LOGIN: Se non autenticato, mostra il modulo di accesso
if (!isset($_SESSION['autenticato'])) {
    ?>
    <!DOCTYPE html>
    <html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Accesso Riservato - Beauty of Image</title>
        <style>
            :root { --pink: #d9509a; --gold: #d4af37; }
            body { font-family: 'Segoe UI', sans-serif; background: #fdf2f8; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
            .login-card { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-bottom: 5px solid var(--gold); text-align: center; max-width: 350px; width: 90%; }
            .logo { max-width: 120px; margin-bottom: 20px; }
            input[type="password"] { width: 100%; padding: 12px; margin: 20px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-size: 16px; text-align: center; }
            button { background: var(--pink); color: white; border: none; padding: 12px 30px; border-radius: 8px; font-weight: bold; cursor: pointer; width: 100%; transition: 0.3s; }
            button:hover { background: #c13d85; }
            p { color: #666; font-size: 0.9em; }
        </style>
    </head>
    <body>
        <div class="login-card">
            <img src="../Beauty Logo.png" alt="Logo" class="logo">
            <h2>Area Riservata</h2>
            <p>Inserisci la password per gestire i promemoria del 22 Marzo.</p>
            <form method="POST">
                <input type="password" name="password" placeholder="Password" required autofocus>
                <button type="submit">ACCEDI ALLA DASHBOARD</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// --- 2. LOGICA DATABASE (Solo se autenticato) ---
define('DB_NAME', 'wp_mypjq');
define('DB_USER', 'wp_4qwng');
define('DB_PASSWORD', '7CG?aIaGoC9Gsj&0');
define('DB_HOST', 'localhost:3306');

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8", DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Esportazione CSV
    if (isset($_GET['download'])) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=lista_iscritti_beauty.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, array('Nome', 'Telefono', 'Email', 'Data Iscrizione'));
        $query = $pdo->query("SELECT nome, telefono, email, data_iscrizione FROM leads_inaugurazione ORDER BY nome ASC");
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit;
    }

    $stmt = $pdo->query("SELECT * FROM leads_inaugurazione ORDER BY data_iscrizione DESC");
    $leads = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Errore Database: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Promemoria - Beauty of Image</title>
    <style>
        :root { --pink: #d9509a; --gold: #d4af37; --bg: #fdf2f8; }
        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: #333; padding: 20px; }
        .container { max-width: 1000px; margin: auto; }
        .header { background: white; padding: 25px; border-radius: 15px; border-bottom: 4px solid var(--gold); box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; margin-bottom: 30px; position: relative; }
        .logout-link { position: absolute; top: 10px; right: 20px; color: #999; text-decoration: none; font-size: 0.8em; }
        
        .export-box { background: #fff; padding: 20px; border-radius: 12px; margin-bottom: 30px; border: 1px solid #eee; }
        textarea { width: 100%; height: 80px; margin: 10px 0; border-radius: 8px; border: 1px solid #ddd; padding: 10px; font-family: monospace; resize: none; }
        .btn-csv { background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: bold; }

        .lead-card { background: white; padding: 15px; border-radius: 10px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; border-left: 5px solid #ccc; transition: 0.3s; }
        .lead-card.sent { border-left-color: #28a745; background: #f0fff4; opacity: 0.8; }
        .lead-info { flex-grow: 1; }
        .lead-name { font-weight: bold; color: var(--pink); font-size: 1.1em; }
        .lead-meta { font-size: 0.85em; color: #666; }
        
        .btn-wa { background: #25D366; color: white; padding: 10px 15px; border-radius: 8px; text-decoration: none; font-weight: bold; display: flex; align-items: center; gap: 8px; }
        .check-icon { display: none; color: #28a745; font-size: 1.5em; margin-right: 15px; }
        .sent .check-icon { display: block; }
        .sent .btn-wa { background: #666; pointer-events: none; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <a href="?logout=1" class="logout-link">Esci 🔓</a>
        <img src="../Beauty Logo.png" style="max-width: 120px;">
        <h1>Gestione Promemoria 22 Marzo</h1>
        <p>Inaugurazione Beauty of Image - Corso Garibaldi, 7 Senise</p>
    </div>

    <div class="export-box">
        <h3>📋 Esportazione per Broadcast WhatsApp</h3>
        <p>Copia questi numeri per inviarli tramite lista Broadcast nativa:</p>
        <textarea readonly><?php 
            $numeri = array_map(function($l) { return preg_replace('/[^0-9]/', '', $l['telefono']); }, $leads);
            echo implode(", ", $numeri); 
        ?></textarea>
        <div style="margin-top:15px;">
            <a href="?download=csv" class="btn-csv">📥 Scarica lista Excel (CSV)</a>
        </div>
    </div>

    <h3>✅ Lista Iscritti e Invio Singolo (Più sicuro)</h3>
    <p>Usa questi tasti per inviare il promemoria personalizzato. Lo stato dell'invio viene salvato localmente su questo browser.</p>
    
    <div id="leads-list">
        <?php foreach ($leads as $lead): 
            $testo = "Ciao " . $lead['nome'] . "! ✨ Ti ricordiamo l'appuntamento di oggi alle ore 17:00 da Beauty of Image (Corso Garibaldi, 7 - Senise). Non vediamo l'ora di brindare insieme! 🍾";
            $wa_url = "https://wa.me/" . preg_replace('/[^0-9]/', '', $lead['telefono']) . "?text=" . urlencode($testo);
        ?>
            <div class="lead-card" id="lead-<?php echo $lead['id']; ?>">
                <div class="check-icon">✓</div>
                <div class="lead-info">
                    <div class="lead-name"><?php echo htmlspecialchars($lead['nome']); ?></div>
                    <div class="lead-meta"><?php echo $lead['telefono']; ?> | Iscritta il: <?php echo date('d/m H:i', strtotime($lead['data_iscrizione'])); ?></div>
                </div>
                <a href="<?php echo $wa_url; ?>" target="_blank" class="btn-wa" onclick="markAsSent(<?php echo $lead['id']; ?>)">
                    📱 Invia Promemoria
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
function markAsSent(id) {
    const card = document.getElementById('lead-' + id);
    card.classList.add('sent');
    let sentLeads = JSON.parse(localStorage.getItem('beauty_sent_leads') || '[]');
    if (!sentLeads.includes(id)) {
        sentLeads.push(id);
        localStorage.setItem('beauty_sent_leads', JSON.stringify(sentLeads));
    }
}

window.onload = function() {
    let sentLeads = JSON.parse(localStorage.getItem('beauty_sent_leads') || '[]');
    sentLeads.forEach(id => {
        const card = document.getElementById('lead-' + id);
        if (card) card.classList.add('sent');
    });
}
</script>

</body>
</html>