<?php
require __DIR__.'/../includes/db.php';
//legge id del prestito se manca o non e' valido è 0
$id = intval($_GET['id'] ?? 0);
//se id e' valido
if ($id) {
  //inserisce all'ID del prestito la data corrente se non e' gia stato restituito
  $stmt = $mysqli->prepare('UPDATE loan SET return_date=CURDATE() WHERE id=? AND return_date IS NULL');
  $stmt->bind_param('i', $id);
  $stmt->execute();
}
//torna a loans_delete
header('Location: /pages/loans_create.php');
exit;
