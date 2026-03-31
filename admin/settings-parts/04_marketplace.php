<div class="setting-row">
    <span class="setting-label">Attiva Sincronizzazione</span>
    <label class="switch"><input type="checkbox" name="marketplace_sync_enabled" value="1" <?php echo ($settings['marketplace_sync_enabled'] ?? 0) == 1 ? 'checked' : ''; ?>><span class="slider"></span></label>
</div>
<div class="form-group" style="margin-top: 1.5rem;"><label>API Key del Marketplace</label><input type="text" class="form-control" name="marketplace_api_key" value="<?php echo htmlspecialchars($settings['marketplace_api_key'] ?? ''); ?>"></div>
<div class="form-group"><label>ID Venditore del Marketplace</label><input type="text" class="form-control" name="marketplace_vendor_id" value="<?php echo htmlspecialchars($settings['marketplace_vendor_id'] ?? ''); ?>"></div>