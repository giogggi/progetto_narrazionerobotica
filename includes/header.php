<?php

$uri = $_SERVER['REQUEST_URI'] ?? '/';
function nav_active($needle){ global $uri; return strpos($uri, $needle) !== false ? 'active' : ''; }
?>
<!doctype html>
<html lang="it" data-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Biblioteca UniFE - Demo</title>
  <link rel="stylesheet" href="/assets/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <meta name="color-scheme" content="light dark">
</head>
<body>
<nav>
  <div class="container">
    <div class="brand">Biblioteca UniFE</div>
    <a class="<?php echo nav_active('/index.php'); ?>" href="/index.php"><i class="bi bi-house"></i>&nbsp;Home</a>
    <a class="<?php echo nav_active('/pages/loans_create.php'); ?>" href="/pages/loans_create.php"><i class="bi bi-plus-circle"></i>&nbsp;Nuovo prestito</a>
    <a class="<?php echo nav_active('/pages/loans_search.php'); ?>" href="/pages/loans_search.php"><i class="bi bi-hourglass-split"></i>&nbsp;Ricerca prestiti</a>
    <a class="<?php echo nav_active('/pages/user_create.php'); ?>" href="/pages/user_create.php"><i class="bi bi-person-plus"></i>&nbsp;Nuovo utente</a>
    <a class="<?php echo nav_active('/pages/users_list.php'); ?>" href="/pages/users_list.php"><i class="bi bi-people"></i>&nbsp;Elenco utenti</a>
    <a class="<?php echo nav_active('/pages/books_search.php'); ?>" href="/pages/books_search.php"><i class="bi bi-search"></i>&nbsp;Ricerca libri</a>
    <a class="<?php echo nav_active('/pages/author_books.php'); ?>" href="/pages/author_books.php"><i class="bi bi-journal-text"></i>&nbsp;Libri per autore</a>
    <a class="<?php echo nav_active('/pages/author_search.php'); ?>" href="/pages/author_search.php"><i class="bi bi-person-vcard"></i>&nbsp;Ricerca autori</a>
    <a class="<?php echo nav_active('/pages/stats.php'); ?>" href="/pages/stats.php"><i class="bi bi-bar-chart-line"></i>&nbsp;Statistiche</a>
    <div class="spacer"></div>
    <button id="themeToggle" class="icon-btn" title="Cambia tema" aria-label="Cambia tema">
      <i id="themeIcon" class="bi bi-brightness-high"></i>
    </button>
  </div>
</nav>

<div class="container"><!-- chiusa nel footer.php -->
<script>
  //tema chiaro scuro tramite bottone
(function(){
  const root = document.documentElement;
  const key = 'unife-theme';
  const btn = document.getElementById('themeToggle');
  const icon = document.getElementById('themeIcon');
  const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
  const saved = localStorage.getItem(key);
  const current = saved || (prefersDark ? 'dark' : 'light');
  root.setAttribute('data-theme', current);
  icon.className = current === 'dark' ? 'bi bi-moon-stars' : 'bi bi-brightness-high';

  btn.addEventListener('click', () => {
    const now = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    root.setAttribute('data-theme', now);
    localStorage.setItem(key, now);
    icon.className = now === 'dark' ? 'bi bi-moon-stars' : 'bi bi-brightness-high';
  });
})();
</script>

