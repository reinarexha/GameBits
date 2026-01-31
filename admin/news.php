<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../repositories/JsonNewsRepository.php';

// $newsRepo = new JsonNewsRepository();

$searchQuery = $_GET['search'] ?? '';
$page = (int)($_GET['page'] ?? 1);
if ($page < 1) $page = 1;

$perPage = ITEMS_PER_PAGE;

$allNews = $searchQuery ? $newsRepo->search($searchQuery) : $newsRepo->findAll();

// newest first
$allNews = array_reverse($allNews);

// pagination
$totalItems = count($allNews);
$totalPages = (int)ceil($totalItems / $perPage);
$offset = ($page - 1) * $perPage;
$news = array_slice($allNews, $offset, $perPage);

$pageTitle = 'Manage News';
$currentPage = 'news';
include __DIR__ . '/../includes/admin_header.php';
?>

<section class="hero admin-hero">
  <h1 class="hero-title">Manage News</h1>
  <p class="hero-sub">Create, edit, and delete news items.</p>
  <div class="hero-buttons">
    <a href="<?= BASE_URL ?>/admin/news_create.php" class="btn-primary">Create News Item</a>
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

  <form method="GET" action="<?= BASE_URL ?>/admin/news.php" class="admin-search">
    <input
      id="adminNewsSearch"
      type="search"
      name="search"
      placeholder="Search news..."
      value="<?= htmlspecialchars($searchQuery) ?>"
      class="admin-search-input"
    >
    <button type="submit" class="btn-primary admin-search-btn">Search</button>

    <?php if ($searchQuery): ?>
      <a href="<?= BASE_URL ?>/admin/news.php" class="btn-primary admin-search-btn">Clear</a>
    <?php endif; ?>
  </form>

  <?php if (empty($news)): ?>
    <p class="admin-empty">
      <?= $searchQuery ? 'No news items found matching your search.' : 'No news items yet. Create your first news item!' ?>
    </p>
  <?php else: ?>
    <div class="admin-news-list">
      <?php foreach ($news as $item): ?>
        <?php
          $title = $item['title'] ?? '';
          $body  = $item['body'] ?? '';
          $createdAt = $item['created_at'] ?? '';
          $attachmentPath = $item['attachment_path'] ?? '';
          $attachmentType = $item['attachment_type'] ?? '';
          $isImage = ($attachmentType === 'image');
          $isPdf = ($attachmentType === 'pdf');

          $searchText = trim($title . ' ' . $body);
        ?>

        <div class="game-card admin-news-card" data-search="<?= htmlspecialchars($searchText) ?>">
          <div class="admin-news-row">
            <?php if (!empty($attachmentPath) && $isImage): ?>
              <img
                src="<?= htmlspecialchars($attachmentPath) ?>"
                alt="<?= htmlspecialchars($title) ?>"
                class="admin-news-thumb"
              >
            <?php endif; ?>

            <div class="admin-news-content">
              <h3 class="admin-news-title"><?= htmlspecialchars($title) ?></h3>

              <p class="admin-news-excerpt">
                <?php
                  echo htmlspecialchars(substr($body, 0, 200));
                  echo strlen($body) > 200 ? '...' : '';
                ?>
              </p>

              <div class="admin-news-meta">
                <span class="admin-news-date">
                  <?= $createdAt ? htmlspecialchars(date('M d, Y', strtotime($createdAt))) : '—' ?>
                </span>

                <?php if (!empty($attachmentPath)): ?>
                  <span class="admin-news-badge">
                    <?= htmlspecialchars(strtoupper($attachmentType ?: 'FILE')) ?>
                  </span>
                <?php endif; ?>

                <?php if (!empty($attachmentPath) && $isPdf): ?>
                  <a class="admin-news-file" href="<?= htmlspecialchars($attachmentPath) ?>" target="_blank" rel="noopener">
                    Open PDF
                  </a>
                <?php endif; ?>
              </div>

              <div class="admin-card-actions">
                <a
                  href="<?= BASE_URL ?>/admin/news_edit.php?id=<?= (int)($item['id'] ?? 0) ?>"
                  class="btn-primary btn-small"
                >
                  Edit
                </a>

                <form
                  method="POST"
                  action="<?= BASE_URL ?>/admin/delete_news.php"
                  class="admin-inline-form"
                  data-confirm="delete"
                >
                  <input type="hidden" name="id" value="<?= (int)($item['id'] ?? 0) ?>">
                  <button type="submit" class="btn-primary btn-small btn-danger">Delete</button>
                </form>
              </div>
            </div>
          </div>
        </div>

      <?php endforeach; ?>
    </div>

    <?php if ($totalPages > 1): ?>
      <div class="admin-pagination">
        <?php if ($page > 1): ?>
          <a
            class="btn-primary"
            href="<?= BASE_URL ?>/admin/news.php?page=<?= $page - 1 ?><?= $searchQuery ? '&search=' . urlencode($searchQuery) : '' ?>"
          >
            Previous
          </a>
        <?php endif; ?>

        <span class="admin-page-info">Page <?= $page ?> of <?= $totalPages ?></span>

        <?php if ($page < $totalPages): ?>
          <a
            class="btn-primary"
            href="<?= BASE_URL ?>/admin/news.php?page=<?= $page + 1 ?><?= $searchQuery ? '&search=' . urlencode($searchQuery) : '' ?>"
          >
            Next
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</section>

<?php include __DIR__ . '/../includes/admin_footer.php'; ?>
