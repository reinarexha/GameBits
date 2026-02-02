<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../repositories/DbGameRepository.php';
require_once __DIR__ . '/../utils/FileUploader.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $description = trim($_POST['description'] ?? '');


  $playUrl = trim($_POST['play_url'] ?? '');
  $difficulty = trim($_POST['difficulty'] ?? '');
  $isComingSoon = isset($_POST['is_coming_soon']) ? 1 : 0;
  $sortOrder = (int)($_POST['sort_order'] ?? 0);

  $imagePath = '';


  if ($title === '') {
    $errors[] = 'Title is required';
  }


  if ($difficulty !== '' && !in_array($difficulty, ['easy', 'medium', 'hard'], true)) {
    $errors[] = 'Difficulty must be Easy, Medium, or Hard';
  }

 
  if (!$isComingSoon && $playUrl === '') {
    $errors[] = 'Play URL is required unless the game is marked Coming Soon';
  }

  // upload 
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
    $gameRepo = new DbGameRepository();
    $gameRepo->create([
      'title' => $title,
      'description' => $description !== '' ? $description : null,
      'image_path' => $imagePath !== '' ? $imagePath : null,

      // new fields saved to db
      'play_url' => $playUrl !== '' ? $playUrl : null,
      'difficulty' => $difficulty !== '' ? $difficulty : null,
      'is_coming_soon' => $isComingSoon,
      'sort_order' => $sortOrder,
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

    <!-- new -->
    <div class="admin-form-group">
      <label class="admin-label">Play URL</label>
      <input
        type="text"
        name="play_url"
        value="<?= htmlspecialchars($_POST['play_url'] ?? '') ?>"
        class="admin-input"
        placeholder="/Sudoku/sudoku.html"
      >
      <small class="admin-help">Leave empty if Coming Soon is checked.</small>
    </div>

    <div class="admin-form-group">
      <label class="admin-label">Difficulty</label>
      <?php $d = $_POST['difficulty'] ?? ''; ?>
      <select name="difficulty" class="admin-input">
        <option value="">-- select --</option>
        <option value="easy" <?= $d === 'easy' ? 'selected' : '' ?>>Easy</option>
        <option value="medium" <?= $d === 'medium' ? 'selected' : '' ?>>Medium</option>
        <option value="hard" <?= $d === 'hard' ? 'selected' : '' ?>>Hard</option>
      </select>
    </div>

    <div class="admin-form-group">
      <label class="admin-label">
        <input
          type="checkbox"
          name="is_coming_soon"
          value="1"
          <?= !empty($_POST['is_coming_soon']) ? 'checked' : '' ?>
        >
        Coming soon
      </label>
    </div>

    <div class="admin-form-group">
      <label class="admin-label">Sort Order</label>
      <input
        type="number"
        name="sort_order"
        value="<?= htmlspecialchars($_POST['sort_order'] ?? '0') ?>"
        class="admin-input"
      >
      <small class="admin-help">Lower number appears first.</small>
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