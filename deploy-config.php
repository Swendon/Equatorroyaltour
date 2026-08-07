<?php
// deploy-config.php — Copy this to config.php and fill in your production values.
// Never commit real passwords to version control.

// Database connection — replace with your hosting provider's credentials.
define('DB_HOST', 'localhost');
define('DB_USER', 'your_db_username');
define('DB_PASS', 'Ruby@254');
define('DB_NAME', 'equator_royal_tour');

// Encryption key — generate a random 32-byte key for production.
// Run: php -r "echo bin2hex(random_bytes(32));"
// Or use: https://www.random.org/strings/
define('APP_KEY', 'replace-with-random-32-byte-hex-key');
