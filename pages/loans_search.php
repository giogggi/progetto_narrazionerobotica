<?php
require __DIR__.'/../includes/db.php'; include __DIR__.'/../includes/header.php';
//legge le date dal-al
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$rows = [];
//nessuna data mostra prestiti ancora in corso
if ($from==='' && $to==='') {
  $res=$mysqli->query('SELECT s.last_name, s.first_name, b.title_en, br.name as branch_name, l.checkout_date, l.due_date
                       FROM loan l JOIN student s ON l.student_id=s.id JOIN book b ON l.book_id=b.id JOIN branch br ON l.branch_id=br.id
                       WHERE l.return_date IS NULL AND l.due_date >= CURDATE() ORDER BY l.due_date ASC LIMIT 200');
  if ($res) while($r=$res->fetch_assoc()) $rows[]=$r;
} else {
  //ho almeno una data e mostra riepilogo in quel lasso di tempo
  $where = ' WHERE 1=1 ';
  if ($from!=='') $where .= " AND l.checkout_date >= '".$mysqli->real_escape_string($from)."' ";
  if ($to!=='')   $where .= " AND l.checkout_date <= '".$mysqli->real_escape_string($to)."' ";
  $sql='SELECT s.last_name, s.first_name, b.title_en, br.name as branch_name, l.checkout_date, l.due_date
        FROM loan l JOIN student s ON l.student_id=s.id JOIN book b ON l.book_id=b.id JOIN branch br ON l.branch_id=br.id' . $where . ' ORDER BY l.checkout_date DESC LIMIT 300';
  $res=$mysqli->query($sql);
  if ($res) while($r=$res->fetch_assoc()) $rows[]=$r;
}
?>
<h1><i class="bi bi-hourglass-split"></i>&nbsp;Ricerca prestiti</h1>

<style>
/*  serve per visualizzare correttamente la tabella */
table { overflow: visible !important; }
thead th { position: static !important; top: auto !important; z-index: 1 !important; }
tbody { display: table-row-group !important; }
tbody tr { display: table-row !important; visibility: visible !important; opacity: 1 !important; }
table, th, td { color: var(--fg) !important; }
</style>
<!-- tabella  -->
<div class="card" style="max-width:860px">
  <form method="get">
    <div class="actions">
      <div style="flex:1;min-width:220px">
        <label>Dal</label><input type="date" name="from" value="<?php echo h($from); ?>"/>
      </div>
      <div style="flex:1;min-width:220px">
        <label>Al</label><input type="date" name="to" value="<?php echo h($to); ?>"/>
      </div>
      <div style="align-self:end">
        <button type="submit"><i class="bi bi-search"></i>&nbsp;Cerca</button>
      </div>
    </div>
  </form>
</div>
<!-- stampa intervallo delle date e quanti risultati sono stati trovati -->
<?php if ($from!=='' || $to!==''): ?>
  <p class="small muted">Intervallo: <strong><?php echo h($from ?: '—'); ?></strong> → <strong><?php echo h($to ?: '—'); ?></strong> — <span class="pill"><?php echo count($rows); ?> risultati</span></p>
<?php endif; ?>
<!-- in caso di $rows vuoto stampa nessun prestito trovato-->
<?php if (!count($rows)): ?>
  <div class="alert error"><i class="bi bi-emoji-frown"></i>&nbsp;Nessun prestito trovato.</div>
<?php else: ?>
<div class="table-wrap">
  <!-- tabella con i risultati  -->
  <table>
    <thead><tr><th>Studente</th><th>Libro</th><th>Succursale</th><th>Uscita</th><th>Scadenza</th></tr></thead>
    <tbody>
<?php foreach($rows as $r){
  echo '<tr><td>'.h($r['last_name'].' '.$r['first_name']).'</td><td>'.h($r['title_en']).'</td><td>'.h($r['branch_name']).'</td><td>'.h($r['checkout_date']).'</td><td><span class="pill">'.h($r['due_date']).'</span></td></tr>';
} ?>
    </tbody>
  </table>
</div>
<?php endif; ?>
<?php include __DIR__.'/../includes/footer.php'; ?>
