<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/require_admin.php';
require_once __DIR__ . '/../repositories/DbNewsRepository.php';
require_once __DIR__ . '/../utils/FileUploader.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $body  = trim($_POST['body'] ?? '');

  $attachmentPath = '';
  $attachmentType = '';

  
  if ($title === '') $errors[] = 'Title is required';
  if ($body === '')  $errors[] = 'Body is required';

  
  if (!isset($_FILES['attachment']) || $_FILES['attachment']['error'] === UPLOAD_ERR_NO_FILE) {
    $errors[] = 'Attachment is required (image or PDF)';
  }


  if (empty($errors) && isset($_FILES['attachment']) && $_FILES['attachment']['error'] !== UPLOAD_ERR_NO_FILE) {
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
      $attachmentType = $result['type']; 
    }
  }

  if (empty($errors)) {
    $newsRepo = new DbNewsRepository();
    $newsRepo->create([
      'title' => $title,
      'body' => $body,
      'attachment_path' => $attachmentPath,
      'attachment_type' => $attachmentType,
      'created_by' => $_SESSION['user']['id']
    ]);

    header('Location: ' . BASE_URL . '/admin/news.php?success=' . urlencode('News item created successfully'));
    exit;
  }
}

$pageTitle = 'Create News';
$currentPage = 'news';
include __DIR__ . '/../includes/admin_header.php';
?>

<section class="hero admin-hero">
  <h1 class="hero-title">Create News Item</h1>
  <p class="hero-sub">Post a new news item with an image or PDF attachment.</p>
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
        value="<?= htmlspecialchars($_POST['title'] ?? '') ?>"
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
      ><?= htmlspecialchars($_POST['body'] ?? '') ?></textarea>
    </div>

    <div class="admin-form-group">
      <label class="admin-label">Attachment (Image or PDF) *</label>
      <input
            type="file"
            name="attachment"
            id="newsAttachmentInput"
            accept="image/jpeg,image/png,image/webp,application/pdf"
             class="admin-file"
        >
         <div id="newsAttachmentPreview" class="admin-file-preview"></div>

      <small class="admin-help">Allowed: JPG, PNG, WEBP, PDF (max 5MB)</small>
    </div>

    <div class="admin-form-actions">
      <button type="submit" class="btn-primary">Create News</button>
      <a href="<?= BASE_URL ?>/admin/news.php" class="btn-secondary">Cancel</a>
    </div>
  </form>
</section>

<?php include __DIR__ . '/../includes/admin_footer.php'; ?>
