<?php
// deploy-config.php — Copy this to config.php and fill in your production values.
// Never commit real passwords to version control.

// Database connection — replace with your hosting provider's credentials.
define('DB_HOST', 'localhost');
define('DB_USER', 'your_db_username');
define('DB_PASS', 'your_db_password');
define('DB_NAME', 'equator_royal_tour');

// Email notifications will be sent TO this address.
define('NOTIFY_EMAIL', 'your-email@example.com');

