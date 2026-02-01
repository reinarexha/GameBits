<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/repositories/DbNewsRepository.php';

$newsRepo = new DbNewsRepository();

$id = (int)($_GET['id'] ?? 0);
$news = $id > 0 ? $newsRepo->findById($id) : null;

if (!$news) {
  header('Location: ' . BASE_URL . '/news.php?error=' . urlencode('News item not found'));
  exit;
}

$pageTitle = $news['title'] ?? 'News Details';
$currentPage = 'news';
include __DIR__ . '/includes/header.php';

$title = $news['title'] ?? '';
$createdAt = $news['created_at'] ?? '';
$attachmentPath = $news['attachment_path'] ?? '';
$attachmentType = $news['attachment_type'] ?? '';
$body = $news['body'] ?? '';
?>

<section class="hero">
  <h1 class="hero-title"><?= htmlspecialchars($title) ?></h1>
  <p class="hero-sub news-details-date">
    <?= $createdAt ? htmlspecialchars(date('F d, Y', strtotime($createdAt))) : '' ?>
  </p>

  <div class="hero-buttons">
    <a href="<?= BASE_URL ?>/news.php" class="btn-primary">Back to News</a>
  </div>
</section>

<section class="news-details-wrap">
  <article class="game-card news-details-card">
    <?php if (!empty($attachmentPath)): ?>
      <div class="news-details-attachment">
        <?php if ($attachmentType === 'image'): ?>
          <img
            src="<?= htmlspecialchars($attachmentPath) ?>"
            alt="<?= htmlspecialchars($title) ?>"
            class="news-details-image"
          >
        <?php else: ?>
          <div class="news-details-pdf">
            <p class="news-details-pdf-label">PDF Attachment</p>
            <a href="<?= htmlspecialchars($attachmentPath) ?>" target="_blank" rel="noopener" class="btn-primary">
              Download PDF
            </a>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <div class="news-details-body">
      <?= nl2br(htmlspecialchars($body)) ?>
    </div>
  </article>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>