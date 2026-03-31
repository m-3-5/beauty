<?php
require 'includes/header.php';

// Recupera tutti gli ordini, i più recenti prima
$result = $db->query("SELECT * FROM orders ORDER BY order_date DESC");
?>

<div class="container">
    <h1>Gestione Ordini</h1>
    <p>Qui trovi la lista di tutti gli ordini ricevuti dal tuo sito e-commerce.</p>

    <table>
        <thead>
            <tr>
                <th>ID Ordine</th>
                <th>Data</th>
                <th>Cliente</th>
                <th>Totale</th>
                <th>Stato</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($order = $result->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo $order['id']; ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></td>
                    <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                    <td><strong>€ <?php echo number_format($order['total_amount'], 2, ',', '.'); ?></strong></td>
                    <td>
                        <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $order['status'])); ?>">
                            <?php echo htmlspecialchars($order['status']); ?>
                        </span>
                    </td>
                    <td class="actions">
                        <a href="ordine_dettaglio.php?id=<?php echo $order['id']; ?>" class="btn btn-primary">Vedi Dettagli</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Nessun ordine ricevuto finora.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
/* Stili per gli "status badge" */
.status-badge { padding: 0.3rem 0.6rem; border-radius: 15px; color: white; font-size: 0.8rem; font-weight: bold; }
.status-in-attesa { background-color: #ffc107; color: #333; }
.status-in-lavorazione { background-color: #17a2b8; }
.status-spedito { background-color: #007bff; }
.status-completato { background-color: #28a745; }
.status-annullato { background-color: #dc3545; }
</style>

<?php require 'includes/footer.php'; ?>