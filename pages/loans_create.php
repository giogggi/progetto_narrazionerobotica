<?php
require __DIR__.'/../includes/db.php';
include __DIR__.'/../includes/header.php';
//inserisci il prestito
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $student_id = intval($_POST['student_id'] ?? 0);
  $book_id = intval($_POST['book_id'] ?? 0);
  $branch_id = intval($_POST['branch_id'] ?? 0);
  $checkout_date = $_POST['checkout_date'] ?? date('Y-m-d');
  //se tutti i campi non sono stati compilati da errore
  if (!$student_id || !$book_id || !$branch_id) {
    echo '<div class="alert error"><i class="bi bi-exclamation-triangle"></i>&nbsp;Compila tutti i campi.</div>';
  } else {
    //copie disponibili nella succursale per quel libro
    $stmt = $mysqli->prepare('SELECT copies FROM inventory WHERE branch_id=? AND book_id=?');
    $stmt->bind_param('ii', $branch_id, $book_id);
    $inv = db_stmt_all_assoc($stmt);
    $copies = $inv ? (int)$inv[0]['copies'] : 0;
    //prestiti in corso su quel libro nella succursale
    $stmt = $mysqli->prepare('SELECT COUNT(*) AS active FROM loan WHERE book_id=? AND branch_id=? AND return_date IS NULL');
    $stmt->bind_param('ii', $book_id, $branch_id);
    $act = db_stmt_all_assoc($stmt);
    $active = $act ? (int)$act[0]['active'] : 0;
     //se le copie sono tutte prese da errore
    if ($copies<=0 || $active >= $copies) {
      echo '<div class="alert error"><i class="bi bi-emoji-frown"></i>&nbsp;Nessuna copia disponibile in questa succursale.</div>';
    } else { //inserimento prestito
      $stmt = $mysqli->prepare('INSERT INTO loan (student_id, book_id, branch_id, checkout_date, due_date) VALUES (?,?,?,?, DATE_ADD(?, INTERVAL 30 DAY))');
      $stmt->bind_param('iiiss', $student_id, $book_id, $branch_id, $checkout_date, $checkout_date);
      if ($stmt->execute()) {
        echo '<div class="alert success"><i class="bi bi-check-circle"></i>&nbsp;Prestito inserito. Scadenza: '.h(date('Y-m-d', strtotime($checkout_date.' +30 days'))).'</div>';
      } else {
        echo '<div class="alert error"><i class="bi bi-bug"></i>&nbsp;Errore inserimento prestito: '.h($stmt->error).'</div>';
      }
    }
  }
}
?>
<!-- titolo -->
<h1><i class="bi bi-plus-circle"></i>&nbsp;Inserisci un prestito</h1>
<div class="card" style="max-width:720px">
<!-- nuovo prestito -->
<form method="post">
  <div class="actions">
    <div style="flex:1;min-width:220px">
      <label>Studente</label>
      <select name="student_id" required>
        <option value="">— seleziona —</option>
        <!-- popola tendina con gli studenti -->
        <?php
          $q = $mysqli->query('SELECT id, matricola, first_name, last_name FROM student ORDER BY last_name');
          while($r=$q->fetch_assoc()){
            echo '<option value="'.(int)$r['id'].'">'.h($r['last_name'].' '.$r['first_name'].' ('.$r['matricola'].')').'</option>';
          }
        ?>
      </select>
    </div>
    <div style="flex:1;min-width:220px">
      <label>Libro</label>
      <select name="book_id" required>
        <option value="">— seleziona —</option>
        <!-- tendina libri-->
        <?php
          $q = $mysqli->query('SELECT id, title_en, publication_year FROM book ORDER BY title_en');
          while($r=$q->fetch_assoc()){
            echo '<option value="'.(int)$r['id'].'">'.h($r['title_en'].' ('.$r['publication_year'].')').'</option>';
          }
        ?>
      </select>
    </div>
  </div>
  <div class="actions">
    <div style="flex:1;min-width:220px">
      <label>Succursale</label>
      <select name="branch_id" required>
        <option value="">— seleziona —</option>
        <!-- tendina succursali-->
        <?php
          $q = $mysqli->query('SELECT id, name FROM branch ORDER BY name');
          while($r=$q->fetch_assoc()){
            echo '<option value="'.(int)$r['id'].'">'.h($r['name']).'</option>';
          }
        ?>
      </select>
    </div>
    <div style="flex:1;min-width:220px">
      <label>Data uscita</label>
      <input type="date" name="checkout_date" value="<?php echo h(date('Y-m-d')); ?>" required/>
    </div>
  </div>
  <!-- bottone per il salvataggio-->
  <button type="submit"><i class="bi bi-save"></i>&nbsp;Salva prestito</button>
</form>
</div>
<!-- prestiti attivi negli ultimi 30 giorni -->
<div class="card">
  <h2><i class="bi bi-clock-history"></i>&nbsp;Prestiti attivi (ultimi 30)</h2>
  <table>
    <thead><tr><th>Studente</th><th>Libro</th><th>Succursale</th><th>Uscita</th><th>Scadenza</th><th>Azioni</th></tr></thead>
    <tbody>
    <?php
      $q=$mysqli->query('SELECT l.id, s.first_name, s.last_name, b.title_en, br.name as branch_name, l.checkout_date, l.due_date 
                         FROM loan l 
                         JOIN student s ON l.student_id=s.id 
                         JOIN book b ON l.book_id=b.id 
                         JOIN branch br ON l.branch_id=br.id
                         WHERE l.return_date IS NULL ORDER BY l.checkout_date DESC LIMIT 30');
      while($r=$q->fetch_assoc()){
        echo '<tr><td>'.h($r['last_name'].' '.$r['first_name']).'</td><td>'.h($r['title_en']).'</td><td>'.h($r['branch_name']).'</td>
              <td>'.h($r['checkout_date']).'</td><td><span class="pill">'.h($r['due_date']).'</span></td>
              <td class="actions"><a class="badge" href="loans_delete.php?id='.(int)$r['id'].'"><i class="bi bi-box-arrow-in-left"></i>&nbsp;Restituisci</a></td></tr>';
      }
    ?>
    </tbody>
  </table>
</div>
<?php include __DIR__.'/../includes/footer.php'; ?>
