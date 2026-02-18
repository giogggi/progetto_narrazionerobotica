<?php
require __DIR__.'/../includes/db.php';
include __DIR__.'/../includes/header.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : ''; //legge il testo della query string
$rows = []; //numero di autori in tabella

// se non è stato scritto ancora nulla stampa i primi 100 autori in ordine
if ($q === '') {
  $res = $mysqli->query('SELECT id, first_name, last_name, birth_date, birth_place FROM author ORDER BY last_name, first_name LIMIT 200');
  if ($res) { while ($r = $res->fetch_assoc()) $rows[] = $r; }
  else { $errorMsg = 'Errore query iniziale: '.$mysqli->error; }
  //se presente un filtro cerca per nome e cognome/nome/cognome o luogo
} else {
  $esc = $mysqli->real_escape_string($q);
  $sql = "SELECT id, first_name, last_name, birth_date, birth_place
          FROM author
          WHERE first_name LIKE '%$esc%'
             OR last_name  LIKE '%$esc%'
             OR birth_place LIKE '%$esc%'
             OR CONCAT(first_name, ' ', last_name) LIKE '%$esc%'
             OR CONCAT(last_name,  ' ', first_name) LIKE '%$esc%'
          ORDER BY last_name, first_name
          LIMIT 100";
  $res = $mysqli->query($sql);
  if ($res) { while ($r = $res->fetch_assoc()) $rows[] = $r; }
  else { $errorMsg = 'Errore ricerca: '.$mysqli->error; }
}
?>
<h1><i class="bi bi-person-vcard"></i>&nbsp;Ricerca autori</h1>

<style>
/* serve per visualizzare correttamente i dati all'interno della tabella */
table { overflow: visible !important; }
thead th { position: static !important; top: auto !important; z-index: 1 !important; }
tbody { display: table-row-group !important; }
tbody tr { display: table-row !important; visibility: visible !important; opacity: 1 !important; }
table, th, td { color: var(--fg) !important; }
</style>

<!-- scrittura per ricerca del nome -->
<div class="card" style="max-width:780px">
  <form method="get" class="actions">
    <div style="flex:1">
      <input name="q" placeholder="Es. 'Rowling', 'Italo Calvino', 'Yate'" value="<?php echo h($q); ?>"/>
    </div>
    <div><button type="submit"><i class="bi bi-search"></i>&nbsp;Cerca</button></div>
  </form>
  <p class="small muted">inserisci nome,cognome o luogo.</p>
</div>

<?php if ($q !== ''): ?>
  <!-- testo cercato e numero dei risultati -->
  <p class="small muted">Ricerca per: <strong><?php echo h($q); ?></strong> — <span class="pill"><?php echo count($rows); ?> risultati</span></p>
<?php endif; ?>

<?php if ($errorMsg): ?>
  <!--query fallita -->
  <div class="alert error"><i class="bi bi-bug"></i>&nbsp;<?php echo h($errorMsg); ?></div>
<?php elseif (!count($rows)): ?>
  <!-- nessun autore trovato-->
  <div class="alert error"><i class="bi bi-emoji-frown"></i>&nbsp;Nessun autore trovato.</div>
<?php else: ?>
  <!--tabella con autore, data di nascita e luogo-->
<div class="table-wrap">
  <table>
    <thead><tr><th>Autore</th><th>Nascita</th><th>Luogo</th></tr></thead>
    <tbody>
<?php
  //stampa
  foreach ($rows as $r) {
    echo '<tr><td>'.h($r['last_name'].' '.$r['first_name']).'</td><td>'.h($r['birth_date']).'</td><td>'.h($r['birth_place']).'</td></tr>';
  }
?>
    </tbody>
  </table>
</div>
<?php endif; ?>

<?php include __DIR__.'/../includes/footer.php'; ?>
