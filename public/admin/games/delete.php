<?php

require_once __DIR__ . '/../../../app/bootstrap.php';
require_once __DIR__ . '/../../../app/core/Validator.php';
require_once __DIR__ . '/../../../app/core/Uploader.php';
require_once __DIR__ . '/../../../app/models/Game.php';

$auth = new Auth();
$auth->start();
$auth->requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$validator = new Validator();
$errors = $validator->validate($_POST, ['id' => 'required']);
if (!empty($errors)) {
    header('Location: index.php?error=' . urlencode('Invalid game ID'));
    exit;
}

$id = (int)$_POST['id'];
if ($id <= 0) {
    header('Location: index.php?error=' . urlencode('Invalid game ID'));
    exit;
}

$gameModel = new Game();
$game = $gameModel->find($id);

if (!$game) {
    header('Location: index.php?error=' . urlencode('Game not found'));
    exit;
}

if (!empty($game['image_path'])) {
    $fullPath = dirname(__DIR__, 3) . '/public/' . $game['image_path'];
    if (file_exists($fullPath) && is_file($fullPath)) {
        unlink($fullPath);
    }
}

$gameModel->delete($id);

header('Location: index.php?success=' . urlencode('Game deleted successfully'));
exit;
