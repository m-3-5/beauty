<?php
require 'includes/header.php';

// Determina se stiamo modificando o creando
$cliente = null;
$pageTitle = "Aggiungi Nuovo Cliente";
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $db->prepare("SELECT * FROM customers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();
    if ($cliente) {
        $pageTitle = "Modifica Cliente: " . htmlspecialchars($cliente['name']);
    }
}
?>

<style>
    /* Stili per il form */
    .form-group { margin-bottom: 1.5rem; }
    .form-group label { display: block; font-weight: 500; margin-bottom: 0.5rem; }
    .form-group input, .form-group textarea { width: 100%; padding: 0.75rem; border: 1px solid #ced4da; border-radius: 0.25rem; box-sizing: border-box; }
    .form-group textarea { min-height: 120px; }
</style>

<div class="container">
    <h1><?php echo $pageTitle; ?></h1>

    <form action="cliente_actions.php" method="POST">
        <input type="hidden" name="action" value="<?php echo $cliente ? 'update' : 'create'; ?>">
        <?php if ($cliente): ?>
            <input type="hidden" name="id" value="<?php echo $cliente['id']; ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="name">Nome Completo *</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($cliente['name'] ?? ''); ?>" required>
        </div>
        
        <div class="form-row" style="display: flex; gap: 1.5rem;">
            <div class="form-group" style="flex: 1;">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($cliente['email'] ?? ''); ?>">
            </div>
            <div class="form-group" style="flex: 1;">
                <label for="phone">Telefono</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($cliente['phone'] ?? ''); ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="address">Indirizzo</label>
            <textarea id="address" name="address"><?php echo htmlspecialchars($cliente['address'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label for="notes">Note Interne</label>
            <textarea id="notes" name="notes" placeholder="Informazioni utili, preferenze, storico contatti..."><?php echo htmlspecialchars($cliente['notes'] ?? ''); ?></textarea>
        </div>

        <div class="text-right">
            <a href="clienti.php" class="btn btn-secondary">Annulla</a>
            <button type="submit" class="btn btn-primary">Salva Cliente</button>
        </div>
    </form>
</div>

<?php
require 'includes/footer.php';
?>