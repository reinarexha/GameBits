<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/repositories/DbNewsRepository.php';

$newsRepo = new DbNewsRepository();

$searchQuery = $_GET['search'] ?? '';
$page = (int)($_GET['page'] ?? 1);
if ($page < 1) $page = 1;

$perPage = ITEMS_PER_PAGE;

$allNews = $searchQuery ? $newsRepo->search($searchQuery) : $newsRepo->findAll();
$allNews = array_reverse($allNews);

// pagination
$totalItems = count($allNews);
$totalPages = (int)ceil($totalItems / $perPage);
$offset = ($page - 1) * $perPage;
$news = array_slice($allNews, $offset, $perPage);

$pageTitle = 'News';
$currentPage = 'news';
include __DIR__ . '/includes/header.php';
?>

<section class="hero">
  <h1 class="hero-title">News</h1>
  <p class="hero-sub">Stay updated with the latest news and updates from Gamebits.</p>
</section>

<section class="news-wrap">
  <form method="GET" action="<?= BASE_URL ?>/news.php" class="news-search">
    <input
      id="publicNewsSearch"
      type="search"
      name="search"
      placeholder="Search news..."
      value="<?= htmlspecialchars($searchQuery) ?>"
      class="news-search-input"
    >
    <button type="submit" class="btn-primary news-search-btn">Search</button>

    <?php if ($searchQuery): ?>
      <a href="<?= BASE_URL ?>/news.php" class="btn-primary news-search-btn">Clear</a>
    <?php endif; ?>
  </form>

  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></div>
  <?php endif; ?>

  <?php if (empty($news)): ?>
    <p class="news-empty">
      <?= $searchQuery ? 'No news items found matching your search.' : 'No news items available yet.'; ?>
    </p>
  <?php else: ?>
    <div class="news-list">
      <?php foreach ($news as $item): ?>
        <?php
          $id = (int)($item['id'] ?? 0);
          $title = $item['title'] ?? '';
          $body  = $item['body'] ?? '';
          $createdAt = $item['created_at'] ?? '';
          $attachmentPath = $item['attachment_path'] ?? '';
          $attachmentType = $item['attachment_type'] ?? '';

          $excerpt = $body;
        ?>

        <div class="game-card news-card">
          <div class="news-row">
            <?php if (!empty($attachmentPath) && $attachmentType === 'image'): ?>
              <img
                src="<?= htmlspecialchars($attachmentPath) ?>"
                alt="<?= htmlspecialchars($title) ?>"
                class="news-thumb"
              >
            <?php endif; ?>

            <div class="news-content">
              <h2 class="news-title">
                <a class="news-title-link" href="<?= BASE_URL ?>/news_details.php?id=<?= $id ?>">
                  <?= htmlspecialchars($title) ?>
                </a>
              </h2>

              <p class="news-date">
                <?= $createdAt ? htmlspecialchars(date('F d, Y', strtotime($createdAt))) : '—' ?>
              </p>

              <p class="news-excerpt">
                <?php
                  echo htmlspecialchars(substr($excerpt, 0, 300));
                  echo strlen($excerpt) > 300 ? '...' : '';
                ?>
              </p>

              <div class="news-actions">
                <a href="<?= BASE_URL ?>/news_details.php?id=<?= $id ?>" class="btn-primary">Read More</a>

                <?php if (!empty($attachmentPath) && $attachmentType === 'pdf'): ?>
                  <a class="news-pdf-link" href="<?= htmlspecialchars($attachmentPath) ?>" target="_blank" rel="noopener">
                    Download PDF
                  </a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>

      <?php endforeach; ?>
    </div>

    <?php if ($totalPages > 1): ?>
      <div class="news-pagination">
        <?php if ($page > 1): ?>
          <a class="btn-primary"
             href="<?= BASE_URL ?>/news.php?page=<?= $page - 1 ?><?= $searchQuery ? '&search=' . urlencode($searchQuery) : '' ?>">
            Previous
          </a>
        <?php endif; ?>

        <span class="news-page-info">Page <?= $page ?> of <?= $totalPages ?></span>

        <?php if ($page < $totalPages): ?>
          <a class="btn-primary"
             href="<?= BASE_URL ?>/news.php?page=<?= $page + 1 ?><?= $searchQuery ? '&search=' . urlencode($searchQuery) : '' ?>">
            Next
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>