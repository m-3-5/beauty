<div id="install-app-block" style="display:none; text-align:center; margin: 20px 0;">
    <div class="card border-primary shadow-sm mx-auto" style="max-width: 400px; border-style: dashed;">
        <div class="card-body p-3">
            <h6 class="fw-bold text-primary mb-2"><i class="bi bi-phone-vibrate me-2"></i>Beauty Sharing App</h6>
            <p class="small text-muted mb-3">Installa l'app sul tuo desktop o nella home del cellulare per un accesso rapido.</p>
            <button id="btn-install-now" class="btn btn-primary btn-sm rounded-pill px-4">
                <i class="bi bi-download me-2"></i>Installa Ora
            </button>
        </div>
    </div>
</div>

<script>
let installPrompt;
const installBlock = document.getElementById('install-app-block');

window.addEventListener('beforeinstallprompt', (e) => {
    // Impedisce al browser di mostrare il prompt automatico
    e.preventDefault();
    installPrompt = e;
    // Mostra il nostro tasto personalizzato
    installBlock.style.display = 'block';
});

document.getElementById('btn-install-now').addEventListener('click', async () => {
    if (installPrompt) {
        installPrompt.prompt();
        const { outcome } = await installPrompt.userChoice;
        if (outcome === 'accepted') {
            installBlock.style.display = 'none';
        }
        installPrompt = null;
    }
});
	// Registrazione del Service Worker obbligatoria per PWA
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('sw.js');
}
</script>