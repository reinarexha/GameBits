<?php

require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/models/PageContent.php';

$pageContent = new PageContent();
$heroTitle = $pageContent->getText('home', 'hero_title', 'Welcome to Gamebits');
$heroSubtitle = $pageContent->getText('home', 'hero_subtitle', 'Learn leadership through play. Quick, replayable mini-games designed to build real-life skills.');
$aboutSnippet = $pageContent->getText('home', 'about_snippet', 'Play, reflect, improve, repeat.');

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gamebits</title>
  <link rel="stylesheet" href="../css/styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
  <header>
    <nav class="navbar">
      <div class="nav-container">
        <a class="nav-brand" href="index.php">Gamebits</a>
        <ul class="nav-menu">
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Mini-Games</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Leaderboard</a></li>
          <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
          <li class="nav-item"><a class="nav-link" href="/login.php">Log In</a></li>
        </ul>
        <form class="nav-search">
          <input type="search" placeholder="Search games…" aria-label="Search">
        </form>
      </div>
    </nav>
  </header>
  <main>
    <section class="hero">
      <h1 class="hero-title"><?= htmlspecialchars($heroTitle) ?></h1>
      <p class="hero-sub">
        <?= htmlspecialchars($heroSubtitle) ?>
      </p>
      <p class="hero-sub"><?= htmlspecialchars($aboutSnippet) ?></p>
      <div class="hero-buttons">
        <a href="index.php" class="btn-primary">Get Started</a>
      </div>
    </section>
  </main>
  <footer class="footer">
    <p>&copy; 2024 Gamebits. Learn leadership by doing.</p>
  </footer>
</body>
</html>
