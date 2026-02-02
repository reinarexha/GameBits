<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/repositories/DbGameRepository.php';

$gameRepo = new DbGameRepository();


function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function difficultyStars(?string $difficulty): string {
    return match (strtolower((string)$difficulty)) {
        'easy'   => '★★☆☆☆',
        'medium' => '★★★☆☆',
        'hard'   => '★★★★☆',
        default  => '',
    };
}


function toPublicUrl(string $path): string {
    $path = trim($path);
    if ($path === '') return '';

    if (preg_match('~^https?://~i', $path)) {
        return $path;
    }

    
    $path = preg_replace('~^\./+~', '', $path);


    $base = rtrim((string)BASE_URL, '/');
    if ($base !== '' && str_starts_with($path, $base . '/')) {
        return $path;
    }

    
    if ($path[0] !== '/') {
        $path = '/' . $path;
    }

  
    return $base . $path;
}



$search = trim((string)($_GET['search'] ?? ''));

$page = (int)($_GET['page'] ?? 1);
if ($page < 1) $page = 1;

$perPage = defined('ITEMS_PER_PAGE') ? (int)ITEMS_PER_PAGE : 12;
if ($perPage < 1) $perPage = 12;


$allGames = ($search !== '')
    ? $gameRepo->search($search)
    : $gameRepo->findAll();

$totalItems = count($allGames);
$totalPages = max(1, (int)ceil($totalItems / $perPage));

if ($page > $totalPages) $page = $totalPages;

$offset = ($page - 1) * $perPage;
$games = array_slice($allGames, $offset, $perPage);

function gamesUrl(int $page, string $search): string {
    $params = ['page' => $page];
    if ($search !== '') $params['search'] = $search;
    return rtrim(BASE_URL, '/') . '/games.php?' . http_build_query($params);
}

$pageTitle = 'Mini-Games';
$currentPage = 'games';
include __DIR__ . '/includes/header.php';
?>

<style>
  
  .games-page-wrap { max-width: 1100px; margin: 0 auto; padding: 0 16px; }

  .games-grid{
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 16px;
    align-items: stretch;
  }

  .game-card{
    display:flex;
    flex-direction: column;
    border-radius: 16px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 6px 18px rgba(0,0,0,.08);
    min-height: 100%;
  }

  .game-thumb{
    width: 100%;
    aspect-ratio: 16 / 9;
    background: #f3f4f6;
    overflow: hidden;
  }

  .game-thumb img{
    width:100%;
    height:100%;
    display:block;
    object-fit: cover;
    object-position: center;
  }

  .game-thumb-placeholder{
    height:100%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:600;
    opacity:.6;
  }

  .game-content{
    padding: 14px 14px 10px;
    display:flex;
    flex-direction: column;
    gap: 8px;
    flex: 1;
  }

  .game-title{
    font-weight: 700;
    font-size: 1.05rem;
    line-height: 1.2;
    margin: 0;
  }

  .game-desc{
    opacity: .85;
    line-height: 1.35;
    font-size: .95rem;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 3.9em;
  }

  .difficulty{
    margin-top: auto;
    opacity: .9;
    font-size: .9rem;
  }

  .game-actions{
    padding: 0 14px 14px;
  }

  .btn-small{
    display:block;
    text-align:center;
    padding: 10px 12px;
    border-radius: 10px;
  }

  .news-search{
    display:flex;
    gap: 10px;
    flex-wrap: wrap;
    margin: 14px 0 22px;
  }

  .news-search-input{
    flex: 1;
    min-width: 220px;
  }

  .leaderboard-pagination{
    display:flex;
    gap: 12px;
    align-items:center;
    justify-content:center;
    margin: 22px 0 8px;
    flex-wrap: wrap;
  }

  .leaderboard-page-info{ opacity:.8; }
</style>

<section class="hero">
  <h1 class="hero-title">Mini-Games</h1>
  <p class="hero-sub">Browse our collection of leadership training mini-games.</p>
  <div class="hero-buttons">
    <a href="<?= e(rtrim(BASE_URL, '/')) ?>/home.php" class="btn-primary">Back to Home</a>
  </div>
</section>

<section class="featured-games">
  <div class="games-page-wrap">

    <form method="GET" action="<?= e(rtrim(BASE_URL, '/')) ?>/games.php" class="news-search">
      <input
        type="search"
        name="search"
        class="news-search-input"
        placeholder="Search games..."
        value="<?= e($search) ?>"
      >
      <button type="submit" class="btn-primary news-search-btn">Search</button>

      <?php if ($search !== ''): ?>
        <a href="<?= e(rtrim(BASE_URL, '/')) ?>/games.php" class="btn-primary news-search-btn">Clear</a>
      <?php endif; ?>
    </form>

    <?php if (empty($games)): ?>
      <h2 class="section-title"><?= $search !== '' ? 'No games found' : 'No games available' ?></h2>
      <p class="news-empty"><?= $search !== '' ? 'No games match your search.' : 'No games available yet.' ?></p>
    <?php else: ?>

      <h2 class="section-title"><?= $search !== '' ? 'Search Results' : 'All Games' ?></h2>

      <div class="games-grid">
        <?php foreach ($games as $game): ?>
          <?php
            $title   = (string)($game['title'] ?? 'Untitled');
            $desc    = (string)($game['description'] ?? '');
            $imgRaw  = (string)($game['image_path'] ?? '');
            $imgUrl  = $imgRaw !== '' ? toPublicUrl($imgRaw) : '';

            $stars   = difficultyStars($game['difficulty'] ?? null);
            $label   = (string)($game['difficulty'] ?? '');
            $coming  = !empty($game['is_coming_soon']);

            $playUrlRaw = (string)($game['play_url'] ?? '');
            $playUrl = $coming ? '#' : ($playUrlRaw !== '' ? toPublicUrl($playUrlRaw) : '#');
          ?>

          <div class="game-card">
            <div class="game-thumb">
              <?php if ($imgUrl !== ''): ?>
                <img src="<?= e($imgUrl) ?>" alt="<?= e($title) ?>" loading="lazy">
              <?php else: ?>
                <div class="game-thumb-placeholder">No Image</div>
              <?php endif; ?>
            </div>

            <div class="game-content">
              <div class="game-title"><?= e($title) ?></div>
              <div class="game-desc"><?= e($desc) ?></div>

              <?php if ($stars !== ''): ?>
                <div class="difficulty">
                  <?= e($stars) ?> - <?= e(ucfirst($label)) ?>
                </div>
              <?php endif; ?>
            </div>

            <div class="game-actions">
              <a
                href="<?= e($playUrl) ?>"
                class="btn-small"
                <?= $coming ? 'onclick="return false;" aria-disabled="true"' : '' ?>
              >
                <?= $coming ? 'Coming Soon' : 'Play Now' ?>
              </a>
            </div>
          </div>

        <?php endforeach; ?>
      </div>

      <?php if ($totalPages > 1): ?>
        <div class="leaderboard-pagination">
          <?php if ($page > 1): ?>
            <a href="<?= e(gamesUrl($page - 1, $search)) ?>" class="btn-primary">Previous</a>
          <?php endif; ?>

          <span class="leaderboard-page-info">Page <?= (int)$page ?> of <?= (int)$totalPages ?></span>

          <?php if ($page < $totalPages): ?>
            <a href="<?= e(gamesUrl($page + 1, $search)) ?>" class="btn-primary">Next</a>
          <?php endif; ?>
        </div>
      <?php endif; ?>

    <?php endif; ?>

  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
