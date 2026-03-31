<?php
// /gestionale/includes/footer.php

// Recuperiamo i link social e P.IVA dalle impostazioni, se non già fatto
if (!isset($settings)) {
    $settings_result = $db->query("SELECT setting_key, setting_value FROM settings");
    $settings = [];
    while ($row = $settings_result->fetch_assoc()) { $settings[$row['setting_key']] = $row['setting_value']; }
}
$social_links = [
    'facebook' => $settings['social_facebook_url'] ?? '',
    'instagram' => $settings['social_instagram_url'] ?? '',
];
$p_iva = $settings['public_p_iva'] ?? '';
$business_name = $settings['public_business_name'] ?? 'La Mia Attività';
?>
</main>

<footer class="footer">
    <div class="social-icons">
         <?php if (!empty($social_links['instagram'])): ?>
            <a href="<?php echo htmlspecialchars($social_links['instagram']); ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
        <?php endif; ?>
        <?php if (!empty($social_links['facebook'])): ?>
            <a href="<?php echo htmlspecialchars($social_links['facebook']); ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
        <?php endif; ?>
    </div>
    <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($business_name); ?>. Tutti i diritti riservati.</p>
    <?php if(!empty($p_iva)): ?>
        <p>P.IVA: <?php echo htmlspecialchars($p_iva); ?></p>
    <?php endif; ?>
</footer>

<script src="assets/js/main.js?v=<?php echo time(); // Cache busting ?>"></script>
</body>
</html>