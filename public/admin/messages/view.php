<?php

require_once __DIR__ . '/../../../app/bootstrap.php';
require_once __DIR__ . '/../../../app/models/ContactMessage.php';

$auth = new Auth();
$auth->start();
$auth->requireAdmin();

$model = new ContactMessage();
$id = (int)($_GET['id'] ?? 0);
$msg = $model->find($id);

if (!$msg) {
    header('Location: index.php?error=' . urlencode('Message not found'));
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Message – Admin</title>
    <link rel="stylesheet" href="../../../css/styles.css">
</head>
<body>
    <main>
        <h1>View Message</h1>
        <dl>
            <dt>Name</dt>
            <dd><?= htmlspecialchars($msg['name'] ?? '') ?></dd>
            <dt>Email</dt>
            <dd><?= htmlspecialchars($msg['email'] ?? '') ?></dd>
            <dt>Subject</dt>
            <dd><?= htmlspecialchars($msg['subject'] ?? '') ?></dd>
            <dt>Message</dt>
            <dd><?= nl2br(htmlspecialchars($msg['message'] ?? '')) ?></dd>
            <dt>Created</dt>
            <dd><?= htmlspecialchars($msg['created_at'] ?? '') ?></dd>
        </dl>
        <p><a href="index.php">Back</a></p>
    </main>
</body>
</html>
