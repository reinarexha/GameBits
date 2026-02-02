<?php

if (!isset($currentPage)) {
    $currentPage = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' • ' : ''; ?>Gamebits</title>

  <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
  <header>
    <nav class="navbar">
      <div class="nav-container">
        <a class="nav-brand" href="<?= BASE_URL ?>/index.php">Gamebits</a>

        <ul class="nav-menu">
          <li class="nav-item">
            <a class="nav-link <?= $currentPage === 'home' ? 'active' : ''; ?>" href="<?= BASE_URL ?>/pages/home.html">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $currentPage === 'games' ? 'active' : ''; ?>" href="<?= BASE_URL ?>/games.php">Mini-Games</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $currentPage === 'leaderboard' ? 'active' : ''; ?>" href="<?= BASE_URL ?>/leaderboard.php">Leaderboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $currentPage === 'news' ? 'active' : ''; ?>" href="<?= BASE_URL ?>/news.php">News</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $currentPage === 'about' ? 'active' : ''; ?>" href="<?= BASE_URL ?>/pages/aboutus.html">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>/auth/signin.html">Log In</a>
          </li>
        </ul>

        <form class="nav-search" method="GET" action="<?= BASE_URL ?>/games.php">
          <input
            type="search"
            name="search"
            placeholder="Search games…"
            aria-label="Search"
            value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
          >
        </form>

      </div>
    </nav>
  </header>
  <main>