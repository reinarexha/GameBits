<?php

require_once __DIR__ . '/../../../app/bootstrap.php';
require_once __DIR__ . '/../../../app/core/Validator.php';
require_once __DIR__ . '/../../../app/core/Uploader.php';
require_once __DIR__ . '/../../../app/models/Game.php';

$auth = new Auth();
$auth->start();
$auth->requireAdmin();

$gameModel = new Game();
$games = $gameModel->all();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Games – Admin</title>
    <link rel="stylesheet" href="../../../css/styles.css">
</head>
<body>
    <main>
        <h1>Manage Games</h1>
        <?php if (isset($_GET['success'])): ?>
            <p class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></p>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <p class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></p>
        <?php endif; ?>
        <p><a href="create.php">Create New Game</a></p>
        <?php if (empty($games)): ?>
            <p>No games yet. Create your first game!</p>
        <?php else: ?>
            <div>
                <?php foreach ($games as $game): ?>
                    <div class="game-card">
                        <?php if (!empty($game['image_path'])): ?>
                            <img src="/<?= htmlspecialchars($game['image_path']) ?>" alt="<?= htmlspecialchars($game['title']) ?>" style="max-width: 200px;">
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($game['title']) ?></h3>
                        <p><?= htmlspecialchars(substr($game['description'] ?? '', 0, 100)) ?><?= strlen($game['description'] ?? '') > 100 ? '...' : '' ?></p>
                        <a href="edit.php?id=<?= (int)$game['id'] ?>">Edit</a>
                        <form method="POST" action="delete.php" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                            <input type="hidden" name="id" value="<?= (int)$game['id'] ?>">
                            <button type="submit">Delete</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
