<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../repositories/JsonNewsRepository.php';
require_once __DIR__ . '/../utils/FileUploader.php';
// require_once __DIR__ . '/../includes/admin_guard.php'; // enable when you add login/roles

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/news.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    header('Location: ' . BASE_URL . '/admin/news.php?error=' . urlencode('Invalid news ID'));
    exit;
}

//$newsRepo = new JsonNewsRepository();
$news = $newsRepo->findById($id);

if (!$news) {
    header('Location: ' . BASE_URL . '/admin/news.php?error=' . urlencode('News item not found'));
    exit;
}

// delete attachment file
if (!empty($news['attachment_path'])) {
    FileUploader::deleteFile($news['attachment_path']);
}

// delete record
if ($newsRepo->delete($id)) {
    header('Location: ' . BASE_URL . '/admin/news.php?success=' . urlencode('News item deleted successfully'));
} else {
    header('Location: ' . BASE_URL . '/admin/news.php?error=' . urlencode('Failed to delete news item'));
}
exit;