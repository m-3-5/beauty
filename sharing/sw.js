self.addEventListener('fetch', (event) => {
  // Questo codice permette all'app di essere riconosciuta come installabile
  event.respondWith(fetch(event.request));
});