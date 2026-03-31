<?php
require_once __DIR__ . '/../config.php';

// Caricamento impostazioni
$settings_result = $db->query("SELECT * FROM settings");
$settings = [];
while ($row = $settings_result->fetch_assoc()) { 
    $settings[$row['setting_key']] = $row['setting_value']; 
}



// Logica visibilità Promozioni
$promo_count = 0;
if (isset($db)) {
    $promo_check_result = $db->query("SELECT COUNT(id) as count FROM promotions WHERE is_active = 1");
    if($promo_check_result) {
        $promo_count = $promo_check_result->fetch_assoc()['count'];
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($settings['public_business_name'] ?? 'Beauty of Image - Hub'); ?></title>
    
    <style>
        :root {
            --colore-primario: #d4af37; /* Oro */
            --colore-secondario: #1a1a1a; /* Nero */
            --font-main: 'Work Sans', sans-serif;
        }
    </style>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    
    <link rel="stylesheet" href="assets/css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/beauty-design/custom-style.css?v=<?php echo time(); ?>">
</head>
<body>

<header class="site-header">
    <div class="header-main">
        <div class="container header-flex-container">
            
            <div class="header-main-left">
                <a href="/gestionale/index.php" class="logo">
                    <img src="https://beautyofimage.com/wp-content/uploads/2026/03/Logo.png" alt="Logo" style="height: 75px; width: auto;">
                </a>
            </div>

            <div class="header-main-center">
                <?php include __DIR__ . '/menu.php'; ?>
            </div>

        </div>
    </div>
</header>
<main>