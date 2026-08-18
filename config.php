<?php

// ============================================================
// PRODUCTION CONFIGURATION — UPDATE THESE VALUES BEFORE DEPLOY
// ============================================================
// Get these from your hosting control panel (cPanel, Plesk, etc.)
//  - DB_HOST: Usually "localhost", sometimes "mysql.hostname.com"
//  - DB_USER / DB_PASS: Your MySQL username and password
//  - DB_NAME: The database name you created
//
// SECURITY: Never commit real passwords to version control.
// After deployment, set DB_PASS to your real password.
// ============================================================

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'equator_royal_tour');

// Encryption key for National ID numbers and other sensitive fields.
// REQUIRED: Generate a random key before deployment.
//   Terminal: php -r "echo bin2hex(random_bytes(32));"
//   Or visit: https://www.random.org/strings/
// Paste the result below between the quotes.
define('APP_KEY', 'change-me-to-a-random-32-byte-hex-key');

// Email notifications will be sent TO this address.
// Replace with your real email so you receive form submissions.
define('NOTIFY_EMAIL', getenv('NOTIFY_EMAIL') ?: '');

function encrypt_field($plaintext) {
    $key = hash('SHA256', APP_KEY, true);
    $iv = substr(hash('SHA256', APP_KEY . 'iv', true), 0, 16);
    return base64_encode(openssl_encrypt($plaintext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv));
}

function decrypt_field($ciphertext) {
    $key = hash('SHA256', APP_KEY, true);
    $iv = substr(hash('SHA256', APP_KEY . 'iv', true), 0, 16);
    return openssl_decrypt(base64_decode($ciphertext), 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
}

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die(
        'Database connection failed: ' . mysqli_connect_error() .
        '<br><br>Check these common issues:<br>' .
        '1. MySQL is running in XAMPP<br>' .
        '2. The database "' . DB_NAME . '" exists in phpMyAdmin<br>' .
        '3. The username/password in this file are correct<br>' .
        '4. You imported the SQL file from database.sql'
    );
}

mysqli_set_charset($conn, 'utf8mb4');

session_start();
