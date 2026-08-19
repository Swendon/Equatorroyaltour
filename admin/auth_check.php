<?php

if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if (!empty($_SESSION['must_change_password'])) {
    header('Location: change-password.php');
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (empty($_SESSION['last_activity'])) {
    $_SESSION['last_activity'] = time();
} elseif (time() - $_SESSION['last_activity'] > 1800) {
    $_SESSION = [];
    session_destroy();
    header('Location: login.php?timeout=1');
    exit;
}
$_SESSION['last_activity'] = time();
