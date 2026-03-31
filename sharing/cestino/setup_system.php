<?php
// Configurazione dati ricevuti
$db_data = [
    'host' => 'localhost',
    'name' => 'beauty_sharing',
    'user' => 'beauty_sharing_user',
    'pass' => '7_PtUnoCec$u97aq'
];

echo "<h2>Inizio configurazione sistema Beauty Sharing...</h2>";

// 1. Creazione cartella Uploads
if (!file_exists('uploads')) {
    mkdir('uploads', 0755, true);
    echo "✅ Cartella /uploads creata.<br>";
}

// 2. Creazione .htaccess di sicurezza dentro uploads
$htaccess_content = "php_flag engine off\nOptions -Indexes\n<Files \"*.php\">\nOrder allow,deny\nDeny from all\n</Files>";
file_put_contents('uploads/.htaccess', $htaccess_content);
echo "✅ File .htaccess di sicurezza creato in /uploads.<br>";

// 3. Generazione config.php
$config_code = "<?php\ndefine('DB_HOST', '{$db_data['host']}');\ndefine('DB_NAME', '{$db_data['name']}');\ndefine('DB_USER', '{$db_data['user']}');\ndefine('DB_PASS', '{$db_data['pass']}');\ndefine('BASE_URL', '/sharing/');\nsession_start();";
file_put_contents('config.php', $config_code);
echo "✅ File config.php generato.<br>";

// 4. Generazione db.php (Connessione PDO)
$db_code = "<?php\nrequire_once 'config.php';\ntry {\n    \$pdo = new PDO(\"mysql:host=\".DB_HOST.\";dbname=\".DB_NAME.\";charset=utf8mb4\", DB_USER, DB_PASS);\n    \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);\n} catch (PDOException \$e) {\n    die(\"Errore DB: \" . \$e->getMessage());\n}";
file_put_contents('db.php', $db_code);
echo "✅ File db.php generato.<br>";

// 5. Generazione di un index.php di test
$index_test = "<?php require_once 'db.php'; echo '<h1>Sistema Online!</h1>'; echo 'Connessione al database: '; echo \$pdo ? 'FUNZIONANTE' : 'ERRORE';";
file_put_contents('index.php', $index_test);
echo "✅ File index.php di test generato.<br>";

echo "<br><strong>Configurazione completata! Elimina questo file (setup_system.php) per sicurezza.</strong>";
?>