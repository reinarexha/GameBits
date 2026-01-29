<?php


$base = dirname(__DIR__);

if (!defined('BASE_PATH')) {
    define('BASE_PATH', $base);
}
if (!defined('APP_PATH')) {
    define('APP_PATH', $base . '/app');
}

// DB: use env if set, else constants (set these or use .env in real project)
if (!defined('DB_SERVER')) {
    define('DB_SERVER', getenv('DB_SERVER') ?: 'YOUR_SQL_SERVER_NAME_OR_HOST');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', getenv('DB_NAME') ?: 'YOUR_DATABASE_NAME');
}
if (!defined('DB_USER')) {
    define('DB_USER', getenv('DB_USER') ?: 'YOUR_DATABASE_USERNAME');
}
if (!defined('DB_PASS')) {
    define('DB_PASS', getenv('DB_PASS') ?: 'YOUR_DATABASE_PASSWORD');
}

putenv('DB_SERVER=' . DB_SERVER);
putenv('DB_NAME=' . DB_NAME);
putenv('DB_USER=' . DB_USER);
putenv('DB_PASS=' . DB_PASS);
