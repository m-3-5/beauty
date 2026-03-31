<div class="cart-summary">
    <div class="cart-total-row"><span>Subtotale:</span><span>€ <?php echo number_format($subtotal, 2, ',', '.'); ?></span></div>
    <div class="cart-total-row"><span>Spedizione:</span><span>€ <?php echo number_format($shipping_cost, 2, ',', '.'); ?></span></div>
    <?php if (($settings['order_enable_tip'] ?? 0) == 1): ?>
    <form method="POST" action="carrello.php" id="tip-form">
         <input type="hidden" name="postcode" value="<?php echo htmlspecialchars($customer_postcode); ?>">
        <div class="cart-total-row">
            <label for="tip">Mancia (opzionale):</label>
            <input type="number" step="0.50" min="0" name="tip" id="tip" value="<?php echo $tip > 0 ? number_format($tip, 2, '.', '') : ''; ?>" placeholder="€ 0.00" onchange="this.form.submit();" style="width: 80px; text-align: right; border: 1px solid #ddd; border-radius: 4px; padding: 0.3rem;">
        </div>
    </form>
    <?php endif; ?>
    <hr>
    <h2><span>Totale:</span><span>€ <?php echo number_format($total, 2, ',', '.'); ?></span></h2>
    
    <?php if (!$min_order_check): ?>
        <div style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; text-align: center; margin-top: 1rem;">
            L'importo minimo per ordinare è di € <?php echo number_format(floatval($settings['order_minimum_value']), 2, ',', '.'); ?>
        </div>
    <?php else: ?>
        <div class="checkout-buttons" style="flex-direction: column; gap: 0.5rem;">
            <a href="prodotti.php" class="btn-secondary" style="background: transparent; border:1px solid #ddd; color: #555; text-align:center;">Continua Shopping</a>
            <?php if (!empty($settings['payment_paypal_email'])): ?>
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                    <input type="hidden" name="cmd" value="_xclick"><input type="hidden" name="business" value="<?php echo htmlspecialchars($settings['payment_paypal_email']); ?>"><input type="hidden" name="item_name" value="Ordine da <?php echo htmlspecialchars($settings['public_business_name']); ?>"><input type="hidden" name="amount" value="<?php echo $total; ?>"><input type="hidden" name="currency_code" value="EUR"><input type="hidden" name="no_shipping" value="1">
                    <button type="submit" class="btn-primary" style="background-color: #0070ba; width:100%;">Paga con PayPal</button>
                </form>
            <?php endif; ?>
            <?php if ($show_cod_option): 
                $cod_fee = floatval($settings['payment_cod_fee'] ?? 0);
                $total_with_cod = $total + $cod_fee;
            ?>
                <form action="order_actions.php" method="POST">
                    <input type="hidden" name="action" value="place_order_cod">
                    <button type="submit" class="btn-secondary" style="width:100%;">
                        Paga alla Consegna
                        <small style="display: block;">(Totale: € <?php echo number_format($total_with_cod, 2, ',', '.'); ?>)</small>
                    </button>
                </form>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>