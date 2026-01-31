<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../repositories/JsonGameRepository.php';
require_once __DIR__ . '/../utils/FileUploader.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $description = trim($_POST['description'] ?? '');
  $imagePath = '';

  // Back-end validation (server-side)
  if ($title === '') {
    $errors[] = 'Title is required';
  }

  // Upload (optional)
  if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $uploader = new FileUploader(
      ALLOWED_GAME_IMAGE_TYPES,
      ALLOWED_GAME_EXTENSIONS,
      UPLOADS_GAMES_PATH
    );

    $result = $uploader->upload($_FILES['image']);
    if ($result === false) {
      $errors = array_merge($errors, $uploader->getErrors());
    } else {
      $imagePath = $result['path'];
    }
  }

  if (empty($errors)) {
    $gameRepo = new JsonGameRepository();
    $gameRepo->create([
      'title' => $title,
      'description' => $description,
      'image_path' => $imagePath,
    ]);

    header('Location: ' . BASE_URL . '/admin/games.php?success=' . urlencode('Game created successfully'));
    exit;
  }
}

$pageTitle = 'Create Game';
$currentPage = 'games';
include __DIR__ . '/../includes/admin_header.php';
?>

<section class="hero admin-hero">
  <h1 class="hero-title">Create New Game</h1>
  <p class="hero-sub">Add a new mini-game to the collection.</p>
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
      <label class="admin-label">Description</label>
      <textarea
        name="description"
        rows="5"
        class="admin-textarea"
      ><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
    </div>

    <div class="admin-form-group">
      <label class="admin-label">Game Image</label>
      <input
        type="file"
        name="image"
        accept="image/jpeg,image/png,image/webp"
        class="admin-file"
      >
      <small class="admin-help">Allowed: JPG, PNG, WEBP (max 5MB)</small>
    </div>

    <div class="admin-form-actions">
      <button type="submit" class="btn-primary">Create Game</button>
      <a href="<?= BASE_URL ?>/admin/games.php" class="btn-secondary">Cancel</a>
    </div>
  </form>
</section>

<?php include __DIR__ . '/../includes/admin_footer.php'; ?>