<?php
include __DIR__.'/includes/db.php';
include __DIR__.'/includes/header.php';
?>
<!-- mostra il titolo e descrizione-->
<section class="py-4">
  <h1 class="h3 mb-2">Sistema informativo per la gestione bibliotecaria– UniFE</h1>
  <p class="mb-0">
    Sono <strong>Giorgia Benatelli (193998)</strong> e questo progetto presenta lo sviluppo di un
    <strong>sistema digitale per la gestione e la comunicazione delle informazioni bibliotecarie</strong>,
    con particolare attenzione all’automazione dei processi e all’interazione utente–sistema.</strong>
  </p>
</section>

<!-- elenco funzioni del sistema -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 1rem;">
  <div class="card-body">
    <h2 class="h5 mb-3">Funzionalità del sistema</h2>
    <ul class="mb-0">
      <li>Gestione automatizzata dei prestiti (inserimento e cancellazione).</li>
      <li>Gestione e consultazione degli utenti registrati.</li>
      <li>Ricerca e organizzazione delle informazioni su libri e autori.</li>
      <li>Statistiche su libri pubblicati in un anno, prestiti per succursale e numero di libri per autore.</li>
      <li>Supporto alla comunicazione tra sistema informativo e utente finale.</li>
    </ul>
  </div>
</div>

<div class="card border-0 shadow-sm mb-4" style="border-radius: 1rem;">
  <div class="card-body">
<div class="mt-4">
  <p>
    Il sistema rappresenta un esempio di applicazione web in cui il dato viene trasformato in informazione accessibile,
    attraverso un’interfaccia che media tra utente e struttura del database.
    L’automazione dei processi di ricerca e gestione dei prestiti evidenzia come un sistema digitale possa
    supportare decisioni e operazioni in modo efficiente.
    Durante lo sviluppo sono stati utilizzati anche strumenti di intelligenza artificiale generativa
    come supporto alla progettazione e al debugging, mantenendo sempre centrale il ruolo del progettista umano.
  </p>
</div>
</div>

<!-- istruzioni per la configurazione -->
<div class="card border-0 shadow-sm" style="border-radius: 1rem;">
  <div class="card-body">
    <h2 class="h6 mb-2">Come iniziare</h2>
    <ol class="mb-2">
      <li>Importa il dump <code>unife_biblioteca.sql</code> in phpMyAdmin (database: <code>unife_biblioteca</code>).</li>
      <li>Copia questa cartella nella root di XAMPP (<code>htdocs</code>) e apri <code>http://localhost/</code>.</li>
    </ol>
    <p class="small mb-0">
      Login MySQL predefinito: utente <code>root</code>, password vuota. Modifica <code>includes/db.php</code> se necessario.
    </p>
  </div>
</div>

<?php include __DIR__.'/includes/footer.php'; ?>
