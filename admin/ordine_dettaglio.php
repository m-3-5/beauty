<?php
require 'includes/header.php';
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) { exit('Ordine non valido.'); }
$order_id = (int)$_GET['id'];

// Carica i dati dell'ordine
$stmt_order = $db->prepare("SELECT * FROM orders WHERE id = ?");
$stmt_order->bind_param("i", $order_id);
$stmt_order->execute();
$order = $stmt_order->get_result()->fetch_assoc();

// Carica i prodotti dell'ordine
$stmt_items = $db->prepare("SELECT oi.*, p.name as product_name FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$order_items = $stmt_items->get_result();

if (!$order) { exit('Ordine non trovato.'); }

$stati_ordine = ['In attesa', 'In lavorazione', 'Spedito', 'Completato', 'Annullato'];
?>
<div class="container">
    <h1>Dettaglio Ordine #<?php echo $order['id']; ?></h1>
    
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        <div>
            <div class="checkout-step">
                <h3>Prodotti Ordinati</h3>
                <table>
                    <thead><tr><th>Prodotto</th><th>Quantità</th><th>Prezzo Unit.</th><th>Subtotale</th></tr></thead>
                    <tbody>
                        <?php while($item = $order_items->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_name'] ?? 'Prodotto cancellato'); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>€ <?php echo number_format($item['price'], 2, ',', '.'); ?></td>
                            <td>€ <?php echo number_format($item['price'] * $item['quantity'], 2, ',', '.'); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <div class="checkout-step" style="margin-top: 2rem;">
                <h3>Dati di Spedizione Cliente</h3>
                <p><strong>Nome:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
                <p><strong>Telefono:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
                <p><strong>Indirizzo:</strong> <?php echo htmlspecialchars($order['shipping_address']); ?>, <?php echo htmlspecialchars($order['shipping_postcode']); ?> <?php echo htmlspecialchars($order['shipping_city']); ?></p>
            </div>
        </div>
        <div>
            <div class="checkout-step">
                <h3>Stato e Riepilogo</h3>
                <p><strong>Data Ordine:</strong> <?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></p>
                <p><strong>Totale Ordine:</strong> <strong style="font-size: 1.5rem;">€ <?php echo number_format($order['total_amount'], 2, ',', '.'); ?></strong></p>
                <hr>
                <form action="ordine_actions.php" method="POST">
                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                    <div class="form-group">
                        <label for="status"><strong>Aggiorna Stato Ordine</strong></label>
                        <select name="status" id="status" class="form-control">
                            <?php foreach ($stati_ordine as $stato): ?>
                                <option value="<?php echo $stato; ?>" <?php if ($order['status'] == $stato) echo 'selected'; ?>>
                                    <?php echo $stato; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" name="action" value="update_status" class="btn btn-primary" style="width: 100%;">Aggiorna Stato</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require 'includes/footer.php'; ?>