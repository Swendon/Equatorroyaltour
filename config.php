<?php


define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'equator_royal_tour');

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
