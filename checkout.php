<?php
require 'includes/header.php';

// --- INIZIO LOGICA DI CALCOLO COMPLETA (IL PEZZO MANCANTE) ---

// Se il carrello è vuoto, non si può essere qui, quindi reindirizziamo
if (empty($_SESSION['cart'])) {
    header('Location: carrello.php');
    exit();
}

// Recupera i dati dalla sessione
$customer_postcode = $_SESSION['customer_postcode'] ?? '';
$tip = $_SESSION['tip'] ?? 0;

// Inizializza le variabili
$cart_items_details = [];
$subtotal = 0;
$shipping_cost = null;
$show_cod_option = false;

// Recupera i dettagli dei prodotti e calcola il subtotale
$product_ids = array_keys($_SESSION['cart']);
if (!empty($product_ids)) {
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
    $types = str_repeat('i', count($product_ids));
    $stmt = $db->prepare("SELECT id, name, price, image_url FROM products WHERE id IN ($placeholders)");
    $stmt->bind_param($types, ...$product_ids);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($product = $result->fetch_assoc()) {
        $quantity = $_SESSION['cart'][$product['id']]['quantity'];
        $subtotal += $product['price'] * $quantity;
    }
}

// Calcola la spedizione (DEVE essere già stato inserito il CAP nella pagina carrello)
if ($customer_postcode != '') {
    $local_postcodes = array_map('trim', explode(',', $settings['shipping_local_postcodes'] ?? ''));
    if (in_array($customer_postcode, $local_postcodes)) {
        $shipping_cost = ($subtotal >= floatval($settings['shipping_local_free_threshold'])) ? 0 : floatval($settings['shipping_local_cost']);
        if (($settings['payment_cod_enabled'] ?? 0) == 1 && $customer_postcode == ($settings['business_postcode'] ?? '')) {
            $show_cod_option = true;
        }
    } else {
        $shipping_cost = ($subtotal >= floatval($settings['shipping_national_free_threshold'])) ? 0 : floatval($settings['shipping_national_cost']);
    }
} else {
    // Se per qualche motivo si arriva qui senza CAP, si torna al carrello
    header('Location: carrello.php');
    exit();
}

$min_order_check = ($settings['order_minimum_value'] == 0) || ($subtotal >= floatval($settings['order_minimum_value']));
$total = $subtotal + $shipping_cost + $tip;

// --- FINE LOGICA ---
?>

<section class="content-section">
    <h2 class="section-title">Dati di Consegna e Pagamento</h2>
    <div class="section-divider"></div>

    <form action="order_actions.php" method="POST" id="checkout-form">
        <div class="checkout-grid">
            <div class="checkout-main">
                <div class="checkout-step">
                    <h3>1. Inserisci i Tuoi Dati</h3>
                    <div class="form-group"><label>Nome e Cognome *</label><input type="text" name="customer_name" class="form-control" required></div>
                    <div class="form-group"><label>Email *</label><input type="email" name="customer_email" class="form-control" required></div>
                    <div class="form-group"><label>Indirizzo (Via, numero civico) *</label><input type="text" name="shipping_address" class="form-control" required></div>
                    <div class="form-group"><label>Città *</label><input type="text" name="shipping_city" class="form-control" required></div>
                    <div class="form-group"><label>Numero di Telefono *</label><input type="tel" name="customer_phone" class="form-control" required></div>
                </div>
            </div>

            <div class="cart-sidebar">
                <div class="cart-summary checkout-step">
                    <h3>Riepilogo Ordine</h3>
                    <div class="cart-total-row"><span>Subtotale:</span><span>€ <?php echo number_format($subtotal, 2, ',', '.'); ?></span></div>
                    <div class="cart-total-row"><span>Spedizione:</span><span>€ <?php echo number_format($shipping_cost, 2, ',', '.'); ?></span></div>
                    <?php if (($settings['order_enable_tip'] ?? 0) == 1): ?>
                        <div class="cart-total-row">
                            <label for="tip">Mancia (opzionale):</label>
                            <input type="number" step="0.50" min="0" name="tip" value="<?php echo $tip > 0 ? number_format($tip, 2, '.', '') : ''; ?>" placeholder="€ 0.00" form="checkout-form" style="width: 80px; text-align: right;">
                        </div>
                    <?php endif; ?>
                    <hr>
                    <h2><span>Totale:</span><span>€ <?php echo number_format($total, 2, ',', '.'); ?></span></h2>
                    
                    <h3>Scegli il Metodo di Pagamento</h3>
                    <div class="checkout-buttons">
                        <?php if (!empty($settings['payment_paypal_email'])): ?>
                            <button type="submit" name="action" value="place_order_paypal" class="btn-primary" style="background-color: #0070ba; width:100%;">Paga con PayPal</button>
                        <?php endif; ?>

                        <?php if ($show_cod_option): 
                            $cod_fee = floatval($settings['payment_cod_fee'] ?? 0);
                            $total_with_cod = $total + $cod_fee;
                        ?>
                            <button type="submit" name="action" value="place_order_cod" class="btn-secondary" style="width:100%;">
                                Paga alla Consegna
                                <small style="display: block;">(Totale: € <?php echo number_format($total_with_cod, 2, ',', '.'); ?>)</small>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>

<?php require 'includes/footer.php'; ?>