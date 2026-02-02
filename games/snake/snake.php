<?php
require_once __DIR__ . '/../../includes/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$pageTitle = 'Snake';
$currentPage = 'games';
include __DIR__ . '/../../includes/header.php';
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/games/snake/snake.css">

<div id="gameContainer">
  <canvas id="gameBoard" width="500" height="500"></canvas>
  <div id="scoreText">0</div>
  <button id="resetBtn">Reset</button>
</div>

<script>
  window.GAMEBITS = {
    baseUrl: "<?= BASE_URL ?>",
    game: "snake"
  };
</script>

<script src="<?= BASE_URL ?>/games/snake/snake.js"></script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>