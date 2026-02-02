<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// base paths
define('BASE_URL', '/GameBits'); 
define('BASE_PATH', dirname(__DIR__));
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

// timezone
date_default_timezone_set('UTC');