<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/require_admin.php';
require_once __DIR__ . '/../repositories/DbNewsRepository.php';
require_once __DIR__ . '/../utils/FileUploader.php';

$newsRepo = new DbNewsRepository();

$id = (int)($_GET['id'] ?? 0);
$news = $newsRepo->findById($id);

if (!$news) {
  header('Location: ' . BASE_URL . '/admin/news.php?error=' . urlencode('News item not found'));
  exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $body  = trim($_POST['body'] ?? '');

  $attachmentPath = $news['attachment_path'] ?? '';
  $attachmentType = $news['attachment_type'] ?? '';

  
  if ($title === '') $errors[] = 'Title is required';
  if ($body === '') $errors[] = 'Body is required';


  if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] !== UPLOAD_ERR_NO_FILE) {
    
    if (!empty($news['attachment_path'])) {
      FileUploader::deleteFile($news['attachment_path']);
    }

    $allowedTypes = array_merge(ALLOWED_NEWS_IMAGE_TYPES, ALLOWED_NEWS_PDF_TYPES);

    $uploader = new FileUploader(
      $allowedTypes,
      ALLOWED_NEWS_EXTENSIONS,
      UPLOADS_NEWS_PATH
    );

    $result = $uploader->upload($_FILES['attachment']);

    if ($result === false) {
      $errors = array_merge($errors, $uploader->getErrors());
    } else {
      $attachmentPath = $result['path'];
      $attachmentType = $result['type']; // expected 'image' or 'pdf'
    }
  }

  if (empty($errors)) {
    $newsRepo->update($id, [
      'title' => $title,
      'body' => $body,
      'attachment_path' => $attachmentPath,
      'attachment_type' => $attachmentType
    ]);

    header('Location: ' . BASE_URL . '/admin/news.php?success=' . urlencode('News item updated successfully'));
    exit;
  }
}

$pageTitle = 'Edit News';
$currentPage = 'news';
include __DIR__ . '/../includes/admin_header.php';
?>

<section class="hero admin-hero">
  <h1 class="hero-title">Edit News Item</h1>
  <p class="hero-sub">Update news item information.</p>
</section>

<section class="admin-wrap admin-form-wrap">
  <?php if (!empty($errors)): ?>
    <div class="alert alert-error">
      <ul class="admin-errors">
        <?php foreach ($errors as $error): ?>
          <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data" class="admin-form-card">
    <div class="admin-form-group">
      <label class="admin-label">Title *</label>
      <input
        type="text"
        name="title"
        required
        value="<?= htmlspecialchars($_POST['title'] ?? ($news['title'] ?? '')) ?>"
        class="admin-input"
      >
    </div>

    <div class="admin-form-group">
      <label class="admin-label">Body *</label>
      <textarea
        name="body"
        rows="10"
        required
        class="admin-textarea"
      ><?= htmlspecialchars($_POST['body'] ?? ($news['body'] ?? '')) ?></textarea>
    </div>

    <?php if (!empty($news['attachment_path'])): ?>
      <div class="admin-form-group">
        <label class="admin-label">Current Attachment</label>

        <?php if (($news['attachment_type'] ?? '') === 'image'): ?>
          <img
            src="<?= htmlspecialchars($news['attachment_path']) ?>"
            alt="Current attachment"
            class="admin-current-image"
          >
        <?php else: ?>
          <a
            href="<?= htmlspecialchars($news['attachment_path']) ?>"
            target="_blank"
            rel="noopener"
            class="admin-file-link"
          >
            View PDF: <?= htmlspecialchars(basename($news['attachment_path'])) ?>
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <div class="admin-form-group">
      <label class="admin-label">
        <?= !empty($news['attachment_path']) ? 'Replace Attachment' : 'Attachment (Image or PDF)' ?>
      </label>

      <input
        type="file"
        name="attachment"
        accept="image/jpeg,image/png,image/webp,application/pdf"
        class="admin-file"
      >

      <small class="admin-help">Allowed: JPG, PNG, WEBP, PDF (max 5MB)</small>
    </div>

    <div class="admin-form-actions">
      <button type="submit" class="btn-primary">Update News Item</button>
      <a href="<?= BASE_URL ?>/admin/news.php" class="btn-secondary">Cancel</a>
    </div>
  </form>
</section>

<?php include __DIR__ . '/../includes/admin_footer.php'; ?>