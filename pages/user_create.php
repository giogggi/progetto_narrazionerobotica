<?php
require __DIR__.'/../includes/db.php';
include __DIR__.'/../includes/header.php';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $mat = trim($_POST['matricola'] ?? '');
  $fn = trim($_POST['first_name'] ?? '');
  $ln = trim($_POST['last_name'] ?? '');
  $addr = trim($_POST['address'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  //chiede numero di matricola,nome e cognome
  if ($mat && $fn && $ln) {
    //inserisce i dati nel DB
    $stmt = $mysqli->prepare('INSERT INTO student (matricola, first_name, last_name, address, phone) VALUES (?,?,?,?,?)');
    $stmt->bind_param('sssss', $mat, $fn, $ln, $addr, $phone);
    //se INSERT va a buon fine -> utente inserito, altrimetni errore
    if ($stmt->execute()) echo '<div class="alert success"><i class="bi bi-check-circle"></i>&nbsp;Utente inserito.</div>';
    else echo '<div class="alert error"><i class="bi bi-bug"></i>&nbsp;Errore: '.h($stmt->error).'</div>';
  } //se non presenti numero di matricola,nome e cognome segna errore
  else {
    echo '<div class="alert error"><i class="bi bi-exclamation-triangle"></i>&nbsp;Compila i campi obbligatori.</div>';
  }
}
?>
<h1><i class="bi bi-person-plus"></i>&nbsp;Inserisci nuovo utente</h1>
<div class="card" style="max-width:720px">
<form method="post">
  <!-- matricola -->
  <label>Matricola*</label><input name="matricola" required placeholder="Es. M12345"/>
  <div class="actions">
    <!-- nome e cognome-->
    <div style="flex:1"><label>Nome*</label><input name="first_name" required /></div>
    <div style="flex:1"><label>Cognome*</label><input name="last_name" required /></div>
  </div>
  <!-- indirizzo e telefono-->
  <label>Indirizzo</label><input name="address" placeholder="Via, Numero, Città" />
  <label>Telefono</label><input name="phone" placeholder="+39 ..." />
  <!-- bottone salva ed invia-->
  <button type="submit"><i class="bi bi-save"></i>&nbsp;Salva</button>
</form>
</div>
<?php include __DIR__.'/../includes/footer.php'; ?>
