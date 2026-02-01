<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/repositories/DbScoreRepository.php';

$scoreRepo = new DbScoreRepository();
$scoresWithGames = $scoreRepo->findAllWithGames();


usort($scoresWithGames, function ($a, $b) {
    return ((int)($b['score'] ?? 0)) <=> ((int)($a['score'] ?? 0));
});

// Pagination
$page = (int)($_GET['page'] ?? 1);
if ($page < 1) $page = 1;

$perPage = ITEMS_PER_PAGE;
$totalItems = count($scoresWithGames);
$totalPages = (int)ceil($totalItems / $perPage);
$offset = ($page - 1) * $perPage;

$scores = array_slice($scoresWithGames, $offset, $perPage);

$pageTitle = 'Leaderboard';
$currentPage = 'leaderboard';
include __DIR__ . '/includes/header.php';
?>

<section class="hero">
  <h1 class="hero-title">Leaderboard</h1>
  <p class="hero-sub">View top scores and rankings from players.</p>
  <div class="hero-buttons">
    <a href="<?= BASE_URL ?>/pages/home.html" class="btn-primary">Back to Home</a>
  </div>
</section>

<section class="leaderboard-wrap">
  <?php if (empty($scores)): ?>
    <p class="leaderboard-empty">No scores yet. Be the first to play and set a record!</p>
  <?php else: ?>
    <div class="leaderboard-card">
      <table class="leaderboard-table">
        <thead>
          <tr>
            <th>Rank</th>
            <th>Player</th>
            <th>Game</th>
            <th class="right">Score</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($scores as $index => $score): ?>
            <?php
              $rank = $offset + $index + 1;
              $username = $score['username'] ?? 'Anonymous';
              $gameTitle = $score['game']['title'] ?? 'Unknown Game';
              $scoreValue = (int)($score['score'] ?? 0);
              $createdAt = $score['created_at'] ?? '';
            ?>
            <tr>
              <td class="leaderboard-rank">#<?= $rank ?></td>
              <td class="leaderboard-player"><?= htmlspecialchars($username) ?></td>
              <td class="leaderboard-game"><?= htmlspecialchars($gameTitle) ?></td>
              <td class="leaderboard-score right"><?= number_format($scoreValue) ?></td>
              <td class="leaderboard-date">
                <?= $createdAt ? htmlspecialchars(date('M d, Y', strtotime($createdAt))) : '' ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php if ($totalPages > 1): ?>
      <div class="leaderboard-pagination">
        <?php if ($page > 1): ?>
          <a class="btn-primary" href="<?= BASE_URL ?>/leaderboard.php?page=<?= $page - 1 ?>">Previous</a>
        <?php endif; ?>

        <span class="leaderboard-page-info">Page <?= $page ?> of <?= $totalPages ?></span>

        <?php if ($page < $totalPages): ?>
          <a class="btn-primary" href="<?= BASE_URL ?>/leaderboard.php?page=<?= $page + 1 ?>">Next</a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>