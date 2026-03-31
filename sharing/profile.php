<?php
/**
 * Beauty Sharing - Gestione Profilo
 * Permette agli utenti di aggiornare i propri dati
 */
require_once 'db.php';

// Protezione: Solo utenti loggati
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$userId = $_SESSION['user_id'];
$message = '';

// Logica di aggiornamento
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $newUsername = trim($_POST['username']);
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    try {
        // 1. Aggiornamento Username
        $stmt = $pdo->prepare("UPDATE users SET username = ? WHERE id = ?");
        $stmt->execute([$newUsername, $userId]);
        $_SESSION['username'] = $newUsername; // Aggiorna la sessione

        // 2. Aggiornamento Password (se compilata)
        if (!empty($newPassword)) {
            if ($newPassword === $confirmPassword) {
                $hashedPass = password_hash($newPassword, PASSWORD_BCRYPT);
                $stmtPass = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmtPass->execute([$hashedPass, $userId]);
                $message = "<div class='alert alert-success'>Profilo e password aggiornati!</div>";
            } else {
                $message = "<div class='alert alert-danger'>Le password non coincidono.</div>";
            }
        } else {
            $message = "<div class='alert alert-success'>Username aggiornato con successo!</div>";
        }
    } catch (PDOException $e) {
        $message = "<div class='alert alert-danger'>Errore: Username probabilmente già in uso.</div>";
    }
}

// Recupero dati attuali
$stmtUser = $pdo->prepare("SELECT username, role, created_at FROM users WHERE id = ?");
$stmtUser->execute([$userId]);
$userData = $stmtUser->fetch();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mio Profilo - Beauty Sharing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary mb-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-arrow-left me-2"></i>Torna alla Dashboard</a>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4"><i class="bi bi-person-circle me-2 text-primary"></i>Il Mio Profilo</h4>
                    <?php echo $message; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase">Username attuale</label>
                            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($userData['username']); ?>" required>
                        </div>
                        
                        <div class="mb-3 text-muted">
                            <small>Ruolo: <strong><?php echo strtoupper($userData['role']); ?></strong></small><br>
                            <small>Account creato il: <?php echo date('d/m/Y', strtotime($userData['created_at'])); ?></small>
                        </div>
                        
                        <hr class="my-4">
                        <h6 class="fw-bold mb-3">Cambia Password (lascia vuoto per non modificare)</h6>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase">Nuova Password</label>
                            <input type="password" name="new_password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase">Conferma Nuova Password</label>
                            <input type="password" name="confirm_password" class="form-control">
                        </div>
                        
                        <button type="submit" name="update_profile" class="btn btn-primary btn-lg w-100 mt-3 shadow-sm">Aggiorna Profilo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>