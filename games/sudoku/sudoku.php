<?php
require_once __DIR__ . '/../../includes/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$pageTitle = 'Sudoku';
$currentPage = 'games';
include __DIR__ . '/../../includes/header.php';
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/games/sudoku/sudoku.css">

<h1>Sudoku</h1>
<hr>
<h2 id="errors">0</h2>

<div id="board"></div>
<br>
<div id="digits"></div>

<script>
  window.GAMEBITS = {
    baseUrl: "<?= BASE_URL ?>",
    game: "sudoku"
  };
</script>

<script src="<?= BASE_URL ?>/games/sudoku/sudoku.js"></script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>