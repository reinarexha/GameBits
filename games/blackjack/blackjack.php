<?php
require_once __DIR__ . '/../../includes/config.php';

$pageTitle = 'Blackjack';
$currentPage = 'games';
include __DIR__ . '/../../includes/header.php';
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/games/blackjack/blackjack.css">

<h2>Dealer: <span id="dealer-sum"></span></h2>
<div id="dealer-cards">
  <img id="hidden" src="<?= BASE_URL ?>/games/blackjack/cards/BACK.png" alt="back of card">
</div>

<h2>You: <span id="your-sum"></span></h2>
<div id="your-cards"></div>
<br>

<button id="hit">Hit</button>
<button id="stay">Stay</button>
<p id="results"></p>

<script>
  window.GAMEBITS = {
    baseUrl: "<?= BASE_URL ?>",
    game: "blackjack"
  };
</script>

<script src="<?= BASE_URL ?>/games/blackjack/blackjack.js"></script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>