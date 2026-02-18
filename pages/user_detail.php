<?php
//legge ID e cerca lo studente con quell'ID, se non c'e' da errore
require __DIR__.'/../includes/db.php'; include __DIR__.'/../includes/header.php';
$id = intval($_GET['id'] ?? 0);
if (!$id) { echo '<div class="alert error"><i class="bi bi-exclamation-triangle"></i>&nbsp;Utente non trovato.</div>'; include __DIR__.'/../includes/footer.php'; exit; }
$u = $mysqli->query('SELECT * FROM student WHERE id='.$id)->fetch_assoc();
?>
<!-- mostra nome cognome e matricola-->
<h1><i class="bi bi-person-badge"></i>&nbsp;<?php echo h($u['last_name'].' '.$u['first_name']); ?> <span class="badge"><?php echo h($u['matricola']); ?></span></h1>
<div class="card" style="max-width:720px">
  <div class="actions">
    <!-- mostra indirizzo e telefono, se non presenti - -->
    <div style="flex:1"><strong>Indirizzo</strong><br><?php echo h($u['address'] ?: '—'); ?></div>
    <div style="flex:1"><strong>Telefono</strong><br><?php echo h($u['phone'] ?: '—'); ?></div>
  </div>
</div>

<!-- estrae i prestiti e ordina per data di uscita decresente, e tabella libro, succursale, uscita, scadenza e restituzione -->
<h2><i class="bi bi-journal-bookmark"></i>&nbsp;Prestiti (storico)</h2>
<table>
<thead><tr><th>Libro</th><th>Succursale</th><th>Uscita</th><th>Scadenza</th><th>Restituzione</th></tr></thead><tbody>
<?php
$q=$mysqli->query('SELECT b.title_en, br.name branch_name, l.checkout_date, l.due_date, l.return_date
                   FROM loan l JOIN book b ON l.book_id=b.id JOIN branch br ON l.branch_id=br.id
                   WHERE l.student_id='.$id.' ORDER BY l.checkout_date DESC');
while($r=$q->fetch_assoc()){
  echo '<tr><td>'.h($r['title_en']).'</td><td>'.h($r['branch_name']).'</td><td>'.h($r['checkout_date']).'</td><td><span class="pill">'.h($r['due_date']).'</span></td><td>'.h($r['return_date']??'—').'</td></tr>';
}
?>
</tbody></table>
<?php include __DIR__.'/../includes/footer.php'; ?>
