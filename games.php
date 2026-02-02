<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/repositories/DbGameRepository.php';

$gameRepo = new DbGameRepository();


$search = trim($_GET['search'] ?? '');


$page = (int)($_GET['page'] ?? 1);
if ($page < 1) $page = 1;

$perPage = ITEMS_PER_PAGE;

$allGames = $search !== ''
    ? $gameRepo->search($search)
    : $gameRepo->findAll();


$allGames = array_reverse($allGames);

// Pagination
$totalItems = count($allGames);
$totalPages = (int)ceil($totalItems / $perPage);
$offset = ($page - 1) * $perPage;
$games = array_slice($allGames, $offset, $perPage);


$pageTitle = 'Mini-Games';
$currentPage = 'games';
include __DIR__ . '/includes/header.php';
?>

<section class="hero">
  <h1 class="hero-title">Mini-Games</h1>
  <p class="hero-sub">
    Browse our collection of leadership training mini-games.
  </p>
  <div class="hero-buttons">
    <a href="<?= BASE_URL ?>/home.php" class="btn-primary">Back to Home</a>
  </div>
</section>

<section class="featured-games">
  <div class="news-wrap">

    <form method="GET" action="<?= BASE_URL ?>/games.php" class="news-search">
      <input
        type="search"
        name="search"
        class="news-search-input"
        placeholder="Search games..."
        value="<?= htmlspecialchars($search) ?>"
      >
      <button type="submit" class="btn-primary news-search-btn">Search</button>

      <?php if ($search !== ''): ?>
        <a href="<?= BASE_URL ?>/games.php" class="btn-primary news-search-btn">Clear</a>
      <?php endif; ?>
    </form>

    <?php if (empty($games)): ?>
      <h2 class="section-title">
        <?= $search !== '' ? 'No games found' : 'No games available' ?>
      </h2>
      <p class="news-empty">
        <?= $search !== '' ? 'No games match your search.' : 'No games available yet.' ?>
      </p>
    <?php else: ?>

      <h2 class="section-title">
        <?= $search !== '' ? 'Search Results' : 'All Games' ?>
      </h2>

      <div class="games-grid">
        <?php foreach ($games as $game): ?>
          <div class="game-card">

            <?php if (!empty($game['image_path'])): ?>
              <img
                src="<?= htmlspecialchars($game['image_path']) ?>"
                alt="<?= htmlspecialchars($game['title']) ?>"
                class="admin-game-image"
              >
            <?php endif; ?>

            <h3 class="game-title">
              <?= htmlspecialchars($game['title']) ?>
            </h3>

            <p class="game-desc">
              <?php
                $desc = $game['description'] ?? '';
                echo htmlspecialchars(mb_substr($desc, 0, 140));
                echo mb_strlen($desc) > 140 ? '...' : '';
              ?>
            </p>

            <a href="#" class="btn-primary">Play Now</a>
          </div>
        <?php endforeach; ?>
      </div>

      <?php if ($totalPages > 1): ?>
        <div class="leaderboard-pagination">

          <?php if ($page > 1): ?>
            <a
              href="<?= BASE_URL ?>/games.php?page=<?= $page - 1 ?><?= $search !== '' ? '&search=' . urlencode($search) : '' ?>"
              class="btn-primary"
            >
              Previous
            </a>
          <?php endif; ?>

          <span class="leaderboard-page-info">
            Page <?= $page ?> of <?= $totalPages ?>
          </span>

          <?php if ($page < $totalPages): ?>
            <a
              href="<?= BASE_URL ?>/games.php?page=<?= $page + 1 ?><?= $search !== '' ? '&search=' . urlencode($search) : '' ?>"
              class="btn-primary"
            >
              Next
            </a>
          <?php endif; ?>

        </div>
      <?php endif; ?>

    <?php endif; ?>

  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>