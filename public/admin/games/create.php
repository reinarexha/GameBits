<?php

require_once __DIR__ . '/../../../app/bootstrap.php';
require_once __DIR__ . '/../../../app/core/Validator.php';
require_once __DIR__ . '/../../../app/core/Uploader.php';
require_once __DIR__ . '/../../../app/models/Game.php';

$auth = new Auth();
$auth->start();
$auth->requireAdmin();

$errors = [];
$old = $_POST ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validator = new Validator();
    $errors = $validator->validate($_POST, [
        'title' => 'required|min_length:3',
    ]);

    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        try {
            $uploader = new Uploader();
            $imagePath = $uploader->upload($_FILES['image']);
        } catch (Exception $e) {
            $errors['image'] = $e->getMessage();
        }
    }

    if (empty($errors)) {
        $gameModel = new Game();
        $gameModel->create([
            'title' => trim($_POST['title']),
            'description' => trim($_POST['description'] ?? ''),
            'image_path' => $imagePath,
        ], (int)($auth->id() ?? 0));

        header('Location: index.php?success=' . urlencode('Game created successfully'));
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Game – Admin</title>
    <link rel="stylesheet" href="../../../css/styles.css">
</head>
<body>
    <main>
        <h1>Create Game</h1>
        <?php foreach ($errors as $field => $msg): ?>
            <p class="error"><?= htmlspecialchars($msg) ?></p>
        <?php endforeach; ?>
        <form method="POST" enctype="multipart/form-data">
            <div>
                <label>Title *</label>
                <input type="text" name="title" value="<?= htmlspecialchars($old['title'] ?? '') ?>">
            </div>
            <div>
                <label>Description</label>
                <textarea name="description" rows="5"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
            </div>
            <div>
                <label>Image</label>
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp">
            </div>
            <button type="submit">Create Game</button>
            <a href="index.php">Cancel</a>
        </form>
    </main>
</body>
</html>
