<?php

require_once __DIR__ . '/../../app/bootstrap.php';

$auth = new Auth();
$auth->start();
$auth->requireAdmin();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard – Gamebits</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <main>
        <h1>Admin Dashboard</h1>
        <ul>
            <li><a href="/admin/games/">Manage Games</a></li>
            <li><a href="/admin/content/">Manage Content</a></li>
            <li><a href="/admin/messages/">Messages</a></li>
            <li><a href="/admin/users/">Users</a></li>
        </ul>
        <p><a href="/index.php">Back to site</a></p>
    </main>
</body>
</html>
