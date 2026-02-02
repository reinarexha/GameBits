<?php

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', __DIR__);

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'gamebits');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_DRIVER', getenv('DB_DRIVER') ?: 'mysql');

putenv('DB_HOST=' . DB_HOST);
putenv('DB_NAME=' . DB_NAME);
putenv('DB_USER=' . DB_USER);
putenv('DB_PASS=' . DB_PASS);
putenv('DB_DRIVER=' . DB_DRIVER);
putenv('DB_SERVER=' . DB_HOST);
