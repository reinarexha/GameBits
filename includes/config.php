<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Paths
 * BASE_PATH should point to the project root (GameBits/)
 */
define('BASE_PATH', dirname(__DIR__));           // GameBits/
define('APP_PATH', BASE_PATH . '/app');

define('BASE_URL', '/GameBits');

define('STORAGE_PATH', BASE_PATH . '/storage');
define('UPLOADS_PATH', BASE_PATH . '/uploads');
define('UPLOADS_GAMES_PATH', UPLOADS_PATH . '/games');
define('UPLOADS_NEWS_PATH', UPLOADS_PATH . '/news');

// web-accessible paths
define('UPLOADS_WEB', '/uploads');
define('UPLOADS_GAMES_WEB', UPLOADS_WEB . '/games');
define('UPLOADS_NEWS_WEB', UPLOADS_WEB . '/news');

// JSON data files
define('GAMES_JSON', STORAGE_PATH . '/games.json');
define('NEWS_JSON', STORAGE_PATH . '/news.json');
define('SCORES_JSON', STORAGE_PATH . '/scores.json');

// file upload settings
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_GAME_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);
define('ALLOWED_GAME_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp']);
define('ALLOWED_NEWS_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);
define('ALLOWED_NEWS_PDF_TYPES', ['application/pdf']);
define('ALLOWED_NEWS_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp', 'pdf']);

// pagination
define('ITEMS_PER_PAGE', 12);

// timezone (use Belgrade if that’s your app audience)
date_default_timezone_set('Europe/Belgrade');

/**
 * Database
 */
// define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
// define('DB_NAME', getenv('DB_NAME') ?: 'gamebits');
// define('DB_USER', getenv('DB_USER') ?: 'root');
// define('DB_PASS', getenv('DB_PASS') ?: '');
// define('DB_DRIVER', getenv('DB_DRIVER') ?: 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'gamebits');
define('DB_USER', 'gamebits_user');
define('DB_PASS', 'gamebits_pass');
define('DB_DRIVER', 'mysql');



