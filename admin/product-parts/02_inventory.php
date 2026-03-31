<div class="form-row" style="display: flex; gap: 1rem;">
    <div class="form-group" style="flex: 1;"><label>Quantità (Inventario)</label><input type="number" class="form-control" name="quantity" value="<?php echo htmlspecialchars($prodotto['quantity'] ?? ''); ?>"></div>
    <div class="form-group" style="flex: 1;"><label>SKU (Codice Univoco)</label><input type="text" class="form-control" name="sku" value="<?php echo htmlspecialchars($prodotto['sku'] ?? ''); ?>"></div>
</div>