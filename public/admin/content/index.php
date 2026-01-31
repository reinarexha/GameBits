<?php

require_once __DIR__ . '/../../../app/bootstrap.php';
require_once __DIR__ . '/../../../app/models/PageContentAdmin.php';

$auth = new Auth();
$auth->start();
$auth->requireAdmin();

$model = new PageContentAdmin();
$rows = $model->all();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Page Content – Admin</title>
    <link rel="stylesheet" href="../../../css/styles.css">
</head>
<body>
    <main>
        <h1>Manage Page Content</h1>
        <?php if (isset($_GET['success'])): ?>
            <p class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></p>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <p class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></p>
        <?php endif; ?>
        <?php if (empty($rows)): ?>
            <p>No page content yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Page</th>
                        <th>Section Key</th>
                        <th>Preview</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= (int)$row['id'] ?></td>
                        <td><?= htmlspecialchars($row['page'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['section_key'] ?? '') ?></td>
                        <td><?= htmlspecialchars(substr($row['content_text'] ?? '', 0, 80)) ?><?= strlen($row['content_text'] ?? '') > 80 ? '...' : '' ?></td>
                        <td><a href="edit.php?id=<?= (int)$row['id'] ?>">Edit</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>
