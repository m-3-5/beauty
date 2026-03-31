<?php
require 'includes/header.php';
$servizio = null;
$pageTitle = "Aggiungi Nuovo Servizio";
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $db->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $servizio = $result->fetch_assoc();
    if ($servizio) { $pageTitle = "Modifica Servizio: " . htmlspecialchars($servizio['name']); }
}
?>
<style>.form-control { width: 100%; padding: 0.75rem; border: 1px solid #ced4da; border-radius: 0.25rem; }</style>
<div class="container">
    <h1><?php echo $pageTitle; ?></h1>
    <form action="servizio_actions.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="<?php echo $servizio ? 'update' : 'create'; ?>">
        <?php if ($servizio): ?><input type="hidden" name="id" value="<?php echo $servizio['id']; ?>"><?php endif; ?>
        
        <div class="form-group"><label>Nome Servizio *</label><input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($servizio['name'] ?? ''); ?>" required></div>
        <div class="form-group"><label>Descrizione</label><textarea class="form-control" name="description" rows="4"><?php echo htmlspecialchars($servizio['description'] ?? ''); ?></textarea></div>
        <div class="form-group"><label>Immagine Rappresentativa</label><input type="file" class="form-control" name="image_url"><?php if (!empty($servizio['image_url'])): ?><p style="margin-top: 1rem;">Immagine attuale: <br><img src="<?php echo htmlspecialchars($servizio['image_url']); ?>" style="max-width: 200px;"></p><?php endif; ?></div>
        <div style="display: flex; gap: 1rem;">
            <div class="form-group" style="flex:1;"><label>Prezzo *</label><input type="number" step="0.01" class="form-control" name="price" value="<?php echo htmlspecialchars($servizio['price'] ?? ''); ?>" required></div>
            <div class="form-group" style="flex:1;"><label>Tipo Prezzo</label><input type="text" class="form-control" name="price_type" value="<?php echo htmlspecialchars($servizio['price_type'] ?? ''); ?>" placeholder="Es: all'ora"></div>
            <div class="form-group" style="flex:1;"><label>Durata</label><input type="text" class="form-control" name="duration" value="<?php echo htmlspecialchars($servizio['duration'] ?? ''); ?>" placeholder="Es: 60 min"></div>
        </div>
        <div class="form-group"><label>Luogo</label><input type="text" class="form-control" name="location" value="<?php echo htmlspecialchars($servizio['location'] ?? ''); ?>" placeholder="Es: in sede"></div>

        <div class="text-right" style="margin-top: 2rem;"><a href="servizi.php" class="btn btn-secondary">Annulla</a><button type="submit" class="btn btn-primary">Salva Servizio</button></div>
    </form>
</div>
<?php require 'includes/footer.php'; ?>