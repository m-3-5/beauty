<?php
require 'includes/header.php';

// Recupero i clienti dal database
$result = $db->query("SELECT id, name, email, phone FROM customers ORDER BY id DESC");
?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Gestione Clienti (CRM)</h1>
        <a href="cliente_form.php" class="btn btn-primary">Aggiungi Cliente</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefono</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td class="actions">
                        <a href="cliente_form.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary">Modifica</a>
                        <a href="cliente_actions.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Sei sicuro di voler eliminare questo cliente?');">Elimina</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Nessun cliente trovato.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
require 'includes/footer.php';
?>