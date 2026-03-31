<<?php
require 'includes/header.php';
?>

<div class="container">
    <div style="background: #eef6ff; border: 1px solid #b8d6fb; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
        <h3><i class="fas fa-share-alt"></i> Condividi la Lista Servizi</h3>
        <p style="margin-bottom: 1rem;">Copia e incolla questo codice per mostrare la lista dei tuoi servizi su un altro sito web.</p>
        <textarea readonly style="width: 100%; height: 110px; padding: 0.5rem; font-family: monospace; resize: none; border: 1px solid #ccc; border-radius: 4px; background: #f8f9fa;" onclick="this.select();"><?php 
            $share_url = 'https://' . $_SERVER['HTTP_HOST'] . '/gestionale/servizi.php';
            echo '<iframe 
    src="' . $share_url . '" 
    style="width:100%; height:800px; border:none;" 
    title="Lista Servizi">
</iframe>';
        ?></textarea>
    </div>

<?php 
// Recupero i servizi dal database
$result = $db->query("SELECT id, name, price, price_type, duration FROM services ORDER BY id DESC");
?>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h1>Gestione Servizi</h1>
        <a href="servizio_form.php" class="btn btn-primary">Aggiungi Servizio</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Prezzo</th>
                <th>Tipo Prezzo</th>
                <th>Durata</th>
                <th style="width: 280px;">Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td>€ <?php echo number_format($row['price'], 2, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($row['price_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['duration']); ?></td>
                    <td class="actions">
                        <a href="../servizi.php#servizio-<?php echo $row['id']; ?>" target="_blank" class="btn btn-info">Visualizza</a>
                        <a href="servizio_form.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary">Modifica</a>
                        <a href="servizio_actions.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Sei sicuro di voler eliminare questo servizio?');">Elimina</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Nessun servizio trovato. Inizia aggiungendone uno.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
require 'includes/footer.php';
?>