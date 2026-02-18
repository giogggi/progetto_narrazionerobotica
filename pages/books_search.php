<?php
require __DIR__.'/../includes/db.php'; include __DIR__.'/../includes/header.php';
// leggo la ricerca
$title = trim($_GET['title'] ?? '');
//accumulo risultati per la tabella
$rows = [];
//se e' presente qualcosa nel campo 'title', ovvero se l'utente ha scritto qualcosa, esegue la ricerca
if ($title!=='') {
  $esc = $mysqli->real_escape_string($title);
  $res = $mysqli->query("SELECT b.id, b.title_en, b.publication_year, b.original_language, b.isbn FROM book b WHERE b.title_en LIKE '%$esc%' ORDER BY b.title_en LIMIT 200");
  if ($res) while($r=$res->fetch_assoc()) $rows[]=$r;
  //se non è presente nulla mostra l'elenco dei primi 100 libri
} else {
  $res = $mysqli->query('SELECT b.id, b.title_en, b.publication_year, b.original_language, b.isbn FROM book b ORDER BY b.title_en LIMIT 100');
  if ($res) while($r=$res->fetch_assoc()) $rows[]=$r;
}
?>
<!-- Titolo pagina -->
<h1><i class="bi bi-search"></i>&nbsp;Ricerca libri</h1>

<style>
/*  serve per visualizzare correttamente la tabella */
table { overflow: visible !important; }
thead th { position: static !important; top: auto !important; z-index: 1 !important; }
tbody { display: table-row-group !important; }
tbody tr { display: table-row !important; visibility: visible !important; opacity: 1 !important; }
table, th, td { color: var(--fg) !important; }
</style>
<!-- box per la ricerca del libro-->
<div class="card" style="max-width:720px">
  <form method="get" class="actions">
    <div style="flex:1"><input name="title" placeholder="Titolo (anche parziale)" value="<?php echo h($title); ?>"/></div>
    <div><button type="submit"><i class="bi bi-search"></i>&nbsp;Cerca</button></div>
  </form>
</div>
<!-- mostra risultati solo se e' stato inserito un libro -->
<?php if ($title!==''): ?>
  <p class="small muted">Ricerca per: <strong><?php echo h($title); ?></strong> — <span class="pill"><?php echo count($rows); ?> risultati</span></p>
<?php endif; ?>
<!-- se $rows e' vuoto non ho risultati di ricerca, quidni stampo "nessun libro trovato -->
<?php if (!count($rows)): ?>
  <div class="alert error"><i class="bi bi-emoji-frown"></i>&nbsp;Nessun libro trovato.</div>
<?php else: ?>
  <!-- tabella con intestazioni tabella -->
<div class="table-wrap">
  <table>
    <thead><tr><th>Titolo</th><th>Anno</th><th>Lingua</th><th>ISBN</th><th>Autori</th><th>Disponibilità</th></tr></thead><tbody>
<?php
foreach($rows as $r){
  //cerca autori libro
  $a = [];
  $q=$mysqli->query('SELECT CONCAT(a.first_name, " ", a.last_name) AS nm FROM author a JOIN book_author ba ON a.id=ba.author_id WHERE ba.book_id='.(int)$r['id'].' ORDER BY a.last_name');
  if ($q) while($ra=$q->fetch_assoc()) $a[]=$ra['nm'];
  // cerca numero di copie totali
  $q2=$mysqli->query('SELECT SUM(copies) AS tot FROM inventory WHERE book_id='.(int)$r['id']);
  $tot=0; if ($q2) { $rowTot=$q2->fetch_assoc(); $tot = $rowTot && $rowTot['tot']!==null ? (int)$rowTot['tot'] : 0; }
  //stampa dei risultati
  echo '<tr><td>'.h($r['title_en']).'</td><td>'.h($r['publication_year']).'</td><td>'.h($r['original_language']).'</td><td>'.h($r['isbn']).'</td><td>'.h(implode(", ",$a)).'</td><td>'.h($tot).' copie</td></tr>';
}
?>
    </tbody></table>
</div>
<?php endif; ?>
<?php include __DIR__.'/../includes/footer.php'; ?>
