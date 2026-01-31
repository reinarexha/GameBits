<?php

require_once __DIR__ . '/../../../app/bootstrap.php';
require_once __DIR__ . '/../../../app/core/Validator.php';
require_once __DIR__ . '/../../../app/core/Uploader.php';
require_once __DIR__ . '/../../../app/models/PageContentAdmin.php';

$auth = new Auth();
$auth->start();
$auth->requireAdmin();

$model = new PageContentAdmin();
$id = (int)($_GET['id'] ?? 0);
$row = $model->find($id);

if (!$row) {
    header('Location: index.php?error=' . urlencode('Content not found'));
    exit;
}

$errors = [];
$old = $_POST ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validator = new Validator();
    $errors = $validator->validate($_POST, [
        'content_text' => 'required|min_length:1',
    ]);

    $imagePath = $row['content_image_path'] ?? null;
    if (isset($_FILES['content_image']) && $_FILES['content_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        try {
            $uploader = new Uploader();
            $imagePath = $uploader->upload($_FILES['content_image']);
        } catch (Exception $e) {
            $errors['content_image'] = $e->getMessage();
        }
    }

    if (empty($errors)) {
        $model->update($id, [
            'content_text' => trim($_POST['content_text'] ?? ''),
            'content_image_path' => $imagePath,
        ], (int)($auth->id() ?? 0));

        header('Location: /admin/content/?success=' . urlencode('Content updated successfully'));
        exit;
    }
}

$old = array_merge($row, $old);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Page Content – Admin</title>
    <link rel="stylesheet" href="../../../css/styles.css">
</head>
<body>
    <main>
        <h1>Edit Page Content</h1>
        <?php foreach ($errors as $field => $msg): ?>
            <p class="error"><?= htmlspecialchars($msg) ?></p>
        <?php endforeach; ?>
        <?php if (!empty($row['content_image_path'])): ?>
            <p>Current image: <img src="/<?= htmlspecialchars($row['content_image_path']) ?>" alt="Current" style="max-width: 150px;"></p>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div>
                <label>Page</label>
                <input type="text" value="<?= htmlspecialchars($row['page'] ?? '') ?>" readonly disabled>
            </div>
            <div>
                <label>Section Key</label>
                <input type="text" value="<?= htmlspecialchars($row['section_key'] ?? '') ?>" readonly disabled>
            </div>
            <div>
                <label>Content Text *</label>
                <textarea name="content_text" rows="10"><?= htmlspecialchars($old['content_text'] ?? '') ?></textarea>
            </div>
            <div>
                <label><?= !empty($row['content_image_path']) ? 'Replace Image' : 'Image' ?></label>
                <input type="file" name="content_image" accept="image/jpeg,image/png,image/webp">
            </div>
            <button type="submit">Update</button>
            <a href="index.php">Cancel</a>
        </form>
    </main>
</body>
</html>
