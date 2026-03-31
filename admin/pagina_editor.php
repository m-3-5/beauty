<?php
require 'includes/header.php';
if (!isset($_GET['slug'])) { exit('Pagina non specificata.'); }
$slug = $_GET['slug'];

$stmt = $db->prepare("SELECT * FROM pages WHERE page_slug = ?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();
$page = $result->fetch_assoc();
if (!$page) { exit('Pagina non trovata.'); }
$content = json_decode($page['content'], true);
?>
<style>.form-control { width: 100%; padding: 0.75rem; border: 1px solid #ced4da; border-radius: 0.25rem; } .setting-row { display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid #f0f0f0; } .setting-label { font-weight: 500; } .switch { position: relative; display: inline-block; width: 60px; height: 34px; } .switch input { opacity: 0; width: 0; height: 0; } .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #dc3545; transition: .4s; border-radius: 34px; } .slider:before { position: absolute; content: ""; height: 26px; width: 26px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; } input:checked + .slider { background-color: #28a745; } input:checked + .slider:before { transform: translateX(26px); }</style>

<div class="container">
    <div style="display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;">
        <h1>Modifica Pagina: <?php echo htmlspecialchars($page['page_title']); ?></h1>
        <?php
        $page_url = ($page['page_slug'] == 'home') ? '../index.php' : '../' . $page['page_slug'] . '.php';
        ?>
        <a href="<?php echo $page_url; ?>" target="_blank" class="btn btn-info">Vedi Pagina Pubblica</a>
    </div>

    <form method="POST" action="pagina_actions.php" enctype="multipart/form-data">
        <input type="hidden" name="page_slug" value="<?php echo $slug; ?>">
        <div style="background: white; padding: 2rem; border-radius: 8px; margin-bottom: 2rem;">
            <h2>Stato Pagina</h2>
            <div class="setting-row">
                <span class="setting-label">Mostra questa pagina sul sito pubblico</span>
                <label class="switch"><input type="checkbox" name="is_active" value="1" <?php echo ($page['is_active'] ?? 0) == 1 ? 'checked' : ''; ?>><span class="slider"></span></label>
            </div>
        </div>
        <div style="background: white; padding: 2rem; border-radius: 8px;">
            <h2>Contenuti della Pagina</h2>
            <?php foreach ($content as $key => $value): $label = ucwords(str_replace('_', ' ', $key)); ?>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label><strong><?php echo htmlspecialchars($label); ?></strong></label>
                    <?php if (strpos($key, '_image_url') !== false): ?>
                        <input type="file" class="form-control" name="content[<?php echo $key; ?>]">
<?php if ($key === 'hero_image_url'): // Mostra il suggerimento solo per l'immagine hero ?>
    <small style="margin-top: 5px; display: block;">Consiglio: per un risultato ottimale, carica un'immagine grande e orizzontale (es. 1920x1080 pixel).</small>
<?php endif; ?>
                        <?php if (!empty($value)): ?><p style="margin-top: 1rem;">Immagine attuale: <br><img src="<?php echo htmlspecialchars($value); ?>" style="max-width: 200px;"></p><?php endif; ?>
                    <?php elseif (strlen(is_string($value) ? $value : "") > 100): ?>
                        <textarea class="form-control" name="content[<?php echo $key; ?>]" rows="5"><?php echo htmlspecialchars($value); ?></textarea>
                    <?php else: ?>
                        <input type="text" class="form-control" name="content[<?php echo $key; ?>]" value="<?php echo htmlspecialchars(is_scalar($value) ? $value : ''); ?>">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-right" style="margin-top: 2rem;"><a href="pagine.php" class="btn btn-secondary">Annulla</a><button type="submit" name="action" value="save_content" class="btn btn-primary">Salva Modifiche</button></div>
    </form>
</div>
<?php require 'includes/footer.php'; ?>