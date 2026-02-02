<?php
// includes/require_admin.php
// Guard to protect admin pages. Expects `includes/config.php` to be included first
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// If config defined BASE_URL it should already be available; otherwise fall back to ''
$base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';

if (!isset($_SESSION['user_id'])) {
  header('Location: ' . $base . '/auth/login.php?error=login_required');
  exit;
}

if (($_SESSION['role'] ?? '') !== 'admin') {
  header('Location: ' . $base . '/auth/login.php?error=unauthorized');
  exit;
}

