<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/repositories/DbNewsRepository.php';

$newsRepo = new DbGameRepository();
$items = array_reverse($newsRepo->findAll()); // newest first

$pageTitle = 'News';
$currentPage = 'news';

if (file_exists(__DIR__ . '/includes/header.php')) {
  include __DIR__ . '/includes/header.php';
} else {

  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News • Gamebits</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
  </head>
  <body>
  <?php
}
?>

<section class="hero">
  <h1 class="hero-title">News</h1>
  <p class="hero-sub">Latest updates and announcements.</p>
</section>

<section class="admin-wrap">
  <?php if (empty($items)): ?>
    <p class="admin-empty">No news yet.</p>
  <?php else: ?>
    <div class="admin-news-list">
      <?php foreach ($items as $item): ?>
        <?php
          $id = (int)($item['id'] ?? 0);
          $title = $item['title'] ?? '';
          $body  = $item['body'] ?? '';
          $createdAt = $item['created_at'] ?? '';
          $attachmentPath = $item['attachment_path'] ?? '';
          $attachmentType = $item['attachment_type'] ?? '';
        ?>

        <div class="game-card admin-news-card">
          <div class="admin-news-row">
            <?php if ($attachmentType === 'image' && !empty($attachmentPath)): ?>
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
              </div>

              <div class="admin-card-actions">
                <a class="btn-primary btn-small" href="<?= BASE_URL ?>/news_details.php?id=<?= $id ?>">
                  Read more
                </a>

                <?php if ($attachmentType === 'pdf' && !empty($attachmentPath)): ?>
                  <a class="btn-secondary btn-small" href="<?= htmlspecialchars($attachmentPath) ?>" target="_blank" rel="noopener">
                    Open PDF
                  </a>
                <?php endif; ?>
              </div>

            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<?php
if (file_exists(__DIR__ . '/includes/footer.php')) {
  include __DIR__ . '/includes/footer.php';
} else {
  ?>
  <footer class="footer">
    <p>&copy; 2024 Gamebits. Learn leadership by doing.</p>
  </footer>
  </body>
  </html>
  <?php
}
?>