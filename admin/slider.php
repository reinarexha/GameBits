<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../app/models/SliderItem.php';

session_start();

if (!isset($_SESSION['admin'])) {
  header('Location: ' . BASE_URL . '/admin/login.php');
  exit;
}

$errors = [];
$success = '';

$storageDir  = __DIR__ . '/../storage';
$storageFile = $storageDir . '/site_content.json';

function readSiteContent(string $file): array {
  if (!file_exists($file)) return [];
  $raw = file_get_contents($file);
  $data = json_decode($raw, true);
  return is_array($data) ? $data : [];
}

function writeSiteContent(string $dir, string $file, array $data): bool {
  if (!is_dir($dir)) {
    @mkdir($dir, 0775, true);
  }
  $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  return file_put_contents($file, $json, LOCK_EX) !== false;
}

if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$siteContent = readSiteContent($storageFile);

function old(string $key, $default = '') {
  return $_POST[$key] ?? $default;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $postedToken = $_POST['csrf_token'] ?? '';
  if (!hash_equals($_SESSION['csrf_token'], $postedToken)) {
    $errors[] = 'Invalid form token. Please refresh the page and try again.';
  } else {

    if (isset($_POST['save_home'])) {
      $heroTitle = trim($_POST['hero_title'] ?? '');
      $heroSubtitle = trim($_POST['hero_subtitle'] ?? '');

      if ($heroTitle === '') {
        $errors[] = 'Hero title is required.';
      }

      if (empty($errors)) {
        $siteContent['hero_title'] = $heroTitle;
        $siteContent['hero_subtitle'] = $heroSubtitle;

        if (writeSiteContent($storageDir, $storageFile, $siteContent)) {
          header('Location: ' . BASE_URL . '/admin/slider.php?success=Home content saved');
          exit;
        } else {
          $errors[] = 'Could not save home content. Check folder permissions for /storage.';
        }
      }
    }

    if (isset($_POST['save_about'])) {
      $aboutText = trim($_POST['about_text'] ?? '');

      if ($aboutText === '') {
        $errors[] = 'About text is required.';
      }

      if (empty($errors)) {
        $siteContent['about_text'] = $aboutText;

        if (writeSiteContent($storageDir, $storageFile, $siteContent)) {
          header('Location: ' . BASE_URL . '/admin/slider.php?success=About content saved');
          exit;
        } else {
          $errors[] = 'Could not save about content. Check folder permissions for /storage.';
        }
      }
    }

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

      if ($imagePath !== '' && strpos($imagePath, '/uploads/slider/') !== 0) {
        $errors[] = 'Image path must start with /uploads/slider/.';
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

    if (isset($_POST['delete']) && isset($_POST['id'])) {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) {
        SliderItem::delete($id);
        header('Location: ' . BASE_URL . '/admin/slider.php?success=Slide deleted');
        exit;
      } else {
        $errors[] = 'Invalid slide id.';
      }
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

$heroTitleVal = htmlspecialchars(old('hero_title', $siteContent['hero_title'] ?? ''), ENT_QUOTES, 'UTF-8');
$heroSubtitleVal = htmlspecialchars(old('hero_subtitle', $siteContent['hero_subtitle'] ?? ''), ENT_QUOTES, 'UTF-8');
$aboutTextVal = htmlspecialchars(old('about_text', $siteContent['about_text'] ?? ''), ENT_QUOTES, 'UTF-8');
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
    <h2 class="section-title" style="margin: 0 0 1rem;">Home page content</h2>
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

      <div class="admin-form-group">
        <label class="admin-label">Hero title</label>
        <input class="admin-input" type="text" name="hero_title" value="<?= $heroTitleVal ?>">
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Hero subtitle</label>
        <input class="admin-input" type="text" name="hero_subtitle" value="<?= $heroSubtitleVal ?>">
      </div>
      <div class="admin-form-actions">
        <button class="btn-primary" type="submit" name="save_home" value="1">Save Home</button>
      </div>
    </form>
  </div>

  <div class="admin-card admin-form-card">
    <h2 class="section-title" style="margin: 0 0 1rem;">About page content</h2>
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

      <div class="admin-form-group">
        <label class="admin-label">About text</label>
        <textarea class="admin-input" name="about_text" rows="5"><?= $aboutTextVal ?></textarea>
      </div>
      <div class="admin-form-actions">
        <button class="btn-primary" type="submit" name="save_about" value="1">Save About</button>
      </div>
    </form>
  </div>

  <div class="admin-card admin-form-card">
    <h2 class="section-title" style="margin: 0 0 1rem;">Add new slide</h2>

    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
    </form>
  </div>

</section>

<?php include __DIR__ . '/../includes/admin_footer.php'; ?>
