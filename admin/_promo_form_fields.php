<?php // /gestionale/admin/_promo_form_fields.php ?>
<div class="form-group">
    <label>Titolo</label>
    <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($promo['title'] ?? ''); ?>" required>
</div>
<div class="form-group">
    <label>Descrizione</label>
    <textarea class="form-control" name="description" rows="3"><?php echo htmlspecialchars($promo['description'] ?? ''); ?></textarea>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Prezzo per Pagamento Diretto</label>
            <input type="number" step="0.01" class="form-control" name="price" value="<?php echo htmlspecialchars($promo['price'] ?? ''); ?>">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Etichetta (es. Offerta, Novità)</label>
            <input type="text" class="form-control" name="label_text" value="<?php echo htmlspecialchars($promo['label_text'] ?? ''); ?>">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Link WhatsApp</label>
            <input type="url" class="form-control" name="whatsapp_link" value="<?php echo htmlspecialchars($promo['whatsapp_link'] ?? ''); ?>">
        </div>
    </div>
</div>
<div class="form-group">
    <label>Immagine</label>
    <input type="file" class="form-control-file" name="image">
    <?php if (!empty($promo['image_url'])): ?>
        <br><img src="../<?php echo htmlspecialchars($promo['image_url']); ?>" alt="Immagine attuale" style="max-width: 150px; margin-top: 10px;">
        <input type="hidden" name="current_image_url" value="<?php echo $promo['image_url']; ?>">
    <?php endif; ?>
</div>
<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" <?php echo (isset($promo['is_active']) && $promo['is_active'] == 1) || !isset($promo) ? 'checked' : ''; ?>>
    <label class="form-check-label" for="is_active">
        Promozione Attiva
    </label>
</div>