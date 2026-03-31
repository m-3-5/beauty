<?php
require 'includes/header.php';
// Chiudiamo il PHP per iniziare a scrivere l'HTML del box di condivisione
?>

<div class="container">
    <div style="background: #eef6ff; border: 1px solid #b8d6fb; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
        <h3><i class="fas fa-share-alt"></i> Condividi il Catalogo Prodotti</h3>
        <p style="margin-bottom: 1rem;">Copia e incolla questo codice in una pagina di un altro sito web per mostrare l'intero catalogo dei tuoi prodotti.</p>
        <textarea readonly style="width: 100%; height: 110px; padding: 0.5rem; font-family: monospace; resize: none; border: 1px solid #ccc; border-radius: 4px; background: #f8f9fa;" onclick="this.select();"><?php 
            // Apriamo un piccolo blocco PHP solo per generare il codice dell'iframe
            $share_url = 'https://' . $_SERVER['HTTP_HOST'] . '/gestionale/prodotti.php';
            echo '<iframe 
    src="' . $share_url . '" 
    style="width:100%; height:800px; border:none;" 
    title="Catalogo Prodotti">
</iframe>';
        ?></textarea>
    </div>

<?php 
// Riapriamo il PHP per la logica di recupero dei prodotti
$result = $db->query("SELECT id, name, price, old_price, sku, quantity FROM products ORDER BY id DESC");
?>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h1>Gestione Prodotti</h1>
        <a href="prodotto_form.php" class="btn btn-primary">Aggiungi Prodotto</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Prezzo</th>
                <th>SKU</th>
                <th>Quantità</th>
                <th style="width: 280px;">Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td>
                        <?php if (!empty($row['old_price']) && $row['old_price'] > 0): ?>
                            <del style="color: #999;">€ <?php echo number_format($row['old_price'], 2, ',', '.'); ?></del>
                        <?php endif; ?>
                        <strong style="color: #28a745;">€ <?php echo number_format($row['price'], 2, ',', '.'); ?></strong>
                    </td>
                    <td><?php echo htmlspecialchars($row['sku']); ?></td>
                    <td><?php echo $row['quantity'] ?? 'N/D'; ?></td>
                    <td class="actions">
                        <a href="../prodotto.php?id=<?php echo $row['id']; ?>" target="_blank" class="btn btn-info">Visualizza</a>
                        <a href="prodotto_form.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary">Modifica</a>
                        <a href="prodotto_actions.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Sei sicuro di voler eliminare questo prodotto?');">Elimina</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Nessun prodotto trovato. Inizia aggiungendone uno.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
require 'includes/footer.php';
?>