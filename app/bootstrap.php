<?php
require_once __DIR__ . '/../includes/config.php';

require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Auth.php';

$auth = new Auth();
$auth->start();

