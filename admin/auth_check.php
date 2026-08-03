<?php
// Include at the top of every protected admin page.
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
