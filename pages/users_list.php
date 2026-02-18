<?php
require __DIR__.'/../includes/db.php'; include __DIR__.'/../includes/header.php';

$term = trim($_GET['q'] ?? '');
//accumulo le righe utenti da mostrare
$rows = [];
//filtro per matricola/cognome/nome
if ($term !== '') {
  
  $esc = $mysqli->real_escape_string($term);
  $sql = "SELECT id, matricola, last_name, first_name, phone FROM student
          WHERE matricola LIKE '%$esc%' OR last_name LIKE '%$esc%' OR first_name LIKE '%$esc%'
          ORDER BY last_name, first_name LIMIT 200";
        //risultati in $rows
  $res = $mysqli->query($sql);
  if ($res) while($r=$res->fetch_assoc()) $rows[]=$r;
  //se non c'e' un campo di ricerca mostra elenco ordinato
} else {

  $res = $mysqli->query('SELECT id, matricola, last_name, first_name, phone FROM student ORDER BY last_name, first_name LIMIT 200');
  if ($res) while($r=$res->fetch_assoc()) $rows[]=$r;
}
//titolo della pagina
?>
<h1><i class="bi bi-people"></i>&nbsp;Elenco utenti</h1>

<style>
/* --- per la corretta visualizzazione della tabella --- */
table { overflow: visible !important; }
thead th { position: static !important; top: auto !important; z-index: 1 !important; }
tbody { display: table-row-group !important; }
tbody tr { display: table-row !important; visibility: visible !important; opacity: 1 !important; }
table, th, td { color: var(--fg) !important; }
</style>
<!-- box ricerca-->
<div class="card" style="max-width:720px">
  <form method="get" class="actions">
    <div style="flex:1"><input name="q" placeholder="Cerca per nome, cognome o matricola" value="<?php echo h($term); ?>"/></div>
    <div><button type="submit"><i class="bi bi-search"></i>&nbsp;Cerca</button></div>
  </form>
</div>
<?php if ($term!==''): ?>
  <!-- ricerca per stringa inserita e numero di risultati -->
  <p class="small muted">Ricerca per: <strong><?php echo h($term); ?></strong> — <span class="pill"><?php echo count($rows); ?> risultati</span></p>
<?php endif; ?>
<!-- nel caso non ci fosse nessun risultato stampa nessun utente trovato-->
<?php if (!count($rows)): ?>
  <div class="alert error"><i class="bi bi-emoji-frown"></i>&nbsp;Nessun utente trovato.</div>
<?php else: ?>
  <!-- tabella con risultati: elenco utenti-->
<div class="table-wrap">
  <table>
<thead><tr><th>Matricola</th><th>Cognome</th><th>Nome</th><th>Telefono</th><th>Azioni</th></tr></thead><tbody>
<?php
//stampa ogni riga risultato
foreach($rows as $r){
  echo '<tr><td>'.h($r['matricola']).'</td><td>'.h($r['last_name']).'</td><td>'.h($r['first_name']).'</td><td>'.h($r['phone']).'</td>
        <td><a class="badge" href="/pages/user_detail.php?id='.(int)$r['id'].'"><i class="bi bi-clock-history"></i>&nbsp;Storico</a></td></tr>';
}
?>
</tbody></table>
</div>
<?php endif; ?>
<?php include __DIR__.'/../includes/footer.php'; ?>
