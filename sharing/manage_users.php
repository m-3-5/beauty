<?php
/**
 * Beauty Sharing - Gestione Utenze
 * Riservato all'Amministratore
 */
require_once 'db.php';

// 1. Protezione Accesso: Solo Admin può visualizzare
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$message = '';

// 2. Logica: Aggiunta nuovo utente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $new_user = trim($_POST['new_username']);
    $new_pass = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
    $new_role = $_POST['role'];

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$new_user, $new_pass, $new_role]);
        $message = "<div class='alert alert-success'>Utente <strong>$new_user</strong> creato con successo!</div>";
    } catch (PDOException $e) {
        $message = "<div class='alert alert-danger'>Errore: " . ($e->getCode() == 23000 ? "Username già esistente" : $e->getMessage()) . "</div>";
    }
}

// 3. Logica: Eliminazione utente (opzionale ma utile)
if (isset($_GET['delete'])) {
    $id_to_delete = (int)$_GET['delete'];
    // Evitiamo che Pasquale si cancelli da solo
    if ($id_to_delete !== $_SESSION['user_id']) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id_to_delete]);
        header("Location: manage_users.php?msg=deleted");
        exit;
    }
}

// 4. Recupero lista utenti dal database beauty_sharing
$users = $pdo->query("SELECT id, username, role, created_at FROM users ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Utenti - Beauty Sharing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="bi bi-arrow-left me-2"></i>Torna alla Dashboard</a>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-4">Crea Nuovo Utente</h5>
                    <?php echo $message; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="new_username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ruolo</label>
                            <select name="role" class="form-select">
                                <option value="user">Cliente (User)</option>
                                <option value="admin">Amministratore (Admin)</option>
                            </select>
                        </div>
                        <button type="submit" name="add_user" class="btn btn-primary w-100">Salva Utente</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-4">Utenze Attive</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Username</th>
                                    <th>Ruolo</th>
                                    <th>Creato il</th>
                                    <th class="text-end">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $u): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($u['username']); ?></strong></td>
                                    <td>
                                        <span class="badge <?php echo $u['role'] == 'admin' ? 'bg-danger' : 'bg-primary'; ?>">
                                            <?php echo strtoupper($u['role']); ?>
                                        </span>
                                    </td>
                                    <td><small><?php echo date('d/m/Y', strtotime($u['created_at'])); ?></small></td>
                                    <td class="text-end">
                                        <?php if ($u['id'] !== $_SESSION['user_id'] && $u['username'] !== 'Pubblico'): ?>
                                            <a href="?delete=<?php echo $u['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Sei sicuro di voler eliminare questo utente?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        <?php endif; ?>
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
</div>

</body>
</html>