<?php

// WARNING: For production deployments, change DB_PASS from '' (empty root password)
// to a strong, unique password. Using an empty root password over the network
// exposes the database to unauthorized access. Update these credentials to match
// your MySQL installation and never commit real passwords to version control.
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'equator_royal_tour');

// Encryption key for sensitive fields (e.g., National ID numbers).
// Generate a random 32-byte key and replace the value below for production.
define('APP_KEY', 'change-me-to-a-random-32-byte-key-in-production');

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
