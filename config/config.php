<?php
// Database Configuration
// Set to 'mysql' for production (InfinityFree) or 'sqlite' for local testing
define('DB_TYPE', 'sqlite');

// MySQL Configuration (for InfinityFree)
define('DB_HOST', 'localhost');
define('DB_NAME', 'echoes_brews');
define('DB_USER', 'root');
define('DB_PASS', '');

// SQLite Configuration (for local testing)
define('SQLITE_PATH', __DIR__ . '/../database.sqlite');

// Site Configuration
define('SITE_NAME', 'Echoes & Brews');
define('SITE_URL', 'http://localhost:5000');
define('UPLOAD_PATH', __DIR__ . '/../uploads/');

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS
session_start();

// Timezone
date_default_timezone_set('UTC');

// Error Reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
