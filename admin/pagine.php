<?php
require 'includes/header.php';
$pages_result = $db->query("SELECT id, page_slug, page_title, is_active FROM pages ORDER BY id");
?>
<div class="container">
    <h1>Gestione Pagine Sito Pubblico</h1>
    <p>Modifica i contenuti delle pagine principali e il loro stato di visibilità.</p>
    <table>
        <thead><tr><th>Nome Pagina</th><th>Stato</th><th>Azioni</th></tr></thead>
        <tbody>
            <?php while($page = $pages_result->fetch_assoc()): ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($page['page_title']); ?></strong></td>
                <td><?php echo $page['is_active'] ? '<span style="color: #28a745;">● Attiva</span>' : '<span style="color: #dc3545;">● Non Attiva</span>'; ?></td>
                <td class="actions">
    <?php
    // Costruisce l'URL pubblico corretto
    $page_url = ($page['page_slug'] == 'home') ? '../index.php' : '../' . $page['page_slug'] . '.php';
    ?>
    <a href="<?php echo $page_url; ?>" target="_blank" class="btn btn-info">Visualizza</a>
    <a href="pagina_editor.php?slug=<?php echo $page['page_slug']; ?>" class="btn btn-secondary">Modifica</a>
</td>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php require 'includes/footer.php'; ?>