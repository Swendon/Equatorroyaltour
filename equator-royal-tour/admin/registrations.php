<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/auth_check.php';

$search = trim($_GET['search'] ?? '');
$status_filter = $_GET['status'] ?? '';
$page = max(1, (int) ($_GET['page'] ?? 1));
$per_page = 20;
$offset = ($page - 1) * $per_page;

$where = [];
$params = [];
$types = '';

if ($search !== '') {
    $where[] = '(full_name LIKE ? OR phone LIKE ? OR trading_centre LIKE ?)';
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
    $types .= 'sss';
}
if ($status_filter !== '' && in_array($status_filter, ['Pending', 'Approved', 'Rejected'], true)) {
    $where[] = 'status = ?';
    $params[] = $status_filter;
    $types .= 's';
}

$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$count_sql = "SELECT COUNT(*) AS c FROM registrations {$where_sql}";
$count_stmt = mysqli_prepare($conn, $count_sql);
if ($params) {
    mysqli_stmt_bind_param($count_stmt, $types, ...$params);
}
mysqli_stmt_execute($count_stmt);
$total = mysqli_fetch_assoc(mysqli_stmt_get_result($count_stmt))['c'];
$total_pages = max(1, (int) ceil($total / $per_page));
if ($page > $total_pages) { $page = $total_pages; $offset = ($page - 1) * $per_page; }
mysqli_stmt_close($count_stmt);

$sql = "SELECT * FROM registrations {$where_sql} ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = mysqli_prepare($conn, $sql);
$bind_types = $types . 'ii';
$bind_params = array_merge($params, [$per_page, $offset]);
mysqli_stmt_bind_param($stmt, $bind_types, ...$bind_params);
mysqli_stmt_execute($stmt);
$registrations = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registration_id'], $_POST['status'], $_POST['csrf_token']) && $_POST['csrf_token'] === ($_SESSION['csrf_token'] ?? '')) {
    $id = (int) $_POST['registration_id'];
    $status = in_array($_POST['status'], ['Pending', 'Approved', 'Rejected'], true) ? $_POST['status'] : 'Pending';
    $upd = mysqli_prepare($conn, 'UPDATE registrations SET status = ? WHERE id = ?');
    mysqli_stmt_bind_param($upd, 'si', $status, $id);
    mysqli_stmt_execute($upd);
    mysqli_stmt_close($upd);
    header('Location: registrations.php?' . http_build_query(array_merge($_GET, ['page' => $page])));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'], $_POST['csrf_token']) && $_POST['csrf_token'] === ($_SESSION['csrf_token'] ?? '')) {
    $id = (int) $_POST['delete_id'];
    $del = mysqli_prepare($conn, 'DELETE FROM registrations WHERE id = ?');
    mysqli_stmt_bind_param($del, 'i', $id);
    mysqli_stmt_execute($del);
    mysqli_stmt_close($del);
    header('Location: registrations.php?' . http_build_query(array_merge($_GET, ['page' => $page])));
    exit;
}

$current_status = $status_filter;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registrations | Equator Royal Tour CBO</title>
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
      <a href="registrations.php" class="active">Registrations</a>
      <a href="messages.php">Messages</a>
      <a href="change-password.php">Change Password</a>
      <a href="logout.php">Log Out</a>
    </nav>
  </aside>
  <main class="admin-main">
    <div class="admin-header">
      <h1>Trader Registrations</h1>
      <div class="admin-user"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></div>
    </div>

    <div class="card" style="margin-bottom:20px;">
      <form method="GET" action="registrations.php" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
        <div class="form-group" style="flex:1;min-width:200px;margin-bottom:0;">
          <label for="search">Search</label>
          <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Name, phone, or centre">
        </div>
        <div class="form-group" style="width:160px;margin-bottom:0;">
          <label for="status">Status</label>
          <select id="status" name="status">
            <option value="">All</option>
            <option value="Pending" <?php echo $current_status === 'Pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="Approved" <?php echo $current_status === 'Approved' ? 'selected' : ''; ?>>Approved</option>
            <option value="Rejected" <?php echo $current_status === 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
        <?php if ($search !== '' || $status_filter !== ''): ?>
          <a href="registrations.php" class="btn btn-outline">Clear</a>
        <?php endif; ?>
      </form>
    </div>

    <div style="overflow-x:auto;">
      <table>
        <thead>
          <tr>
            <th>Name</th><th>ID Number</th><th>Phone</th><th>Centre</th><th>Produce</th><th>Gender</th><th>Status</th><th>Date</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($registrations) === 0): ?>
            <tr><td colspan="9" style="text-align:center;padding:20px;">No registrations found.</td></tr>
          <?php else: ?>
            <?php while ($r = mysqli_fetch_assoc($registrations)): ?>
              <tr>
                <td><?php echo htmlspecialchars($r['full_name']); ?></td>
                <td><?php echo htmlspecialchars(decrypt_field($r['id_number'])); ?></td>
                <td><?php echo htmlspecialchars($r['phone']); ?></td>
                <td><?php echo htmlspecialchars($r['trading_centre']); ?></td>
                <td><?php echo htmlspecialchars($r['produce_type'] ?: '-'); ?></td>
                <td><?php echo htmlspecialchars($r['gender']); ?></td>
                <td><span class="badge badge-<?php echo strtolower($r['status']); ?>"><?php echo htmlspecialchars($r['status']); ?></span></td>
                <td><?php echo htmlspecialchars(date('d M Y', strtotime($r['created_at']))); ?></td>
                <td>
                   <form method="POST" action="registrations.php?<?php echo htmlspecialchars(http_build_query($_GET)); ?>" style="display:inline;">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                    <input type="hidden" name="registration_id" value="<?php echo (int) $r['id']; ?>">
                    <select name="status" onchange="this.form.submit()" style="padding:4px 8px;font-size:12px;">
                      <option value="">Set</option>
                      <option value="Pending">Pending</option>
                      <option value="Approved">Approve</option>
                      <option value="Rejected">Reject</option>
                    </select>
                  </form>
                   <form method="POST" action="registrations.php?<?php echo htmlspecialchars(http_build_query($_GET)); ?>" style="display:inline;" onsubmit="return confirm('Delete this registration?');">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                    <input type="hidden" name="delete_id" value="<?php echo (int) $r['id']; ?>">
                    <button type="submit" class="btn btn-sm btn-danger">Del</button>
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
