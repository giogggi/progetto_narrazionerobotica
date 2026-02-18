
<?php
#libri per autore
require __DIR__.'/../includes/db.php'; include __DIR__.'/../includes/header.php';
$author_id = intval($_GET['author_id'] ?? 0);
?>
<h1><i class="bi bi-journal-text"></i>&nbsp;Libri per autore</h1>  

<style>
/* serve per visualizzare correttamente i dati all'interno della tabella */
.table-wrap table { overflow: visible; }
.table-wrap thead th { position: static; top: auto; z-index: 1; }
.table-wrap tbody { display: table-row-group; }
.table-wrap tbody tr { display: table-row; visibility: visible; opacity: 1; }
</style>

<!-- scegli un autore dalla tendina che contiene i dati del DB, l'ID finisce in ?author_id 
e la pagina usa l'ID per filtrare i libri da mostrare -->
<div class="card" style="max-width:850px">  
  <form method="get" class="actions">
    <div style="flex:1">
      <label>Autore</label>
      <select name="author_id">
        <option value="0">— seleziona —</option>
        <?php
          $q=$mysqli->query('SELECT id, first_name, last_name FROM author ORDER BY last_name');
          if ($q) while($r=$q->fetch_assoc()){
            $sel = $author_id == (int)$r['id'] ? ' selected' : '';
            echo '<option value="'.(int)$r['id'].'"'.$sel.'>'.h($r['last_name'].' '.$r['first_name']).'</option>';
          }
        ?>
      </select>
    </div>
    <div style="align-self:end"><button type="submit"><i class="bi bi-arrow-right-circle"></i>&nbsp;Vedi</button></div>
  </form>
</div>
<!-- se è stato scelto un autore vengono mostrati in tabella l'anno e il titolo -->
<?php if($author_id): ?>
<div class="table-wrap">
  <table>
  <thead><tr><th>Anno</th><th>Titolo</th></tr></thead><tbody>
  <?php
    $res=$mysqli->query('SELECT b.publication_year, b.title_en FROM book b JOIN book_author ba ON b.id=ba.book_id WHERE ba.author_id='.(int)$author_id.' ORDER BY b.publication_year, b.title_en');
    if ($res) while($r=$res->fetch_assoc()){
      echo '<tr><td>'.h($r['publication_year']).'</td><td>'.h($r['title_en']).'</td></tr>';
    }
  ?>
  </tbody></table>
</div>
<?php endif; ?>
<?php include __DIR__.'/../includes/footer.php'; ?>


