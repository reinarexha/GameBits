<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../repositories/DbGameRepository.php';
require_once __DIR__ . '/../repositories/DbNewsRepository.php';
require_once __DIR__ . '/../repositories/DbScoreRepository.php';

$gameRepo = new DbGameRepository();
$newsRepo = new DbNewsRepository();
$scoreRepo = new DbScoreRepository();

$games  = $gameRepo->findAll();
$news   = $newsRepo->findAll();
$scores = $scoreRepo->findAll();

$stats = [
  'total_games'  => count($games),
  'total_news'   => count($news),
  'total_scores' => count($scores),
  'total_users'  => count(array_unique(array_column($scores, 'user_id'))),
];

$pageTitle = 'Dashboard';
$currentPage = 'dashboard';
include __DIR__ . '/../includes/admin_header.php';
?>

<section class="hero">
  <h1 class="hero-title">Admin Dashboard</h1>
  <p class="hero-sub">Overview of your Gamebits content and statistics.</p>
</section>

<section class="admin-wrap">
  <div class="games-grid admin-grid">
    <div class="game-card admin-stat-card">
      <h3 class="admin-stat-number"><?= (int)$stats['total_games'] ?></h3>
      <p class="admin-stat-label">Total Games</p>
      <a href="<?= BASE_URL ?>/admin/games.php" class="btn-primary admin-stat-btn">Manage Games</a>
    </div>

    <div class="game-card admin-stat-card">
      <h3 class="admin-stat-number"><?= (int)$stats['total_news'] ?></h3>
      <p class="admin-stat-label">News Items</p>
      <a href="<?= BASE_URL ?>/admin/news.php" class="btn-primary admin-stat-btn">Manage News</a>
    </div>

    <div class="game-card admin-stat-card">
      <h3 class="admin-stat-number"><?= (int)$stats['total_scores'] ?></h3>
      <p class="admin-stat-label">Total Scores</p>
    </div>

    <div class="game-card admin-stat-card">
      <h3 class="admin-stat-number"><?= (int)$stats['total_users'] ?></h3>
      <p class="admin-stat-label">Unique Players</p>
    </div>
  </div>

  <div class="admin-actions">
    <h2 class="section-title">Quick Actions</h2>
    <div class="admin-actions-row">
      <a href="<?= BASE_URL ?>/admin/game_create.php" class="btn-primary">Create New Game</a>
      <a href="<?= BASE_URL ?>/admin/news_create.php" class="btn-primary">Create News Item</a>
      <a href="<?= BASE_URL ?>/leaderboard.php" class="btn-primary">View Leaderboard</a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/../includes/admin_footer.php'; ?>