<?php
require_once __DIR__ . '/../config.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';
$setup_mode = false;

$admin_check = mysqli_query($conn, 'SELECT COUNT(*) AS c FROM admins');
if ($admin_check && (int) mysqli_fetch_assoc($admin_check)['c'] === 0) {
    $setup_mode = true;
}

$max_attempts = 5;
$lockout_time = 300;
$now = time();

if (!$setup_mode && !empty($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= $max_attempts) {
    if (empty($_SESSION['lockout_until']) || $now > $_SESSION['lockout_until']) {
        unset($_SESSION['login_attempts']);
        unset($_SESSION['lockout_until']);
    }
}

if (!$setup_mode && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_SESSION['lockout_until']) && $now < $_SESSION['lockout_until']) {
        $remaining = ceil(($_SESSION['lockout_until'] - $now) / 60);
        $error = "Too many failed attempts. Please try again in {$remaining} minute(s).";
    } elseif (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = 'Invalid request. Please try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $stmt = mysqli_prepare($conn, 'SELECT id, username, password_hash, must_change_password FROM admins WHERE username = ?');
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $admin = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($admin && password_verify($password, $admin['password_hash'])) {
            if (!empty($_SESSION['login_attempts'])) {
                unset($_SESSION['login_attempts']);
                unset($_SESSION['lockout_until']);
            }

            session_regenerate_id(true);
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['last_activity'] = time();

            if (!empty($admin['must_change_password'])) {
                $_SESSION['must_change_password'] = true;
                header('Location: change-password.php');
                exit;
            }
            header('Location: dashboard.php');
            exit;
        } else {
            $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
            if ($_SESSION['login_attempts'] >= $max_attempts) {
                $_SESSION['lockout_until'] = $now + $lockout_time;
                $error = "Too many failed attempts. Please wait {$lockout_time} seconds before trying again.";
            } else {
                $error = 'Invalid username or password.';
            }
        }
    }
} elseif ($setup_mode && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = 'Invalid request. Please try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if ($username === '' || strlen($username) < 3) {
            $error = 'Username must be at least 3 characters.';
        } elseif (strlen($password) < 8) {
            $error = 'Password must be at least 8 characters.';
        } elseif ($password !== $confirm) {
            $error = 'Passwords do not match.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, 'INSERT INTO admins (username, password_hash) VALUES (?, ?)');
            mysqli_stmt_bind_param($stmt, 'ss', $username, $hash);
            if (mysqli_stmt_execute($stmt)) {
                session_regenerate_id(true);
                $_SESSION['admin_id'] = mysqli_insert_id($conn);
                $_SESSION['admin_username'] = $username;
                $_SESSION['last_activity'] = time();
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Failed to create admin account. Please try again.';
            }
            mysqli_stmt_close($stmt);
        }
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
  <div class="form-card" style="max-width:400px;width:100%;">
    <?php if ($setup_mode): ?>
      <h2 style="text-align:center;">Create Administrator</h2>
      <p style="text-align:center;color:var(--muted);margin-bottom:20px;">No admin account found. Set up your first administrator account.</p>
    <?php else: ?>
      <h2 style="text-align:center;">Admin Login</h2>
      <p style="text-align:center;color:var(--muted);margin-bottom:20px;">Equator Royal Tour CBO — Registration Management</p>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST" action="login.php">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
      <?php if ($setup_mode): ?>
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" required autofocus>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required minlength="8">
        </div>
        <div class="form-group">
          <label for="confirm_password">Confirm Password</label>
          <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;">Create Account</button>
      <?php else: ?>
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" required autofocus autocomplete="username">
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required autocomplete="current-password">
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;">Log In</button>
      <?php endif; ?>
    </form>
  </div>
</body>
</html>
