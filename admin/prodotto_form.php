<?php
require 'includes/header.php';
$prodotto = null; $pageTitle = "Nuovo Prodotto";
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $db->prepare("SELECT * FROM products WHERE id = ?"); $stmt->bind_param("i", $id); $stmt->execute();
    $prodotto = $stmt->get_result()->fetch_assoc();
    if ($prodotto) { $pageTitle = "Modifica: " . htmlspecialchars($prodotto['name']); }
}
?>
<style>.form-control { width: 100%; padding: 0.75rem; border: 1px solid #ced4da; border-radius: 0.25rem; }</style>
<div class="container">
    <h1><?php echo $pageTitle; ?></h1>
    <form action="prodotto_actions.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="<?php echo $prodotto ? 'update' : 'create'; ?>">
        <?php if ($prodotto): ?><input type="hidden" name="id" value="<?php echo $prodotto['id']; ?>"><?php endif; ?>

        <div style="background: white; padding: 2rem; border-radius: 8px;">
            <div class="form-group"><label>Nome Prodotto *</label><input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($prodotto['name'] ?? ''); ?>" required></div>
            <?php
            // Carica dinamicamente i moduli del prodotto
            $product_parts = glob(__DIR__ . '/product-parts/*.php');
            sort($product_parts);
            foreach ($product_parts as $part) {
                include $part;
            }
            ?>
        </div>
        <div class="text-right" style="margin-top: 2rem;"><a href="prodotti.php" class="btn btn-secondary">Annulla</a><button type="submit" class="btn btn-primary">Salva Prodotto</button></div>
    </form>
</div>
<?php require 'includes/footer.php'; ?>