<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../app/models/SliderItem.php';
require_once __DIR__ . '/../app/models/PageContent.php';

$auth = new Auth();
$auth->start();

$pageContent = new PageContent();
$heroTitle = $pageContent->getText('home', 'hero_title', '');
$heroSubtitle = $pageContent->getText('home', 'hero_subtitle', '');

$slides = SliderItem::getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
</head>
<body>

<?php if ($heroTitle !== '' || $heroSubtitle !== ''): ?>
  <section class="hero">
    <?php if ($heroTitle !== ''): ?>
      <h1 class="hero-title"><?= htmlspecialchars($heroTitle) ?></h1>
    <?php endif; ?>

    <?php if ($heroSubtitle !== ''): ?>
      <p class="hero-subtitle"><?= htmlspecialchars($heroSubtitle) ?></p>
    <?php endif; ?>
  </section>
<?php endif; ?>

<?php if (!empty($slides)): ?>
  <div class="slider" tabindex="0">
    <button class="slider-btn prev" type="button" aria-label="Previous slide">‹</button>
    <button class="slider-btn next" type="button" aria-label="Next slide">›</button>

    <div class="slider-dots" aria-label="Slider dots"></div>

    <?php foreach ($slides as $slide): ?>
      <div class="slide">
        <div class="slide-image">
          <img
            src="<?= htmlspecialchars($slide->image_path) ?>"
            alt="<?= htmlspecialchars($slide->title ?: 'Slide image') ?>"
          >
        </div>

        <div class="slide-content">
          <?php if (!empty($slide->title)): ?>
            <h2><?= htmlspecialchars($slide->title) ?></h2>
          <?php endif; ?>

          <?php if (!empty($slide->subtitle)): ?>
            <p><?= htmlspecialchars($slide->subtitle) ?></p>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php else: ?>
  <p>No slides yet.</p>
<?php endif; ?>

<script src="/assets/js/slider.js"></script>
</body>
</html>
