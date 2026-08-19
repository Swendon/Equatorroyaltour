<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/auth_check.php';

$totalReg = mysqli_fetch_assoc(mysqli_query($conn, 'SELECT COUNT(*) AS c FROM registrations'))['c'];
$pendingReg = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM registrations WHERE status = 'Pending'"))['c'];
$approvedReg = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM registrations WHERE status = 'Approved'"))['c'];
$rejectedReg = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM registrations WHERE status = 'Rejected'"))['c'];
$totalMessages = mysqli_fetch_assoc(mysqli_query($conn, 'SELECT COUNT(*) AS c FROM messages'))['c'];
$unreadMessages = mysqli_fetch_assoc(mysqli_query($conn, 'SELECT COUNT(*) AS c FROM messages'))['c'];
$totalAdmins = mysqli_fetch_assoc(mysqli_query($conn, 'SELECT COUNT(*) AS c FROM admins'))['c'];
$recentReg = mysqli_query($conn, 'SELECT full_name, trading_centre, status, created_at FROM registrations ORDER BY created_at DESC LIMIT 5');
$recentMsg = mysqli_query($conn, 'SELECT name, email, subject, created_at FROM messages ORDER BY created_at DESC LIMIT 5');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard | Equator Royal Tour CBO</title>
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
      <a href="dashboard.php" class="active">Dashboard</a>
      <a href="registrations.php">Registrations</a>
      <a href="messages.php">Messages</a>
      <a href="change-password.php">Change Password</a>
      <a href="logout.php">Log Out</a>
    </nav>
  </aside>
  <main class="admin-main">
    <div class="admin-header">
      <h1>Dashboard</h1>
      <div class="admin-user">Logged in as <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong></div>
    </div>

    <div class="stat-grid">
      <div class="stat-card">
        <div class="num"><?php echo $totalReg; ?></div>
        <div class="label">Total Registrations</div>
      </div>
      <div class="stat-card">
        <div class="num"><?php echo $pendingReg; ?></div>
        <div class="label">Pending Review</div>
      </div>
      <div class="stat-card">
        <div class="num"><?php echo $approvedReg; ?></div>
        <div class="label">Approved Traders</div>
      </div>
      <div class="stat-card">
        <div class="num"><?php echo $rejectedReg; ?></div>
        <div class="label">Rejected</div>
      </div>
      <div class="stat-card">
        <div class="num"><?php echo $totalMessages; ?></div>
        <div class="label">Contact Messages</div>
      </div>
      <div class="stat-card">
        <div class="num"><?php echo $totalAdmins; ?></div>
        <div class="label">Admin Users</div>
      </div>
    </div>

    <div class="grid" style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
      <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
          <h3 style="margin:0;">Recent Registrations</h3>
          <a href="registrations.php" class="btn btn-sm btn-outline">View All</a>
        </div>
        <?php if (mysqli_num_rows($recentReg) === 0): ?>
          <p style="color:var(--muted);font-size:14px;">No registrations yet.</p>
        <?php else: ?>
          <table>
            <thead><tr><th>Name</th><th>Centre</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
              <?php while ($r = mysqli_fetch_assoc($recentReg)): ?>
                <tr>
                  <td><?php echo htmlspecialchars($r['full_name']); ?></td>
                  <td><?php echo htmlspecialchars($r['trading_centre']); ?></td>
                  <td><span class="badge badge-<?php echo strtolower($r['status']); ?>"><?php echo htmlspecialchars($r['status']); ?></span></td>
                  <td><?php echo htmlspecialchars(date('d M Y', strtotime($r['created_at']))); ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>

      <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
          <h3 style="margin:0;">Recent Messages</h3>
          <a href="messages.php" class="btn btn-sm btn-outline">View All</a>
        </div>
        <?php if (mysqli_num_rows($recentMsg) === 0): ?>
          <p style="color:var(--muted);font-size:14px;">No messages yet.</p>
        <?php else: ?>
          <table>
            <thead><tr><th>Name</th><th>Subject</th><th>Date</th></tr></thead>
            <tbody>
              <?php while ($m = mysqli_fetch_assoc($recentMsg)): ?>
                <tr>
                  <td><?php echo htmlspecialchars($m['name']); ?></td>
                  <td><?php echo htmlspecialchars($m['subject'] ?: '(no subject)'); ?></td>
                  <td><?php echo htmlspecialchars(date('d M Y', strtotime($m['created_at']))); ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>
  </main>
</div>
</body>
</html>
