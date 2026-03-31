<?php 
// Protezione: se non è loggato, non caricare nulla
if (!isset($_SESSION['user_id'])) return; 
?>

<style>
    /* Finestra più grande e leggibile */
    #ai-window { 
        width: 450px;          /* Aumentato da 320px a 450px */
        max-width: 90vw;       /* Sicurezza per i cellulari */
        z-index: 1050; 
        border-radius: 20px;   /* Angoli più smussati, più moderno */
        overflow: hidden; 
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15); /* Ombra più profonda */
    }

    /* Area messaggi molto più alta */
    #ai-body { 
        height: 500px;         /* Aumentato da 280px a 500px */
        overflow-y: auto; 
        font-size: 0.95rem;    /* Testo leggermente più grande */
        background: #f8f9fa; 
        padding: 20px;         /* Più spazio interno */
    }

    /* Scrollbar personalizzata più visibile */
    #ai-body::-webkit-scrollbar { width: 6px; }
    #ai-body::-webkit-scrollbar-thumb { 
        background: #0d6efd; 
        border-radius: 10px; 
    }
</style>

<div id="ai-button" onclick="toggleAI()" style="position: fixed; bottom: 25px; right: 25px; cursor: pointer; z-index: 1000;">
    <div class="bg-primary text-white rounded-circle shadow-lg d-flex align-items-center justify-content-center" style="width: 55px; height: 55px; border: 3px solid #fff;">
        <i class="bi bi-stars fs-3"></i>
    </div>
</div>

<div id="ai-window" class="card shadow-lg d-none" style="position: fixed; bottom: 90px; right: 25px; z-index: 1000;">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
        <span class="fw-bold small text-uppercase"><i class="bi bi-robot me-2"></i>Beauty Assistant</span>
        <button type="button" class="btn-close btn-close-white" onclick="toggleAI()"></button>
    </div>
    <div id="ai-body" class="card-body p-3">
        <div class="bg-white p-2 rounded shadow-sm mb-3 border-start border-primary border-4">
            Ciao <strong><?php echo $_SESSION['username']; ?></strong>! Come posso aiutarti?
        </div>
        <div class="d-flex flex-wrap gap-1 mb-3">
            <button class="btn btn-outline-primary btn-sm" style="font-size: 0.7rem;" onclick="quickAsk('Come carico un file?')">Caricare File</button>
            <button class="btn btn-outline-primary btn-sm" style="font-size: 0.7rem;" onclick="quickAsk('Come creo un utente?')">Creare Utenti</button>
        </div>
    </div>
    <div class="card-footer bg-white p-2">
        <div class="input-group">
            <input type="text" id="ai-input" class="form-control form-control-sm" placeholder="Chiedi..." onkeypress="if(event.key === 'Enter') sendToAI()">
            <button class="btn btn-primary btn-sm" onclick="sendToAI()"><i class="bi bi-send"></i></button>
        </div>
    </div>
</div>

<script>
let chatHistory = [];
function toggleAI() { document.getElementById('ai-window').classList.toggle('d-none'); }
function quickAsk(q) { document.getElementById('ai-input').value = q; sendToAI(); }

async function sendToAI() {
    const input = document.getElementById('ai-input');
    const body = document.getElementById('ai-body');
    if (!input.value.trim()) return;
    const q = input.value;
    input.value = '';
    body.innerHTML += `<div class="text-end mb-2"><div class="d-inline-block bg-primary text-white p-2 rounded small shadow-sm">${q}</div></div>`;
    const loadingId = 'loading-' + Date.now();
    body.innerHTML += `<div id="${loadingId}" class="text-muted italic small mb-2">Sto elaborando...</div>`;
    body.scrollTop = body.scrollHeight;

    try {
        const response = await fetch('ai_handler.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ question: q, history: chatHistory })
        });
        const data = await response.json();
        chatHistory.push({ role: "user", content: q }, { role: "model", content: data.answer });
        if(chatHistory.length > 10) chatHistory.splice(0, 2);
        document.getElementById(loadingId).remove();
        body.innerHTML += `<div class="bg-white p-2 rounded shadow-sm mb-2 border-start border-success border-4"><strong>AI:</strong> ${data.answer}</div>`;
    } catch (e) { document.getElementById(loadingId).innerText = "Errore connessione."; }
    body.scrollTop = body.scrollHeight;
}
</script>