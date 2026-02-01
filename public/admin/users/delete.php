<?php

require_once __DIR__ . '/../../../app/bootstrap.php';
require_once __DIR__ . '/../../../app/core/Validator.php';
require_once __DIR__ . '/../../../app/models/User.php';

$auth = new Auth();
$auth->start();
$auth->requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/users/');
    exit;
}

$validator = new Validator();
$errors = $validator->validate($_POST, ['id' => 'required']);
if (!empty($errors)) {
    header('Location: /admin/users/?error=' . urlencode('Invalid user ID'));
    exit;
}

$id = (int)$_POST['id'];
if ($id <= 0) {
    header('Location: /admin/users/?error=' . urlencode('Invalid user ID'));
    exit;
}

if ($auth->id() === $id) {
    header('Location: /admin/users/?error=' . urlencode('You cannot delete yourself'));
    exit;
}

$model = new User();
$user = $model->find($id);

if (!$user) {
    header('Location: /admin/users/?error=' . urlencode('User not found'));
    exit;
}

if (($user['role'] ?? '') === 'admin' && $model->countAdmins() <= 1) {
    header('Location: /admin/users/?error=' . urlencode('Cannot delete the last admin'));
    exit;
}

$model->delete($id);

header('Location: /admin/users/?success=' . urlencode('User deleted successfully'));
exit;
