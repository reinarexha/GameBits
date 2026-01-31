<?php
session_start();

if (!isset($_SESSION['admin'])) {
  header('Location: ' . BASE_URL . '/admin/login.php');
  exit;
}

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../app/models/SliderItem.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // add slide
  if (isset($_POST['add_slide'])) {
    $title = trim($_POST['title'] ?? '');
    $subtitle = trim($_POST['subtitle'] ?? '');
    $imagePath = trim($_POST['image_path'] ?? '');

    if ($title === '') {
      $errors[] = 'Title is required.';
    }

    if ($imagePath === '') {
      $errors[] = 'Image path is required.';
    }

    if (empty($errors)) {
      $item = new SliderItem();
      $item->title = $title;
      $item->subtitle = $subtitle;
      $item->image_path = $imagePath;

      if ($item->save()) {
        header('Location: ' . BASE_URL . '/admin/slider.php?success=Slide added');
        exit;
      } else {
        $errors[] = 'Could not save slide. Try again.';
      }
    }
  }

  // delete slide
  if (isset($_POST['delete']) && isset($_POST['id'])) {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
      SliderItem::delete($id);
      header('Location: ' . BASE_URL . '/admin/slider.php?success=Slide deleted');
      exit;
    }
  }
}

if (isset($_GET['success'])) {
  $success = $_GET['success'];
}

$items = SliderItem::getAll();

$pageTitle = 'Manage Slider';
$currentPage = 'slider'; 
include __DIR__ . '/../includes/admin_header.php';
?>

<section class="hero admin-hero">
  <h1 class="hero-title">Manage Slider</h1>
  <p class="hero-sub">Add or remove slides shown on the homepage.</p>
</section>

<section class="admin-wrap admin-form-wrap">

  <?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-error">
      <ul class="admin-errors">
        <?php foreach ($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <div class="admin-card admin-form-card">
    <h2 class="section-title" style="margin: 0 0 1rem;">Add new slide</h2>

    <form method="post">
      <div class="admin-form-group">
        <label class="admin-label">Title *</label>
        <input class="admin-input" type="text" name="title" required value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Subtitle</label>
        <input class="admin-input" type="text" name="subtitle" value="<?= htmlspecialchars($_POST['subtitle'] ?? '') ?>">
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Image path *</label>
        <input class="admin-input" type="text" name="image_path" placeholder="/uploads/slider/slide1.jpg" required value="<?= htmlspecialchars($_POST['image_path'] ?? '') ?>">
        <small class="admin-help">Tip: use a path like <b>/uploads/slider/your-image.jpg</b></small>
      </div>

      <div class="admin-form-actions">
        <button class="btn-primary" type="submit" name="add_slide" value="1">Add slide</button>
      </div>
    </form>
  </div>

  <div class="admin-card">
    <h2 class="section-title" style="margin: 0 0 1rem;">All slides</h2>

    <?php if (empty($items)): ?>
      <p class="admin-empty">No slides yet. Add your first slide above.</p>
    <?php else: ?>
      <div class="admin-news-list">
        <?php foreach ($items as $item): ?>
          <div class="game-card admin-news-card">
            <div class="admin-news-row">
              <?php if (!empty($item->image_path)): ?>
                <img
                  src="<?= htmlspecialchars($item->image_path) ?>"
                  alt="<?= htmlspecialchars($item->title ?? 'Slide image') ?>"
                  class="admin-news-thumb"
                  onerror="this.style.display='none';"
                >
              <?php endif; ?>

              <div class="admin-news-content">
                <h3 class="admin-news-title"><?= htmlspecialchars($item->title) ?></h3>

                <?php if (!empty($item->subtitle)): ?>
                  <p class="admin-news-excerpt"><?= htmlspecialchars($item->subtitle) ?></p>
                <?php endif; ?>

                <div class="admin-news-meta">
                  <span class="admin-news-date">
                    <?= !empty($item->created_at) ? date('M d, Y', strtotime($item->created_at)) : '' ?>
                  </span>

                  <?php if (!empty($item->image_path)): ?>
                    <a class="admin-news-file" href="<?= htmlspecialchars($item->image_path) ?>" target="_blank">Open image</a>
                  <?php endif; ?>
                </div>

                <div class="admin-card-actions" style="margin-top: 1rem;">
                  <form method="post" class="admin-inline-form" onsubmit="return confirm('Delete this slide?');">
                    <input type="hidden" name="id" value="<?= (int)$item->id ?>">
                    <button type="submit" name="delete" value="1" class="btn-primary btn-small btn-danger">Delete</button>
                  </form>
                </div>

              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>

</section>

<?php include __DIR__ . '/../includes/admin_footer.php'; ?>
