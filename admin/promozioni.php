<?php
// /gestionale/admin/promozioni.php (VERSIONE DEFINITIVA CON MODALITÀ MODIFICA)
require 'includes/header.php';
require_once __DIR__ . '/../includes/functions.php';

// --- NUOVA LOGICA PER LA CANCELLAZIONE ---
if (isset($_GET['delete']) && filter_var($_GET['delete'], FILTER_VALIDATE_INT)) {
    $delete_id = (int)$_GET['delete'];
    
    // 1. Prima recuperiamo il percorso dell'immagine per cancellarla dal server
    $stmt = $db->prepare("SELECT image_url FROM promotions WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($promo_to_delete = $result->fetch_assoc()) {
        $image_path_to_delete = __DIR__ . '/../' . $promo_to_delete['image_url'];
        if (file_exists($image_path_to_delete)) {
            unlink($image_path_to_delete); // Cancella il file immagine
        }
    }
    
    // 2. Ora cancelliamo la riga dal database
    $stmt = $db->prepare("DELETE FROM promotions WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    
    // 3. Reindirizziamo per vedere la lista aggiornata
    header("Location: promozioni.php");
    exit();
}
// --- FINE LOGICA DI CANCELLAZIONE ---

// --- LOGICA DI SALVATAGGIO (invariata ma completa) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image_url = $_POST['current_image_url'] ?? '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $new_image_path = uploadImage($_FILES['image'], 'promo', 1200, 85);
        if ($new_image_path) {
            $image_url = $new_image_path;
        }
    }
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = !empty($_POST['price']) ? $_POST['price'] : null;
    $label_text = $_POST['label_text'];
    $whatsapp_link = $_POST['whatsapp_link'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = $_POST['id'];
        $stmt = $db->prepare("UPDATE promotions SET title=?, description=?, price=?, label_text=?, whatsapp_link=?, image_url=?, is_active=? WHERE id=?");
        $stmt->bind_param("ssdsssii", $title, $description, $price, $label_text, $whatsapp_link, $image_url, $is_active, $id);
    } else {
        $stmt = $db->prepare("INSERT INTO promotions (title, description, price, label_text, whatsapp_link, image_url, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdsssi", $title, $description, $price, $label_text, $whatsapp_link, $image_url, $is_active);
    }
    $stmt->execute();
    header("Location: promozioni.php");
    exit();
}

// --- LOGICA DI VISUALIZZAZIONE A DUE MODALITÀ ---
$promo_id = $_GET['edit'] ?? null;
$promo = null;
if ($promo_id) {
    // Siamo in modalità MODIFICA, carichiamo i dati della singola promozione
    $stmt = $db->prepare("SELECT * FROM promotions WHERE id = ?");
    $stmt->bind_param("i", $promo_id);
    $stmt->execute();
    $promo = $stmt->get_result()->fetch_assoc();
} else {
    // Siamo in modalità LISTA, carichiamo tutte le promozioni
    $promotions_result = $db->query("SELECT * FROM promotions ORDER BY created_at DESC");
}
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.min.css" rel="stylesheet">

<div class="container-fluid">
    
    <?php if ($promo_id && $promo): // --- SE SIAMO IN MODALITÀ MODIFICA --- ?>

        <h1 class="h3 mb-4 text-gray-800">Modifica Promozione</h1>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Dettagli Promozione: <?php echo htmlspecialchars($promo['title']); ?></h6>
            </div>
            <div class="card-body">
                <form action="promozioni.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $promo['id']; ?>">
                    <?php include '_promo_form_fields.php'; ?>
                    <hr>
                    <button type="submit" class="btn btn-primary">Salva Modifiche</button>
                    <a href="promozioni.php" class="btn btn-secondary">Annulla</a>
                </form>
            </div>
        </div>

    <?php else: // --- SE SIAMO IN MODALITÀ LISTA/CREA --- ?>

        <h1 class="h3 mb-4 text-gray-800">Gestione Promozioni</h1>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Crea Nuova Promozione</h6>
            </div>
            <div class="card-body">
                <form action="promozioni.php" method="POST" enctype="multipart/form-data">
                    <?php include '_promo_form_fields.php'; ?>
                    <hr>
                    <button type="submit" class="btn btn-success">Crea Promozione</button>
                </form>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Elenco Promozioni</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Titolo</th>
                                <th>Prezzo</th>
                                <th>Stato</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($p = $promotions_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($p['title']); ?></td>
                                <td><?php echo $p['price'] ? number_format($p['price'], 2, ',', '.') . ' €' : 'N/D'; ?></td>
                                <td><?php echo $p['is_active'] ? '<span class="badge badge-success">Attiva</span>' : '<span class="badge badge-secondary">Non Attiva</span>'; ?></td>
                                <td>
                                    <a href="promozioni.php?edit=<?php echo $p['id']; ?>" class="btn btn-warning btn-sm">Modifica</a>
									<a href="promozioni.php?delete=<?php echo $p['id']; ?>" 
       class="btn btn-danger btn-sm" 
       onclick="return confirm('Sei sicuro di voler cancellare questa promozione? L\'azione è irreversibile.');">
       Cancella
    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php endif; ?>
</div>

<?php require 'includes/footer.php'; ?>