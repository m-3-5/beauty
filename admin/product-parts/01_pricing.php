<div class="form-row" style="display: flex; gap: 1rem;">
    <div class="form-group" style="flex: 1;"><label>Prezzo di Listino</label><input type="number" step="0.01" class="form-control" name="old_price" value="<?php echo htmlspecialchars($prodotto['old_price'] ?? ''); ?>"></div>
    <div class="form-group" style="flex: 1;"><label>Prezzo di Vendita *</label><input type="number" step="0.01" class="form-control" name="price" value="<?php echo htmlspecialchars($prodotto['price'] ?? ''); ?>" required></div>
</div>