<?php

 // Admin header 
 
if (!isset($currentPage)) {
  $currentPage = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' • ' : '' ?>Admin • Gamebits</title>

  <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
<header>
  <nav class="navbar">
    <div class="nav-container">
      <a class="nav-brand" href="<?= BASE_URL ?>/admin/dashboard.php">Admin • Gamebits</a>
      <ul class="nav-menu">
        <li class="nav-item">
          <a class="nav-link" href="<?= BASE_URL ?>/index.php">View Site</a>
        </li>
      </ul>
    </div>
  </nav>

  <nav class="admin-nav">
    <div class="admin-nav-container">
      <a href="<?= BASE_URL ?>/admin/dashboard.php" class="admin-nav-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
      <a href="<?= BASE_URL ?>/admin/games.php" class="admin-nav-link <?= $currentPage === 'games' ? 'active' : '' ?>">Games</a>
      <a href="<?= BASE_URL ?>/admin/news.php" class="admin-nav-link <?= $currentPage === 'news' ? 'active' : '' ?>">News</a>
    </div>
  </nav>
</header>

<main>