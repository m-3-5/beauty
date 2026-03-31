<?php

/**
 * Beauty Sharing - Main Dashboard & Login
 * Sviluppato per: Pasquale
 */
require_once 'db.php';


// 1. Gestione Logica Login
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Interrogazione tabella users
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Accesso negato: credenziali non valide.";
    }
}

$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = ($isLoggedIn && $_SESSION['role'] === 'admin');
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beauty Sharing - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
	<link rel="manifest" href="manifest.json">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .login-card { max-width: 400px; margin: 100px auto; border: none; border-radius: 15px; }
        .navbar { background-color: #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .card { border: none; border-radius: 12px; transition: transform 0.2s; }
        .table thead { background-color: #f8f9fa; }
        .badge-pubblico { background-color: #e9ecef; color: #495057; }
    </style>
</head>	
<body>

<?php if (!$isLoggedIn): ?>
    <div class="container">
        <div class="card login-card shadow-lg p-4">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-primary">Beauty Sharing</h2>
                <p class="text-muted">Accedi per gestire i tuoi file</p>
            </div>
            <?php if ($error): ?>
                <div class="alert alert-danger d-flex align-items-center"><i class="bi bi-exclamaition-triangle-fill me-2"></i><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Username</label>
                    <input type="text" name="username" class="form-control form-control-lg" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Password</label>
                    <input type="password" name="password" class="form-control form-control-lg" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary btn-lg w-100 shadow-sm mt-3">Accedi</button>
            </form>
        </div>
    </div>

<?php else: ?>
    <nav class="navbar navbar-expand-lg navbar-light sticky-top mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">Beauty Sharing</a>
            <div class="d-flex align-items-center">
    <span class="me-3 d-none d-md-inline text-muted">
        Benvenuto, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
    </span>

    <a href="profile.php" class="btn btn-outline-primary btn-sm me-2">
        <i class="bi bi-person-circle me-1"></i>Profilo
    </a>

    <a href="logout.php" class="btn btn-outline-danger btn-sm">
        <i class="bi bi-box-arrow-right me-1"></i>Esci
    </a>
</div>
        </div>
    </nav> <?php include 'app_installer.php'; ?>

    <div class="container">
        <div class="row">
            <?php if ($isAdmin): ?>
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-sm p-4">
                        <h5 class="fw-bold mb-4"><i class="bi bi-cloud-arrow-up-fill me-2 text-primary"></i>Nuova Condivisione</h5>
                        <form action="upload_engine.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-uppercase">1. Seleziona file (Excel, PDF, Immagini)</label>
                                <input type="file" name="files[]" class="form-control shadow-sm" multiple required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-uppercase">2. Destinatario</label>
                                <select name="target_user_id" class="form-select shadow-sm">
                                    <option value="">Seleziona un utente...</option>
                                    <?php
                                    // Recupero utenti per dropdown
                                    $users = $pdo->query("SELECT id, username FROM users WHERE role = 'user' ORDER BY username ASC")->fetchAll();
                                    foreach ($users as $u) {
                                        $label = ($u['username'] == 'Pubblico') ? "⭐ TUTTI (Pubblico)" : $u['username'];
                                        echo "<option value='{$u['id']}'>{$label}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success btn-lg w-100 shadow-sm"><i class="bi bi-send-fill me-2"></i>Condividi File</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <div class="<?php echo $isAdmin ? 'col-lg-8' : 'col-12'; ?>">
                <div class="card shadow-sm overflow-hidden">
                    <div class="p-4 border-bottom bg-white d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0"><i class="bi bi-file-earmark-richtext-fill me-2 text-primary"></i>Documenti Condivisi</h5>
                        <?php if ($isAdmin): ?>
                            <a href="manage_users.php" class="btn btn-primary btn-sm rounded-pill"><i class="bi bi-people-fill me-1"></i>Gestione Utenze</a>
                        <?php endif; ?>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">File</th>
                                    <th>Destinatario</th>
                                    <th>Data Caricamento</th>
                                    <th class="text-end pe-4">Azione</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Recupero file condivisibili
                                // Se admin vede tutto, se user vede solo i suoi e i pubblici
                                if ($isAdmin) {
                                    $sql = "SELECT f.*, u.username as target_name 
                                            FROM shared_files f 
                                            LEFT JOIN users u ON f.target_user_id = u.id 
                                            ORDER BY upload_date DESC";
                                    $stmtFiles = $pdo->query($sql);
                                } else {
                                    $sql = "SELECT f.*, u.username as target_name 
                                            FROM shared_files f 
                                            LEFT JOIN users u ON f.target_user_id = u.id 
                                            WHERE f.target_user_id = ? OR u.username = 'Pubblico'
                                            ORDER BY upload_date DESC";
                                    $stmtFiles = $pdo->prepare($sql);
                                    $stmtFiles->execute([$_SESSION['user_id']]);
                                }
                                
                                $files = $stmtFiles->fetchAll();
                                if (empty($files)): ?>
                                    <tr><td colspan="4" class="text-center py-5 text-muted">Nessun file presente al momento.</td></tr>
                                <?php endif;
                                
                                foreach ($files as $f): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-file-earmark-binary fs-4 text-secondary me-2"></i>
                                            <div>
                                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($f['filename']); ?></div>
                                                <small class="text-muted">ID: #<?php echo $f['id']; ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo ($f['target_name'] == 'Pubblico') ? 'badge-pubblico' : 'bg-info-subtle text-info'; ?> rounded-pill px-3">
                                            <?php echo $f['target_name'] ?? 'Non assegnato'; ?>
                                        </span>
                                    </td>
                                    <td><small><?php echo date('d M Y, H:i', strtotime($f['upload_date'])); ?></small></td>
                                    <td class="text-end pe-4">
    <div class="btn-group">
        <a href="download.php?id=<?php echo $f['id']; ?>" class="btn btn-sm btn-outline-primary" title="Scarica">
            <i class="bi bi-download"></i>
        </a>

        <?php if ($isAdmin): ?>
            <a href="delete_file.php?id=<?php echo $f['id']; ?>" 
               class="btn btn-sm btn-outline-danger" 
               onclick="return confirm('Vuoi eliminare definitivamente questo file?')" 
               title="Elimina">
                <i class="bi bi-trash"></i>
            </a>
        <?php endif; ?>
    </div>
</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<?php include 'ai_assistant.php'; ?>
</body>
</html>