<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/auth_check.php';

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registration_id'], $_POST['status'])) {
    $id = (int) $_POST['registration_id'];
    $status = in_array($_POST['status'], ['Pending', 'Approved', 'Rejected'], true) ? $_POST['status'] : 'Pending';
    $stmt = mysqli_prepare($conn, 'UPDATE registrations SET status = ? WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'si', $status, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header('Location: dashboard.php');
    exit;
}

$totalResult = mysqli_query($conn, 'SELECT COUNT(*) AS c FROM registrations');
$total = mysqli_fetch_assoc($totalResult)['c'];

$pendingResult = mysqli_query($conn, "SELECT COUNT(*) AS c FROM registrations WHERE status = 'Pending'");
$pending = mysqli_fetch_assoc($pendingResult)['c'];

$approvedResult = mysqli_query($conn, "SELECT COUNT(*) AS c FROM registrations WHERE status = 'Approved'");
$approved = mysqli_fetch_assoc($approvedResult)['c'];

$registrations = mysqli_query($conn, 'SELECT * FROM registrations ORDER BY created_at DESC');
$messages = mysqli_query($conn, 'SELECT * FROM messages ORDER BY created_at DESC LIMIT 10');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | Equator Royal Tour CBO</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header class="site-header">
  <div class="container">
    <div class="brand">
      <span class="brand-mark">ERT</span>
      <span class="brand-text">
        <span class="title">Admin Dashboard</span>
        <span class="subtitle">Equator Royal Tour CBO</span>
      </span>
    </div>
    <nav class="nav-links">
      <span style="color:var(--cream);">Logged in as <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
      <a href="logout.php">Log Out</a>
    </nav>
  </div>
</header>

<section class="section">
  <div class="container">
    <div class="grid grid-3" style="margin-bottom:36px;">
      <div class="stat"><div class="num"><?php echo $total; ?></div><div class="label">Total Registrations</div></div>
      <div class="stat"><div class="num"><?php echo $pending; ?></div><div class="label">Pending Review</div></div>
      <div class="stat"><div class="num"><?php echo $approved; ?></div><div class="label">Approved Traders</div></div>
    </div>

    <h2>Trader Registrations</h2>
    <div style="overflow-x:auto;">
      <table>
        <thead>
          <tr>
            <th>Name</th><th>ID Number</th><th>Phone</th><th>Centre</th><th>Produce</th><th>Gender</th><th>Status</th><th>Date</th><th>Update</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($registrations) === 0): ?>
            <tr><td colspan="9">No registrations yet.</td></tr>
          <?php else: ?>
            <?php while ($r = mysqli_fetch_assoc($registrations)): ?>
              <tr>
                <td><?php echo htmlspecialchars($r['full_name']); ?></td>
                <td><?php echo htmlspecialchars($r['id_number']); ?></td>
                <td><?php echo htmlspecialchars($r['phone']); ?></td>
                <td><?php echo htmlspecialchars($r['trading_centre']); ?></td>
                <td><?php echo htmlspecialchars($r['produce_type']); ?></td>
                <td><?php echo htmlspecialchars($r['gender']); ?></td>
                <td><span class="badge badge-<?php echo strtolower($r['status']); ?>"><?php echo htmlspecialchars($r['status']); ?></span></td>
                <td><?php echo htmlspecialchars(date('d M Y', strtotime($r['created_at']))); ?></td>
                <td>
                  <form method="POST" style="display:flex;gap:6px;">
                    <input type="hidden" name="registration_id" value="<?php echo (int) $r['id']; ?>">
                    <select name="status" onchange="this.form.submit()">
                      <option value="Pending" <?php echo $r['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                      <option value="Approved" <?php echo $r['status'] === 'Approved' ? 'selected' : ''; ?>>Approved</option>
                      <option value="Rejected" <?php echo $r['status'] === 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                    </select>
                  </form>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <h2 style="margin-top:44px;">Recent Contact Messages</h2>
    <div style="overflow-x:auto;">
      <table>
        <thead><tr><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Date</th></tr></thead>
        <tbody>
          <?php if (mysqli_num_rows($messages) === 0): ?>
            <tr><td colspan="5">No messages yet.</td></tr>
          <?php else: ?>
            <?php while ($m = mysqli_fetch_assoc($messages)): ?>
              <tr>
                <td><?php echo htmlspecialchars($m['name']); ?></td>
                <td><?php echo htmlspecialchars($m['email']); ?></td>
                <td><?php echo htmlspecialchars($m['subject']); ?></td>
                <td><?php echo htmlspecialchars($m['message']); ?></td>
                <td><?php echo htmlspecialchars(date('d M Y', strtotime($m['created_at']))); ?></td>
              </tr>
            <?php endwhile; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
