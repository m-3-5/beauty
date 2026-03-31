<div style="display: flex; gap: 1.5rem;">
    <div class="form-group"><label>Colore Primario</label><input type="color" name="public_color_primary" value="<?php echo htmlspecialchars($settings['public_color_primary'] ?? '#007bff'); ?>"></div>
    <div class="form-group"><label>Colore Testo</label><input type="color" name="public_color_text" value="<?php echo htmlspecialchars($settings['public_color_text'] ?? '#333333'); ?>"></div>
    <div class="form-group"><label>Colore Sfondo</label><input type="color" name="public_color_background" value="<?php echo htmlspecialchars($settings['public_color_background'] ?? '#f9f9f9'); ?>"></div>
</div>