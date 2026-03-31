<?php
// Carichiamo l'header che abbiamo personalizzato
require_once __DIR__ . '/includes/header.php';
?>

<div class="welcome-hub">
    <div class="welcome-text">
        <h1>Benvenuta nel tuo Hub Esclusivo</h1>
        <p>Il tuo spazio riservato per la bellezza e il benessere firmato Beauty of Image.</p>
    </div>

    <div class="hub-grid">
        <a href="prodotti.php" class="hub-card">
            <div class="hub-icon"><i class="fas fa-shopping-bag"></i></div>
            <h3>Prodotti Professionale</h3>
            <p>Scopri le nostre linee esclusive per la tua beauty routine.</p>
            <span class="hub-btn">Esplora lo Shop</span>
        </a>

        <a href="servizi.php" class="hub-card">
            <div class="hub-icon"><i class="fas fa-sparkles"></i></div>
            <h3>I Nostri Servizi</h3>
            <p>Prenota o acquista i trattamenti in salone.</p>
            <span class="hub-btn">Vedi Trattamenti</span>
        </a>

        <a href="promozioni.php" class="hub-card">
            <div class="hub-icon"><i class="fas fa-tags"></i></div>
            <h3>Promozioni & Gift</h3>
            <p>Offerte speciali e idee regalo per te o per chi ami.</p>
            <span class="hub-btn">Scopri Offerte</span>
        </a>
    </div>
</div>

<style>
    .welcome-hub { text-align: center; padding: 40px 0; }
    .welcome-text h1 { font-size: 2.5rem; margin-bottom: 10px; color: var(--beauty-gold) !important; }
    .welcome-text p { font-style: italic; color: #666; margin-bottom: 50px; }

    .hub-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
        gap: 30px; 
    }

    .hub-card { 
        background: #fff; 
        border: 1px solid #eee; 
        padding: 40px 20px; 
        text-decoration: none !important; 
        color: var(--beauty-black) !important;
        transition: 0.3s;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .hub-card:hover { 
        border-color: var(--beauty-gold); 
        transform: translateY(-10px);
        box-shadow: 0 10px 30px rgba(212, 175, 55, 0.1);
    }

    .hub-icon { font-size: 3rem; color: var(--beauty-gold); margin-bottom: 20px; }
    .hub-card h3 { text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; }
    .hub-card p { font-size: 0.9rem; color: #777; margin-bottom: 25px; line-height: 1.4; }

    .hub-btn { 
        background: var(--beauty-gold); 
        color: #fff; 
        padding: 10px 20px; 
        font-size: 0.8rem; 
        font-weight: bold; 
        text-transform: uppercase; 
    }
</style>

<?php
// Carichiamo il footer
require_once __DIR__ . '/includes/footer.php';
?>