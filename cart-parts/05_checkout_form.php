<div class="checkout-main">
    <form action="order_actions.php" method="POST" id="checkout-form">
        <div class="checkout-step">
            <h3>1. Dati di Spedizione</h3>
            <div class="form-group"><label>Nome e Cognome *</label><input type="text" name="customer_name" class="form-control" required></div>
            <div class="form-group"><label>Email *</label><input type="email" name="customer_email" class="form-control" required></div>
            <div class="form-group"><label>Indirizzo (Via, numero civico) *</label><input type="text" name="shipping_address" class="form-control" required></div>
            <div class="form-group"><label>Città *</label><input type="text" name="shipping_city" class="form-control" required></div>
            <div class="form-group"><label>Numero di Telefono *</label><input type="tel" name="customer_phone" class="form-control" required></div>
        </div>
    </form>
    <div class="checkout-step" style="margin-top: 2rem;">
        <h3>Riepilogo Prodotti</h3>
        <?php include __DIR__ . '/01_item_list.php'; ?>
    </div>
</div>

<div class="cart-sidebar">
    <div class="checkout-step">
        <h3>2. Calcola Spedizione</h3>
        <form method="POST" action="carrello.php" id="shipping-form">
            <input type="hidden" name="action" value="update_shipping">
            <div style="display: flex; gap: 1rem;">
                <input type="text" name="postcode" placeholder="Il tuo CAP" value="<?php echo htmlspecialchars($customer_postcode); ?>" required class="form-control">
                <button type="submit" form="shipping-form" class="btn-secondary">Calcola</button>
            </div>
        </form>
    </div>

    <?php if ($shipping_cost !== null): ?>
        <div class="cart-summary checkout-step">
            <h3>3. Riepilogo Finale</h3>
            <div class="cart-total-row"><span>Subtotale:</span><span>€ <?php echo number_format($subtotal, 2, ',', '.'); ?></span></div>
            <div class="cart-total-row"><span>Spedizione:</span><span>€ <?php echo number_format($shipping_cost, 2, ',', '.'); ?></span></div>
            <?php if (($settings['order_enable_tip'] ?? 0) == 1): ?>
            <form method="POST" action="carrello.php" id="tip-form" style="margin:0;">
                <input type="hidden" name="action" value="update_tip">
                <input type="hidden" name="postcode" value="<?php echo htmlspecialchars($customer_postcode); ?>">
                <div class="cart-total-row">
                    <label for="tip">Mancia (opzionale):</label>
                    <input type="number" step="0.50" min="0" name="tip" id="tip" value="<?php echo $tip > 0 ? number_format($tip, 2, '.', '') : ''; ?>" placeholder="€ 0.00" onchange="this.form.submit();" style="width: 80px; text-align: right;">
                </div>
            </form>
            <?php endif; ?>
            <hr>
            <h2><span>Totale:</span><span>€ <?php echo number_format($total, 2, ',', '.'); ?></span></h2>
            
            <?php if (!$min_order_check): ?>
                <div class="error-box">L'importo minimo per ordinare è di € <?php echo number_format(floatval($settings['order_minimum_value']), 2, ',', '.'); ?></div>
            <?php else: ?>
                <div class="checkout-buttons">
                    <h3>4. Paga Ora</h3>
                    <button type="submit" form="checkout-form" name="action" value="place_order_paypal" class="btn-primary" style="background-color: #0070ba; width:100%;">Paga con PayPal</button>
                    <?php if ($show_cod_option): 
                        $cod_fee = floatval($settings['payment_cod_fee'] ?? 0);
                        $total_with_cod = $total + $cod_fee;
                    ?>
                        <button type="submit" form="checkout-form" name="action" value="place_order_cod" class="btn-secondary" style="width:100%;">
                            Paga alla Consegna
                            <small style="display: block;">(Totale: € <?php echo number_format($total_with_cod, 2, ',', '.'); ?>)</small>
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>