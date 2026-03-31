<?php
require 'includes/header.php';

// --- LOGICA DI CALCOLO COMPLETA ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_shipping') {
    if (isset($_POST['postcode'])) { $_SESSION['customer_postcode'] = trim($_POST['postcode']); }
    $_SESSION['tip'] = 0;
}
$customer_postcode = $_SESSION['customer_postcode'] ?? '';
$tip = $_SESSION['tip'] ?? 0;
$cart_items_details = [];
$subtotal = 0;
$shipping_cost = null;
if (!empty($_SESSION['cart'])) {
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
            $item_subtotal = $product['price'] * $quantity;
            $subtotal += $item_subtotal;
            $cart_items_details[] = ['id' => $product['id'], 'name' => $product['name'], 'price' => $product['price'], 'image_url' => $product['image_url'], 'quantity' => $quantity, 'subtotal' => $item_subtotal];
        }
    }
}
if ($customer_postcode != '') {
    $local_postcodes = array_map('trim', explode(',', $settings['shipping_local_postcodes'] ?? ''));
    if (in_array($customer_postcode, $local_postcodes)) {
        $shipping_cost = ($subtotal >= floatval($settings['shipping_local_free_threshold'])) ? 0 : floatval($settings['shipping_local_cost']);
    } else {
        $shipping_cost = ($subtotal >= floatval($settings['shipping_national_free_threshold'])) ? 0 : floatval($settings['shipping_national_cost']);
    }
}
$min_order_check = ($settings['order_minimum_value'] == 0) || ($subtotal >= floatval($settings['order_minimum_value']));
$total = $subtotal + ($shipping_cost ?? 0) + $tip;
?>

<section class="content-section">
    <h2 class="section-title">Il Tuo Carrello</h2>
    <div class="section-divider"></div>

    <?php if (empty($cart_items_details)): ?>
        <?php include 'cart-parts/04_empty_cart.php'; ?>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: flex-start;">
            <div class="cart-items-container">
                <?php include 'cart-parts/01_item_list.php'; ?>
            </div>
            
            <div class="cart-sidebar">
                <?php include 'cart-parts/02_shipping_calculator.php'; ?>

                <?php if ($shipping_cost !== null): ?>
                    <div class="cart-summary checkout-step">
                        <h3>Riepilogo</h3>
                        <div class="cart-total-row"><span>Subtotale:</span><span>€ <?php echo number_format($subtotal, 2, ',', '.'); ?></span></div>
                        <div class="cart-total-row"><span>Spedizione:</span><span>€ <?php echo number_format($shipping_cost, 2, ',', '.'); ?></span></div>
                        
                        <form method="POST" action="carrello.php" id="tip-form">
                             <input type="hidden" name="postcode" value="<?php echo htmlspecialchars($customer_postcode); ?>">
                            <div class="cart-total-row">
                                <label for="tip">Mancia (opzionale):</label>
                                <input type="number" step="0.50" min="0" name="tip" id="tip" value="<?php echo $tip > 0 ? number_format($tip, 2, '.', '') : ''; ?>" placeholder="€ 0.00" onchange="this.form.submit();" style="width: 80px; text-align: right;">
                            </div>
                        </form>
                        <hr>
                        <h2><span>Totale:</span><span>€ <?php echo number_format($total, 2, ',', '.'); ?></span></h2>
                        
                        <?php if (!$min_order_check): ?>
                            <div class="error-box">L'importo minimo per ordinare è di € <?php echo number_format(floatval($settings['order_minimum_value']), 2, ',', '.'); ?></div>
                        <?php else: ?>
                            <div class="checkout-buttons">
                                <a href="checkout.php" class="btn-primary" style="width: 100%; text-align: center;">Procedi al Checkout</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php require 'includes/footer.php'; ?>