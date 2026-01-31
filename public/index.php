<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../app/models/SliderItem.php';

$slides = SliderItem::getAll();
?>

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
  <div class="slider" tabindex="0">
  <button class="slider-btn prev" type="button" aria-label="Previous slide">‹</button>
  <button class="slider-btn next" type="button" aria-label="Next slide">›</button>

  <div class="slider-dots" aria-label="Slider dots"></div>

  <?php foreach ($slides as $slide): ?>
    <div class="slide">
      <div class="slide-image">
        <img src="<?= htmlspecialchars($slide->image_path) ?>" alt="<?= htmlspecialchars($slide->title) ?>">
      </div>
      <div class="slide-content">
        <h2><?= htmlspecialchars($slide->title) ?></h2>
        <p><?= htmlspecialchars($slide->subtitle ?? '') ?></p>
      </div>
    </div>
  <?php endforeach; ?>
</div>


    <script src="assets/js/slider.js"></script>
</body>
</html>
