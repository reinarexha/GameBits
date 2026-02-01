<?php

require_once __DIR__ . '/../../../app/bootstrap.php';
require_once __DIR__ . '/../../../app/core/Validator.php';
require_once __DIR__ . '/../../../app/models/User.php';

$auth = new Auth();
$auth->start();
$auth->requireAdmin();

$model = new User();
$id = (int)($_GET['id'] ?? 0);
$user = $model->find($id);

if (!$user) {
    header('Location: /admin/users/?error=' . urlencode('User not found'));
    exit;
}

$errors = [];
$old = $_POST ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validator = new Validator();
    $errors = $validator->validate($_POST, [
        'username' => 'required|min_length:3',
        'email' => 'required|email',
        'role' => 'required',
    ]);

    $newRole = trim($_POST['role'] ?? '');
    if ($newRole !== 'admin' && $newRole !== 'user') {
        $errors['role'] = 'Role must be admin or user.';
    }

    if (empty($errors) && $auth->id() === $id && ($user['role'] ?? '') === 'admin' && $newRole === 'user') {
        $errors['role'] = 'You cannot change your own role from admin to user.';
    }

    if (empty($errors) && ($user['role'] ?? '') === 'admin' && $newRole === 'user' && $model->countAdmins() === 1) {
        $errors['role'] = 'Cannot change the last admin to user.';
    }

    if (empty($errors)) {
        $model->update($id, [
            'username' => trim($_POST['username'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'role' => $newRole,
        ], (int)($auth->id() ?? 0));

        header('Location: /admin/users/?success=' . urlencode('User updated successfully'));
        exit;
    }
}

$old = array_merge($user, $old);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User – Admin</title>
    <link rel="stylesheet" href="../../../css/styles.css">
</head>
<body>
    <main>
        <h1>Edit User</h1>
        <?php foreach ($errors as $field => $msg): ?>
            <p class="error"><?= htmlspecialchars($msg) ?></p>
        <?php endforeach; ?>
        <form method="POST">
            <div>
                <label>Username *</label>
                <input type="text" name="username" value="<?= htmlspecialchars($old['username'] ?? '') ?>">
            </div>
            <div>
                <label>Email *</label>
                <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
            </div>
            <div>
                <label>Role *</label>
                <select name="role">
                    <option value="admin" <?= ($old['role'] ?? '') === 'admin' ? 'selected' : '' ?>>admin</option>
                    <option value="user" <?= ($old['role'] ?? '') === 'user' ? 'selected' : '' ?>>user</option>
                </select>
            </div>
            <button type="submit">Update User</button>
            <a href="index.php">Cancel</a>
        </form>
    </main>
</body>
</html>
