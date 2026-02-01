<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../repositories/DbGameRepository.php';

$gameRepo = new DbGameRepository();

$searchQuery = $_GET['search'] ?? '';
$page = (int)($_GET['page'] ?? 1);
if ($page < 1) $page = 1;

$perPage = ITEMS_PER_PAGE;

// Get games (all or search results)
$allGames = $searchQuery ? $gameRepo->search($searchQuery) : $gameRepo->findAll();

// Show newest first
$allGames = array_reverse($allGames);

// Pagination (split into pages)
$totalItems = count($allGames);
$totalPages = (int)ceil($totalItems / $perPage);
$offset = ($page - 1) * $perPage;
$games = array_slice($allGames, $offset, $perPage);

$pageTitle = 'Manage Games';
$currentPage = 'games';
include __DIR__ . '/../includes/admin_header.php';
?>

<section class="hero admin-hero">
  <h1 class="hero-title">Manage Games</h1>
  <p class="hero-sub">Create, edit, and delete mini-games.</p>
  <div class="hero-buttons">
    <a href="<?= BASE_URL ?>/admin/game_create.php" class="btn-primary">Create New Game</a>
  </div>
</section>

<section class="admin-wrap">
  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
      <?= htmlspecialchars($_GET['success']) ?>
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-error">
      <?= htmlspecialchars($_GET['error']) ?>
    </div>
  <?php endif; ?>

  <form method="GET" action="<?= BASE_URL ?>/admin/games.php" class="admin-search">
    <input
      id="adminGameSearch"
      type="search"
      name="search"
      placeholder="Search games..."
      value="<?= htmlspecialchars($searchQuery) ?>"
      class="admin-search-input"
>

    <button type="submit" class="btn-primary admin-search-btn">Search</button>

    <?php if ($searchQuery): ?>
      <a href="<?= BASE_URL ?>/admin/games.php" class="btn-primary admin-search-btn">Clear</a>
    <?php endif; ?>
  </form>

  <?php if (empty($games)): ?>
    <p class="admin-empty">
      <?= $searchQuery ? 'No games found matching your search.' : 'No games yet. Create your first game!' ?>
    </p>
  <?php else: ?>
    <div class="games-grid admin-games-grid"
    data-search="<?= htmlspecialchars(($game['title'] ?? '') . ' ' . ($game['description'] ?? '')) ?>">
      <?php foreach ($games as $game): ?>
        <div class="game-card admin-game-card">
          <?php if (!empty($game['image_path'])): ?>
            <img
              src="<?= htmlspecialchars($game['image_path']) ?>"
              alt="<?= htmlspecialchars($game['title']) ?>"
              class="admin-game-image"
            >
          <?php endif; ?>

          <h3 class="game-title admin-game-title">
            <?= htmlspecialchars($game['title']) ?>
          </h3>

          <p class="game-desc admin-game-desc">
            <?php
              $desc = $game['description'] ?? '';
              echo htmlspecialchars(substr($desc, 0, 100));
              echo strlen($desc) > 100 ? '...' : '';
            ?>
          </p>

          <div class="admin-card-actions">
            <a
              href="<?= BASE_URL ?>/admin/game_edit.php?id=<?= (int)$game['id'] ?>"
              class="btn-primary btn-small"
            >
              Edit
            </a>

            <form
              method="POST"
              action="<?= BASE_URL ?>/admin/delete_game.php"
              class="admin-inline-form"
              onsubmit="return confirm('Are you sure you want to delete this game?');"
            >
              <input type="hidden" name="id" value="<?= (int)$game['id'] ?>">
              <button type="submit" class="btn-primary btn-small btn-danger">Delete</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <?php if ($totalPages > 1): ?>
      <div class="admin-pagination">
        <?php if ($page > 1): ?>
          <a
            class="btn-primary"
            href="<?= BASE_URL ?>/admin/games.php?page=<?= $page - 1 ?><?= $searchQuery ? '&search=' . urlencode($searchQuery) : '' ?>"
          >
            Previous
          </a>
        <?php endif; ?>

        <span class="admin-page-info">Page <?= $page ?> of <?= $totalPages ?></span>

        <?php if ($page < $totalPages): ?>
          <a
            class="btn-primary"
            href="<?= BASE_URL ?>/admin/games.php?page=<?= $page + 1 ?><?= $searchQuery ? '&search=' . urlencode($searchQuery) : '' ?>"
          >
            Next
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</section>

<?php include __DIR__ . '/../includes/admin_footer.php'; ?>