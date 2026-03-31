<?php
require 'includes/header.php';
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) { exit('ID blocco non valido.'); }
$section_id = (int)$_GET['id'];

$stmt = $db->prepare("SELECT * FROM page_sections WHERE id = ?");
$stmt->bind_param("i", $section_id);
$stmt->execute();
$result = $stmt->get_result();
$section = $result->fetch_assoc();
if (!$section) { exit('Blocco non trovato.'); }
$content = json_decode($section['content'], true);
?>
<style>.form-control { width: 100%; padding: 0.75rem; border: 1px solid #ced4da; border-radius: 0.25rem; }</style>

<div class="container">
    <h1>Modifica Blocco: <?php echo ucfirst(str_replace('_', ' ', $section['section_type'])); ?></h1>
    
    <form method="POST" action="block_actions.php" enctype="multipart/form-data">
        <input type="hidden" name="section_id" value="<?php echo $section['id']; ?>">
        <input type="hidden" name="page_slug" value="<?php echo $section['page_slug']; ?>">
        
        <div style="background: white; padding: 2rem; border-radius: 8px;">
            <?php foreach ($content as $key => $value): 
                $label = ucwords(str_replace('_', ' ', $key));
                // Se il valore è un array (es. la lista 'features'), non mostriamo un campo di input
                if (is_array($value)) { continue; }
            ?>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label><strong><?php echo htmlspecialchars($label); ?></strong></label>
                    <?php if (strpos($key, '_image_url') !== false): ?>
                        <input type="file" class="form-control" name="content[<?php echo $key; ?>]">
                        <?php if (!empty($value)): ?><p style="margin-top:1rem;">Immagine attuale: <br><img src="<?php echo htmlspecialchars($value); ?>" style="max-width:200px;"></p><?php endif; ?>
                    <?php elseif (strlen($value) > 100): ?>
                        <textarea class="form-control" name="content[<?php echo $key; ?>]" rows="5"><?php echo htmlspecialchars($value); ?></textarea>
                    <?php else: ?>
                        <input type="text" class="form-control" name="content[<?php echo $key; ?>]" value="<?php echo htmlspecialchars($value); ?>">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-right" style="margin-top: 2rem;">
            <a href="builder.php?slug=<?php echo $section['page_slug']; ?>" class="btn btn-secondary">Annulla</a>
            <button type="submit" name="action" value="save_block" class="btn btn-primary">Salva Modifiche Blocco</button>
        </div>
    </form>
</div>
<?php require 'includes/footer.php'; ?>