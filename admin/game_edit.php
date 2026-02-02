<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/require_admin.php';
require_once __DIR__ . '/../repositories/DbGameRepository.php';
require_once __DIR__ . '/../utils/FileUploader.php';

$gameRepo = new DbGameRepository();
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

 
  $playUrl = trim($_POST['play_url'] ?? '');
  $difficulty = trim($_POST['difficulty'] ?? ''); 
  $isComingSoon = isset($_POST['is_coming_soon']) ? 1 : 0;
  $sortOrder = (int)($_POST['sort_order'] ?? 0);


  $imagePath = $game['image_path'] ?? '';

  if ($title === '') {
    $errors[] = 'Title is required';
  }

 
  if ($difficulty !== '' && !in_array($difficulty, ['easy', 'medium', 'hard'], true)) {
    $errors[] = 'Difficulty must be Easy, Medium, or Hard';
  }

 
  if (!$isComingSoon && $playUrl === '') {
    $errors[] = 'Play URL is required unless the game is marked Coming Soon';
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
      'description' => $description !== '' ? $description : null,
      'image_path' => $imagePath !== '' ? $imagePath : null,

      'play_url' => $playUrl !== '' ? $playUrl : null,
      'difficulty' => $difficulty !== '' ? $difficulty : null,
      'is_coming_soon' => $isComingSoon,
      'sort_order' => $sortOrder,
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

  
    <div class="admin-form-group">
      <label class="admin-label">Play URL</label>
      <input
        type="text"
        name="play_url"
        value="<?= htmlspecialchars($_POST['play_url'] ?? ($game['play_url'] ?? '')) ?>"
        class="admin-input"
        placeholder="/Sudoku/sudoku.html"
      >
      <small class="admin-help">Leave empty if Coming Soon is checked.</small>
    </div>

    <div class="admin-form-group">
      <label class="admin-label">Difficulty</label>
      <?php $d = $_POST['difficulty'] ?? ($game['difficulty'] ?? ''); ?>
      <select name="difficulty" class="admin-input">
        <option value="">-- select --</option>
        <option value="easy" <?= $d === 'easy' ? 'selected' : '' ?>>Easy</option>
        <option value="medium" <?= $d === 'medium' ? 'selected' : '' ?>>Medium</option>
        <option value="hard" <?= $d === 'hard' ? 'selected' : '' ?>>Hard</option>
      </select>
    </div>

    <div class="admin-form-group">
      <label class="admin-label">
        <?php
          
          $comingChecked = !empty($_POST)
            ? !empty($_POST['is_coming_soon'])
            : !empty($game['is_coming_soon']);
        ?>
        <input
          type="checkbox"
          name="is_coming_soon"
          value="1"
          <?= $comingChecked ? 'checked' : '' ?>
        >
        Coming soon
      </label>
    </div>

    <div class="admin-form-group">
      <label class="admin-label">Sort Order</label>
      <input
        type="number"
        name="sort_order"
        value="<?= htmlspecialchars($_POST['sort_order'] ?? ($game['sort_order'] ?? '0')) ?>"
        class="admin-input"
      >
      <small class="admin-help">Lower number appears first.</small>
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