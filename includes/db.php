

<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'unife_biblioteca';
 //crea l'oggetto mysqli e tenta la connessione al DB
$mysqli = new mysqli($host, $user, $pass, $db);
//connessione fallita mostra errore
if ($mysqli->connect_errno) {
  http_response_code(500);
  echo '<div class="alert error">Errore connessione MySQL: ' . htmlspecialchars($mysqli->connect_error, ENT_QUOTES, 'UTF-8') . '</div>';
  exit;
}
$mysqli->set_charset('utf8mb4');
function h($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }

// Helper per escape HTML sicuro
  function db_stmt_all_assoc(mysqli_stmt $stmt){
    $rows = [];
    if (!$stmt || !$stmt->execute()) { return $rows; }

    if (method_exists($stmt, 'get_result')) {
      if ($res = $stmt->get_result()) {
        while ($r = $res->fetch_assoc()) { $rows[] = $r; }
        $res->free();
      }
      return $rows;
    }

    $stmt->store_result();
    $meta = $stmt->result_metadata();
    if (!$meta) { $stmt->free_result(); return $rows; }
    //prepara strutture per il bind dinamico dei campi
    $fields = []; $row = []; $bind = [];
    while ($field = $meta->fetch_field()) {
      $fields[] = $field->name;
      $row[$field->name] = null;
      $bind[] = &$row[$field->name];
    }
    if (method_exists($meta, 'free')) { $meta->free(); }

    call_user_func_array([$stmt, 'bind_result'], $bind);
    while ($stmt->fetch()) {
      $copy = [];
      foreach ($fields as $name) { $copy[$name] = $row[$name]; }
      $rows[] = $copy;
    }
    
    $stmt->free_result();
    return $rows;
  }



