<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/auth_check.php';

$search = trim($_GET['search'] ?? '');
$page = max(1, (int) ($_GET['page'] ?? 1));
$per_page = 20;
$offset = ($page - 1) * $per_page;

$where = [];
$params = [];
$types = '';

if ($search !== '') {
    $where[] = '(name LIKE ? OR email LIKE ? OR subject LIKE ? OR message LIKE ?)';
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
    $types .= 'ssss';
}

$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$count_sql = "SELECT COUNT(*) AS c FROM messages {$where_sql}";
$count_stmt = mysqli_prepare($conn, $count_sql);
if ($params) {
    mysqli_stmt_bind_param($count_stmt, $types, ...$params);
}
mysqli_stmt_execute($count_stmt);
$total = mysqli_fetch_assoc(mysqli_stmt_get_result($count_stmt))['c'];
$total_pages = max(1, (int) ceil($total / $per_page));
if ($page > $total_pages) { $page = $total_pages; $offset = ($page - 1) * $per_page; }
mysqli_stmt_close($count_stmt);

$sql = "SELECT * FROM messages {$where_sql} ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = mysqli_prepare($conn, $sql);
$bind_types = $types . 'ii';
$bind_params = array_merge($params, [$per_page, $offset]);
mysqli_stmt_bind_param($stmt, $bind_types, ...$bind_params);
mysqli_stmt_execute($stmt);
$messages = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'], $_POST['csrf_token']) && $_POST['csrf_token'] === ($_SESSION['csrf_token'] ?? '')) {
    $id = (int) $_POST['delete_id'];
    $del = mysqli_prepare($conn, 'DELETE FROM messages WHERE id = ?');
    mysqli_stmt_bind_param($del, 'i', $id);
    mysqli_stmt_execute($del);
    mysqli_stmt_close($del);
    header('Location: messages.php?' . http_build_query(array_merge($_GET, ['page' => $page])));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Messages | Equator Royal Tour CBO</title>
<link rel="stylesheet" href="../css/style.css">
<?php include __DIR__ . '/includes/admin_header.php'; ?>
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
      <a href="messages.php" class="active">Messages</a>
      <a href="change-password.php">Change Password</a>
      <a href="logout.php">Log Out</a>
    </nav>
  </aside>
  <main class="admin-main">
    <div class="admin-header">
      <h1>Contact Messages</h1>
      <div class="admin-user"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></div>
    </div>

    <div class="card" style="margin-bottom:20px;">
      <form method="GET" action="messages.php" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
        <div class="form-group" style="flex:1;min-width:200px;margin-bottom:0;">
          <label for="search">Search</label>
          <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Name, email, subject, or message">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
        <?php if ($search !== ''): ?>
          <a href="messages.php" class="btn btn-outline">Clear</a>
        <?php endif; ?>
      </form>
    </div>

    <div style="overflow-x:auto;">
      <table>
        <thead>
          <tr><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Date</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($messages) === 0): ?>
            <tr><td colspan="6" style="text-align:center;padding:20px;">No messages found.</td></tr>
          <?php else: ?>
            <?php while ($m = mysqli_fetch_assoc($messages)): ?>
              <tr>
                <td><?php echo htmlspecialchars($m['name']); ?></td>
                <td><?php echo htmlspecialchars($m['email']); ?></td>
                <td><?php echo htmlspecialchars($m['subject'] ?: '(no subject)'); ?></td>
                <td style="max-width:300px;white-space:pre-wrap;"><?php echo htmlspecialchars($m['message']); ?></td>
                <td><?php echo htmlspecialchars(date('d M Y H:i', strtotime($m['created_at']))); ?></td>
                <td>
                  <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this message?');">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                    <input type="hidden" name="delete_id" value="<?php echo (int) $m['id']; ?>">
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if ($total_pages > 1): ?>
      <div style="margin-top:16px;display:flex;gap:8px;justify-content:center;">
        <?php if ($page > 1): ?>
          <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>" class="btn btn-outline">&laquo; Prev</a>
        <?php endif; ?>
        <span style="align-self:center;font-size:14px;color:var(--muted);">Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>
        <?php if ($page < $total_pages): ?>
          <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>" class="btn btn-outline">Next &raquo;</a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </main>
</div>
</body>
</html>
