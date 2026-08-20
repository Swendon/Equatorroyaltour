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
$totalUploads = mysqli_fetch_assoc(mysqli_query($conn, 'SELECT COUNT(*) AS c FROM uploads'))['c'];
$totalProposals = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM uploads WHERE category = 'proposal'"))['c'];
$recentReg = mysqli_query($conn, 'SELECT full_name, trading_centre, status, created_at FROM registrations ORDER BY created_at DESC LIMIT 5');
$recentMsg = mysqli_query($conn, 'SELECT name, email, subject, created_at FROM messages ORDER BY created_at DESC LIMIT 5');

$galleryMessage = '';
$galleryError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'gallery_upload') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        $galleryError = 'Invalid request.';
    } elseif (empty($_FILES['gallery_photo']) || $_FILES['gallery_photo']['error'] !== UPLOAD_ERR_OK) {
        $upload_error = $_FILES['gallery_photo']['error'] ?? UPLOAD_ERR_NO_FILE;
        switch ($upload_error) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $galleryError = 'File is too large. Maximum size is 10MB (check upload_max_filesize and post_max_size in php.ini).';
                break;
            case UPLOAD_ERR_NO_FILE:
                $galleryError = 'Please select a photo to upload.';
                break;
            default:
                $galleryError = 'Upload failed with error code ' . $upload_error . '.';
        }
    } else {
        $file = $_FILES['gallery_photo'];
        $original_name = $file['name'];
        $file_size = $file['size'];
        $file_tmp = $file['tmp_name'];
        $file_ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

        $allowed_types = ['jpg', 'jpeg', 'png', 'webp'];
        $max_size = 10 * 1024 * 1024;

        if (!in_array($file_ext, $allowed_types, true)) {
            $galleryError = 'Invalid file type. Allowed: JPG, PNG, WEBP.';
        } elseif ($file_size > $max_size) {
            $galleryError = 'File is too large. Maximum size is 10MB.';
        } else {
            $new_filename = bin2hex(random_bytes(16)) . '.' . $file_ext;
            $upload_dir = UPLOAD_DIR;
            if (!is_dir($upload_dir)) {
                if (!mkdir($upload_dir, 0755, true)) {
                    $galleryError = 'Failed to create uploads directory. Please check permissions.';
                }
            }
            if (!empty($galleryError)) {
                // error already set
            } elseif (!is_writable($upload_dir)) {
                $galleryError = 'Uploads directory is not writable. Please check permissions.';
            } else {
                $destination = $upload_dir . $new_filename;

                if (move_uploaded_file($file_tmp, $destination)) {
                    $file_type = function_exists('mime_content_type') ? (mime_content_type($destination) ?: 'application/octet-stream') : 'application/octet-stream';
                    $stmt = mysqli_prepare($conn, 'INSERT INTO uploads (filename, original_name, file_type, file_size, category) VALUES (?, ?, ?, ?, ?)');
                    if (!$stmt) {
                        $galleryError = 'Database error: ' . mysqli_error($conn);
                    } else {
                    mysqli_stmt_bind_param($stmt, 'sssis', $new_filename, $original_name, $file_type, $file_size, 'gallery');
                    if (mysqli_stmt_execute($stmt)) {
                        $galleryMessage = 'Photo uploaded to gallery successfully.';
                    } else {
                        $galleryError = 'Database error: ' . mysqli_stmt_error($stmt);
                    }
                    mysqli_stmt_close($stmt);
                    }
                } else {
                    $galleryError = 'Failed to move uploaded file.';
                }
            }
        }
    }
}
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
      <a href="uploads.php">Uploads</a>
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
      <div class="stat-card">
        <div class="num"><?php echo $totalUploads; ?></div>
        <div class="label">Total Files</div>
      </div>
      <div class="stat-card">
        <div class="num"><?php echo $totalProposals; ?></div>
        <div class="label">Proposals</div>
      </div>
    </div>

    <div class="grid" style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
      <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
          <h3 style="margin:0;">Upload to Gallery</h3>
        </div>
        <?php if ($galleryMessage): ?>
          <div class="alert alert-success"><?php echo htmlspecialchars($galleryMessage); ?></div>
        <?php endif; ?>
        <?php if ($galleryError): ?>
          <div class="alert alert-error"><?php echo htmlspecialchars($galleryError); ?></div>
        <?php endif; ?>
        <form method="POST" action="dashboard.php" enctype="multipart/form-data">
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
          <input type="hidden" name="action" value="gallery_upload">
          <div class="form-group">
            <label for="gallery_photo">Select Photo</label>
            <input type="file" id="gallery_photo" name="gallery_photo" required accept=".jpg,.jpeg,.png,.webp">
            <small style="color:var(--muted);">Allowed: JPG, PNG, WEBP. Max 10MB. Uploads go directly to the gallery.</small>
          </div>
          <button type="submit" class="btn btn-primary">Upload to Gallery</button>
        </form>
      </div>

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
