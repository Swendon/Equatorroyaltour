<?php
require_once __DIR__ . '/../config.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = mysqli_prepare($conn, 'SELECT id, username, password_hash FROM admins WHERE username = ?');
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $admin = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($admin && password_verify($password, $admin['password_hash'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login | Equator Royal Tour CBO</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body style="background:var(--green-deep);min-height:100vh;display:flex;align-items:center;justify-content:center;">
  <div class="form-card">
    <h2 style="text-align:center;">Admin Login</h2>
    <p style="text-align:center;color:var(--muted);margin-bottom:20px;">Equator Royal Tour CBO — Registration Management</p>
    <?php if ($error): ?>
      <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST" action="login.php">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;">Log In</button>
    </form>
    <p style="text-align:center;font-size:13px;color:var(--muted);margin-top:16px;">Default: admin / admin123 (change after import — see README)</p>
  </div>
</body>
</html>
