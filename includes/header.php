<?php
//shared header
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
<?php

$scriptPath = $_SERVER['SCRIPT_NAME'] ?? '';
$basePath = str_replace(['/games.php', '/leaderboard.php', '/news.php', '/news_details.php'], '', $scriptPath);
if (empty($basePath) || $basePath === '/') {
    $basePath = '';
}
?>
  <link rel="stylesheet" href="<?php echo $basePath; ?>/css/styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
  <header>
    <nav class="navbar">
      <div class="nav-container">
        <a class="nav-brand" href="<?php echo $basePath; ?>/index.html">Gamebits</a>
        <ul class="nav-menu">
          <li class="nav-item"><a class="nav-link <?php echo $currentPage === 'home' ? 'active' : ''; ?>" href="<?php echo $basePath; ?>/pages/home.html">Home</a></li>
          <li class="nav-item"><a class="nav-link <?php echo $currentPage === 'games' ? 'active' : ''; ?>" href="<?php echo $basePath; ?>/games.php">Mini-Games</a></li>
          <li class="nav-item"><a class="nav-link <?php echo $currentPage === 'leaderboard' ? 'active' : ''; ?>" href="<?php echo $basePath; ?>/leaderboard.php">Leaderboard</a></li>
          <li class="nav-item"><a class="nav-link <?php echo $currentPage === 'news' ? 'active' : ''; ?>" href="<?php echo $basePath; ?>/news.php">News</a></li>
          <li class="nav-item"><a class="nav-link <?php echo $currentPage === 'about' ? 'active' : ''; ?>" href="<?php echo $basePath; ?>/pages/aboutus.html">About</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo $basePath; ?>/auth/signin.html">Log In</a></li>
        </ul>
        <form class="nav-search" method="GET" action="<?php echo $basePath; ?>/games.php">
          <input type="search" name="search" placeholder="Search games…" aria-label="Search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        </form>
      </div>
    </nav>
  </header>
  <main>