<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../repositories/JsonGameRepository.php';
require_once __DIR__ . '/../utils/FileUploader.php';

$gameRepo = new JsonGameRepository();
$id = (int)($_GET['id'] ?? 0);
$game = $gameRepo->findById($id);

if (!$game) {
  header('Location: ' . BASE_URL . '/admin/games.php?error=' . urlencode('Game not found'));
  exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $description = trim($_POST['description'] ?? '');
  $imagePath = $game['image_path'] ?? '';

  if ($title === '') {
    $errors[] = 'Title is required';
  }

 
  if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    if (!empty($game['image_path'])) {
      FileUploader::deleteFile($game['image_path']);
    }

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
    $gameRepo->update($id, [
      'title' => $title,
      'description' => $description,
      'image_path' => $imagePath,
    ]);

    header('Location: ' . BASE_URL . '/admin/games.php?success=' . urlencode('Game updated successfully'));
    exit;
  }
}

$pageTitle = 'Edit Game';
$currentPage = 'games';
include __DIR__ . '/../includes/admin_header.php';
?>

<section class="hero admin-hero">
  <h1 class="hero-title">Edit Game</h1>
  <p class="hero-sub">Update game information.</p>
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
        value="<?= htmlspecialchars($_POST['title'] ?? ($game['title'] ?? '')) ?>"
        class="admin-input"
      >
    </div>

    <div class="admin-form-group">
      <label class="admin-label">Description</label>
      <textarea
        name="description"
        rows="5"
        class="admin-textarea"
      ><?= htmlspecialchars($_POST['description'] ?? ($game['description'] ?? '')) ?></textarea>
    </div>

    <?php if (!empty($game['image_path'])): ?>
      <div class="admin-form-group">
        <label class="admin-label">Current Image</label>
        <img
          src="<?= htmlspecialchars($game['image_path']) ?>"
          alt="Current game image"
          class="admin-current-image"
        >
      </div>
    <?php endif; ?>

    <div class="admin-form-group">
      <label class="admin-label"><?= !empty($game['image_path']) ? 'Replace Image' : 'Game Image' ?></label>
      <input
        type="file"
        name="image"
        accept="image/jpeg,image/png,image/webp"
        class="admin-file"
      >
      <small class="admin-help">Allowed: JPG, PNG, WEBP (max 5MB)</small>
    </div>

    <div class="admin-form-actions">
      <button type="submit" class="btn-primary">Update Game</button>
      <a href="<?= BASE_URL ?>/admin/games.php" class="btn-secondary">Cancel</a>
    </div>
  </form>
</section>

<?php include __DIR__ . '/../includes/admin_footer.php'; ?>