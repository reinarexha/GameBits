<?php

require_once __DIR__ . '/../../../app/bootstrap.php';
require_once __DIR__ . '/../../../app/models/ContactMessage.php';

$auth = new Auth();
$auth->start();
$auth->requireAdmin();

$model = new ContactMessage();
$rows = $model->all();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages – Admin</title>
    <link rel="stylesheet" href="../../../css/styles.css">
</head>
<body>
    <main>
        <h1>Contact Messages</h1>
        <?php if (empty($rows)): ?>
            <p>No messages yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Created</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= (int)$row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['subject'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['created_at'] ?? '') ?></td>
                        <td><a href="view.php?id=<?= (int)$row['id'] ?>">View</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>
