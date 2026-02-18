<?php
require __DIR__.'/../includes/db.php'; include __DIR__.'/../includes/header.php';
$year = intval($_GET['year'] ?? date('Y'));
$from = $_GET['from'] ?? date('Y-m-01');
$to = $_GET['to'] ?? date('Y-m-t');
?>
<h1><i class="bi bi-bar-chart-line"></i>&nbsp;Statistiche</h1>
<!-- pagina statistiche-->
<div class="grid col-2">
  <div class="card">
    <h2><i class="bi bi-book"></i>&nbsp;Libri pubblicati in un anno</h2>
    <!-- libri pubblicati in un anno
          inserisci l'anno e premi calcola-->
    <form method="get" class="actions" style="margin-top:6px">
      <div><label>Anno</label><input type="number" name="year" value="<?php echo h($year); ?>"/></div>
      <div><button type="submit"><i class="bi bi-calculator"></i>&nbsp;Calcola</button></div>
    </form>
    <p class="lead" style="margin-top:8px">
    <!-- conta i libri e ne restituisce il numero, se non presenti 0 -->
      <?php
        $stmt=$mysqli->prepare('SELECT COUNT(*) AS n FROM book WHERE publication_year=?');
        $stmt->bind_param('i', $year); 
        $res = db_stmt_all_assoc($stmt);
        echo '<strong>'.(int)($res[0]['n'] ?? 0).'</strong> libri pubblicati nel '.$year;
      ?>
    </p>
  </div>

  <!-- prestiti per succursale, inserendo le date -->
  <div class="card">
    <h2><i class="bi bi-building"></i>&nbsp;Prestiti per succursale</h2>
    <form method="get" class="actions" style="margin-top:6px">
      <div><label>Dal</label><input type="date" name="from" value="<?php echo h($from); ?>"/></div>
      <div><label>Al</label><input type="date" name="to" value="<?php echo h($to); ?>"/></div>
      <div><button type="submit"><i class="bi bi-calculator"></i>&nbsp;Calcola</button></div>
    </form>
    <table style="margin-top:20px">
      <thead><tr><th>Succursale</th><th>Prestiti</th></tr></thead><tbody>
        <!-- conta prestiti effettuati per data di uscita, ordinato per conteggio decrescente-->
      <?php
        $stmt=$mysqli->prepare('SELECT br.name, COUNT(*) AS n FROM loan l JOIN branch br ON l.branch_id=br.id WHERE l.checkout_date BETWEEN ? AND ? GROUP BY br.id ORDER BY n DESC');
        $stmt->bind_param('ss', $from, $to); 
        $rows = db_stmt_all_assoc($stmt);
        foreach($rows as $r){
          echo '<tr><td>'.h($r['name']).'</td><td>'.h($r['n']).'</td></tr>';
        }
      ?>
      </tbody>
    </table>
  </div>
</div>
<!-- numero di libri per autore-->
<div class="card">
  <h2><i class="bi bi-person-lines-fill"></i>&nbsp;Numero di libri per autore</h2>
  <table>
    <thead><tr><th>Autore</th><th>Libri pubblicati</th></tr></thead><tbody>
    <?php
      $q=$mysqli->query('SELECT CONCAT(a.last_name, " ", a.first_name) AS nm, COUNT(*) AS n 
                         FROM author a JOIN book_author ba ON a.id=ba.author_id GROUP BY a.id ORDER BY n DESC, nm');
      while($r=$q->fetch_assoc()){
        echo '<tr><td>'.h($r['nm']).'</td><td>'.h($r['n']).'</td></tr>';
      }
    ?>
    </tbody>
  </table>
</div>
<?php include __DIR__.'/../includes/footer.php'; ?>
