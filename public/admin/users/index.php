<?php

require_once __DIR__ . '/../../../app/bootstrap.php';
require_once __DIR__ . '/../../../app/models/User.php';

$auth = new Auth();
$auth->start();
$auth->requireAdmin();

$model = new User();
$rows = $model->all();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users – Admin</title>
    <link rel="stylesheet" href="../../../css/styles.css">
</head>
<body>
    <main>
        <h1>Manage Users</h1>
        <?php if (isset($_GET['success'])): ?>
            <p class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></p>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <p class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></p>
        <?php endif; ?>
        <?php if (empty($rows)): ?>
            <p>No users yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= (int)$row['id'] ?></td>
                        <td><?= htmlspecialchars($row['username'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['role'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['created_at'] ?? '') ?></td>
                        <td>
                            <a href="edit.php?id=<?= (int)$row['id'] ?>">Edit</a>
                            <form method="POST" action="delete.php" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>
