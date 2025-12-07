<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'cafe_dopamine');

// Site configuration
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
define('SITE_URL', $base_url);
define('SITE_NAME', 'Cafe Dopamine');
define('ADMIN_EMAIL', 'admin@cafedopamine.com');

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Error reporting (turn off in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>