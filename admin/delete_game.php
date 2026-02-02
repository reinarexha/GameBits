<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../repositories/DbGameRepository.php';
require_once __DIR__ . '/../utils/FileUploader.php';
require_once __DIR__ . '/../includes/config.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/games.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    header('Location: ' . BASE_URL . '/admin/games.php?error=' . urlencode('Invalid game ID'));
    exit;
}

$gameRepo = new DbGameRepository();
$game = $gameRepo->findById($id);

if (!$game) {
    header('Location: ' . BASE_URL . '/admin/games.php?error=' . urlencode('Game not found'));
    exit;
}

// delete image file
if (!empty($game['image_path'])) {
    FileUploader::deleteFile($game['image_path']);
}

// delete record
if ($gameRepo->delete($id)) {
    header('Location: ' . BASE_URL . '/admin/games.php?success=' . urlencode('Game deleted successfully'));
} else {
    header('Location: ' . BASE_URL . '/admin/games.php?error=' . urlencode('Failed to delete game'));
}
exit;