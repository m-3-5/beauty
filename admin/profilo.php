<?php
// /gestionale/admin/profilo.php (VERSIONE CORRETTA E SICURA)

// 1. Includiamo l'header. L'header a sua volta include già config.php con il percorso corretto.
require_once 'includes/header.php';

// Inizializza le variabili per i messaggi all'utente
$success_message = '';
$error_message = '';

// Controlla se il form è stato inviato
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recupera i dati dal form
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Recupera l'ID dell'utente loggato dalla sessione
    $user_id = $_SESSION['user_id'] ?? 0;

    // Validazione dei dati
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error_message = 'Tutti i campi sono obbligatori.';
    } elseif ($new_password !== $confirm_password) {
        $error_message = 'La nuova password e la sua conferma non coincidono.';
    } elseif ($user_id > 0) {
        
        // Recupera l'hash della password attuale dal database
        $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        // Verifica che la password attuale sia corretta
        if ($user && password_verify($current_password, $user['password'])) {
            
            // Crea il nuovo hash per la nuova password
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

            // Prepara e esegui l'aggiornamento
            $update_stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update_stmt->bind_param("si", $new_password_hash, $user_id);

            if ($update_stmt->execute()) {
                $success_message = 'Password aggiornata con successo!';
            } else {
                $error_message = 'Si è verificato un errore durante l\'aggiornamento. Riprova.';
            }
            
        } else {
            $error_message = 'La password attuale inserita non è corretta.';
        }
    } else {
        $error_message = 'Sessione utente non valida. Effettua nuovamente il login.';
    }
}
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.min.css" rel="stylesheet">

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <h1 class="h3 mb-4 text-gray-800">Il Mio Profilo</h1>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cambia Password</h6>
                </div>
                <div class="card-body">

                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                    <?php endif; ?>

                    <form action="profilo.php" method="POST">
                        <div class="form-group">
                            <label for="current_password">Password Attuale</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="new_password">Nuova Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Conferma Nuova Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Salva Modifiche</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>