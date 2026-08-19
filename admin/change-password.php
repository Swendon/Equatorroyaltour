<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        die('Invalid request.');
    }

    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (strlen($new) < 8) {
        $error = 'New password must be at least 8 characters.';
    } elseif ($new !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $stmt = mysqli_prepare($conn, 'SELECT password_hash FROM admins WHERE id = ?');
        mysqli_stmt_bind_param($stmt, 'i', $_SESSION['admin_id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $admin = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if (!$admin || !password_verify($current, $admin['password_hash'])) {
            $error = 'Current password is incorrect.';
        } else {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, 'UPDATE admins SET password_hash = ?, must_change_password = 0 WHERE id = ?');
            mysqli_stmt_bind_param($stmt, 'si', $hash, $_SESSION['admin_id']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            unset($_SESSION['must_change_password']);
            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Change Password | Equator Royal Tour CBO</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="admin-layout">
  <aside class="admin-sidebar">
    <div class="brand">
      <span class="brand-mark">ERT Admin</span>
      <span class="brand-text">Equator Royal Tour CBO</span>
    </div>
    <nav class="admin-nav">
      <a href="dashboard.php">Dashboard</a>
      <a href="registrations.php">Registrations</a>
      <a href="messages.php">Messages</a>
      <a href="change-password.php" class="active">Change Password</a>
      <a href="logout.php">Log Out</a>
    </nav>
  </aside>
  <main class="admin-main">
    <div class="admin-header">
      <h1>Change Password</h1>
    </div>
    <div class="card" style="max-width:500px;">
      <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
      <form method="POST" action="change-password.php">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <div class="form-group">
          <label for="current_password">Current Password</label>
          <input type="password" id="current_password" name="current_password" required>
        </div>
        <div class="form-group">
          <label for="new_password">New Password</label>
          <input type="password" id="new_password" name="new_password" required minlength="8">
        </div>
        <div class="form-group">
          <label for="confirm_password">Confirm New Password</label>
          <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
        </div>
        <button type="submit" class="btn btn-primary">Update Password</button>
      </form>
    </div>
  </main>
</div>
</body>
</html>
