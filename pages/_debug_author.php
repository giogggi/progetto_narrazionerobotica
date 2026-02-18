
<!-- verifica che la ricerca per autori sia corretta-->
<?php
require __DIR__.'/../includes/db.php';
header('Content-Type: text/plain; charset=utf-8');

$diag = [];
$diag['authors_count'] = 0;
if ($res = $mysqli->query('SELECT COUNT(*) AS n FROM author')) {
  $diag['authors_count'] = (int)($res->fetch_assoc()['n'] ?? 0);
}
$diag['base_5'] = [];
if ($res2 = $mysqli->query('SELECT id, first_name, last_name FROM author ORDER BY last_name LIMIT 5')) {
  while($r=$res2->fetch_assoc()) $diag['base_5'][] = $r;
}
$q = $_GET['q'] ?? 'Rowling';
$esc = $mysqli->real_escape_string($q);
$sqlRaw = "SELECT id, first_name, last_name FROM author
           WHERE first_name LIKE '%$esc%' OR last_name LIKE '%$esc%'
              OR birth_place LIKE '%$esc%' OR CONCAT(first_name,' ',last_name) LIKE '%$esc%'
              OR CONCAT(last_name,' ',first_name) LIKE '%$esc%'
           ORDER BY last_name LIMIT 20";
$diag['raw_sql'] = $sqlRaw;
$diag['raw_rows'] = [];
if ($res3 = $mysqli->query($sqlRaw)) {
  while($r=$res3->fetch_assoc()) $diag['raw_rows'][] = $r;
} else {
  $diag['raw_error'] = $mysqli->error;
}
print_r($diag);
