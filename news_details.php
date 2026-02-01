<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/repositories/DbNewsRepository.php';

$newsRepo = new DbNewsRepository();

$id = (int)($_GET['id'] ?? 0);
$item = $id > 0 ? $newsRepo->findById($id) : null;

$pageTitle = 'News Details';
$currentPage = 'news';

if (!$item) {
  if (file_exists(__DIR__ . '/includes/header.php')) include __DIR__ . '/includes/header.php';
  ?>
  <section class="hero">
    <h1 class="hero-title">News not found</h1>
    <p class="hero-sub">The news item you are looking for does not exist.</p>
    <div class="hero-buttons">
      <a href="<?= BASE_URL ?>/news.php" class="btn-primary">Back to News</a>
    </div>
  </section>
  <?php
  if (file_exists(__DIR__ . '/includes/footer.php')) include __DIR__ . '/includes/footer.php';
  exit;
}

$title = $item['title'] ?? '';
$body  = $item['body'] ?? '';
$createdAt = $item['created_at'] ?? '';
$attachmentPath = $item['attachment_path'] ?? '';
$attachmentType = $item['attachment_type'] ?? '';

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
  <h1 class="hero-title"><?= htmlspecialchars($title) ?></h1>
  <p class="hero-sub">
    <?= $createdAt ? htmlspecialchars(date('M d, Y', strtotime($createdAt))) : '' ?>
  </p>
  <div class="hero-buttons">
    <a href="<?= BASE_URL ?>/news.php" class="btn-primary">Back to News</a>
  </div>
</section>

<section class="admin-wrap">
  <div class="game-card admin-news-details-card">
    <?php if ($attachmentType === 'image' && !empty($attachmentPath)): ?>
      <img
        src="<?= htmlspecialchars($attachmentPath) ?>"
        alt="<?= htmlspecialchars($title) ?>"
        class="admin-news-details-image"
      >
    <?php endif; ?>

    <div class="admin-news-details-body">
      <p class="admin-news-details-text"><?= nl2br(htmlspecialchars($body)) ?></p>

      <?php if ($attachmentType === 'pdf' && !empty($attachmentPath)): ?>
        <div class="admin-news-details-file">
          <a class="btn-primary" href="<?= htmlspecialchars($attachmentPath) ?>" target="_blank" rel="noopener">
            Open PDF
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>
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