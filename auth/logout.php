<?php



require_once __DIR__ . '/../app/bootstrap.php';

$auth = new Auth();
$auth->start();
$auth->logout();

header('Location: /index.php');
exit;
