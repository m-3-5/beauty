<?php
require_once __DIR__ . '/../../config.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit(); }

$settings_result = $db->query("SELECT setting_key, setting_value FROM settings");
$settings = [];
while ($row = $settings_result->fetch_assoc()) { $settings[$row['setting_key']] = $row['setting_value']; }

$admin_menu = require_once __DIR__ . '/../../config/menu_admin.php';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pannello di Controllo</title>
    
    <link rel="stylesheet" href="../assets/css/admin.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.min.css" rel="stylesheet">

</head>
<body>
    <aside class="sidebar">
        <a href="index.php" class="logo">Gestionale</a>
        
        <div style="text-align: center; padding-bottom: 1rem; border-bottom: 1px solid #4a5056;">
            <a href="../index.php" target="_blank" class="btn btn-info" style="width: 80%;">Vedi Sito Pubblico</a>
        </div>
        
        <nav>
            <?php foreach ($admin_menu as $item): ?>
                <?php $show_item = is_null($item['setting_key']) || (!empty($settings[$item['setting_key']]) && $settings[$item['setting_key']] == 1); ?>
                <?php if ($show_item): ?>
                    <a href="<?php echo $item['url']; ?>" class="nav-link"><?php echo $item['label']; ?></a>
                <?php endif; ?>
            <?php endforeach; ?>
        </nav>
        
        <div class="settings-group">
             <a href="impostazioni.php" class="nav-link">Moduli e Funzionalità</a>
             <a href="personalizzazione.php" class="nav-link">Personalizzazione</a>
             <a href="api_settings.php" class="nav-link">API e Integrazioni</a>
        </div>

        <div class="logout"><a href="logout.php" class="nav-link">Logout</a></div>
    </aside>
    <main class="main-content">