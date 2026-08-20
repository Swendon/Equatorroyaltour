<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/auth_check.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'upload') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        $error = 'Invalid request.';
    } elseif (empty($_FILES['upload_file']) || $_FILES['upload_file']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Please select a file to upload.';
    } else {
        $file = $_FILES['upload_file'];
        $original_name = $file['name'];
        $file_size = $file['size'];
        $file_tmp = $file['tmp_name'];
        $file_ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
        $category = $_POST['category'] ?? 'document';

        $allowed_types = ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'doc', 'docx'];
        $max_size = 10 * 1024 * 1024; // 10MB

        if (!in_array($file_ext, $allowed_types, true)) {
            $error = 'Invalid file type. Allowed: PDF, JPG, PNG, WEBP, DOC, DOCX.';
        } elseif ($file_size > $max_size) {
            $error = 'File is too large. Maximum size is 10MB.';
        } else {
            $new_filename = bin2hex(random_bytes(16)) . '.' . $file_ext;
            $upload_dir = UPLOAD_DIR;
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            if (!is_writable($upload_dir)) {
                $error = 'Uploads directory is not writable. Please check permissions.';
            } else {
                $destination = $upload_dir . $new_filename;

                if (move_uploaded_file($file_tmp, $destination)) {
                $file_type = mime_content_type($destination) ?: 'application/octet-stream';
                $stmt = mysqli_prepare($conn, 'INSERT INTO uploads (filename, original_name, file_type, file_size, category) VALUES (?, ?, ?, ?, ?)');
                mysqli_stmt_bind_param($stmt, 'sssis', $new_filename, $original_name, $file_type, $file_size, $category);
                if (mysqli_stmt_execute($stmt)) {
                    $message = 'File uploaded successfully.';
                } else {
                    $error = 'Database error. Please try again.';
                }
                mysqli_stmt_close($stmt);
            } else {
                $error = 'Failed to move uploaded file.';
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        $error = 'Invalid request.';
    } elseif (isset($_POST['upload_id'])) {
        $id = (int) $_POST['upload_id'];
        $stmt = mysqli_prepare($conn, 'SELECT filename FROM uploads WHERE id = ?');
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($row) {
            $filepath = UPLOAD_DIR . $row['filename'];
            if (file_exists($filepath)) {
                unlink($filepath);
            }
            $del = mysqli_prepare($conn, 'DELETE FROM uploads WHERE id = ?');
            mysqli_stmt_bind_param($del, 'i', $id);
            mysqli_stmt_execute($del);
            mysqli_stmt_close($del);
            $message = 'File deleted successfully.';
        }
    }
}

$filter_category = $_GET['category'] ?? '';
$search = trim($_GET['search'] ?? '');

$where = [];
$params = [];
$types = '';

if ($filter_category !== '' && in_array($filter_category, ['proposal', 'gallery', 'document'], true)) {
    $where[] = 'category = ?';
    $params[] = $filter_category;
    $types .= 's';
}
if ($search !== '') {
    $where[] = '(original_name LIKE ? OR filename LIKE ?)';
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
    $types .= 'ss';
}

$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$count_sql = "SELECT COUNT(*) AS c FROM uploads {$where_sql}";
$count_stmt = mysqli_prepare($conn, $count_sql);
if ($params) {
    mysqli_stmt_bind_param($count_stmt, $types, ...$params);
}
mysqli_stmt_execute($count_stmt);
$total_uploads = mysqli_fetch_assoc(mysqli_stmt_get_result($count_stmt))['c'];
mysqli_stmt_close($count_stmt);

$sql = "SELECT * FROM uploads {$where_sql} ORDER BY created_at DESC";
$stmt = mysqli_prepare($conn, $sql);
if ($params) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$uploads = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

$total_proposals = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM uploads WHERE category = 'proposal'"))['c'];
$total_gallery = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM uploads WHERE category = 'gallery'"))['c'];
$total_documents = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM uploads WHERE category = 'document'"))['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Uploads | Equator Royal Tour CBO</title>
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
      <a href="messages.php">Messages</a>
      <a href="uploads.php" class="active">Uploads</a>
      <a href="change-password.php">Change Password</a>
      <a href="logout.php">Log Out</a>
    </nav>
  </aside>
  <main class="admin-main">
    <div class="admin-header">
      <h1>File Uploads</h1>
      <div class="admin-user"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></div>
    </div>

    <div class="stat-grid">
      <div class="stat-card">
        <div class="num"><?php echo $total_uploads; ?></div>
        <div class="label">Total Files</div>
      </div>
      <div class="stat-card">
        <div class="num"><?php echo $total_proposals; ?></div>
        <div class="label">Proposals</div>
      </div>
      <div class="stat-card">
        <div class="num"><?php echo $total_gallery; ?></div>
        <div class="label">Gallery Photos</div>
      </div>
      <div class="stat-card">
        <div class="num"><?php echo $total_documents; ?></div>
        <div class="label">Documents</div>
      </div>
    </div>

    <div class="card" style="margin-bottom:20px;">
      <h3 style="margin-top:0;">Upload New File</h3>
      <?php if ($message): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
      <?php endif; ?>
      <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
      <form method="POST" action="uploads.php" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <input type="hidden" name="action" value="upload">
        <div class="form-group">
          <label for="upload_file">Select File</label>
          <input type="file" id="upload_file" name="upload_file" required accept=".pdf,.jpg,.jpeg,.png,.webp,.doc,.docx">
          <small style="color:var(--muted);">Allowed: PDF, JPG, PNG, WEBP, DOC, DOCX. Max 10MB.</small>
        </div>
        <div class="form-group">
          <label for="category">Category</label>
          <select id="category" name="category">
            <option value="gallery">Gallery Photo</option>
            <option value="proposal">Proposal</option>
            <option value="document">Document</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Upload File</button>
      </form>
    </div>

    <div class="card" style="margin-bottom:20px;">
      <form method="GET" action="uploads.php" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
        <div class="form-group" style="flex:1;min-width:200px;margin-bottom:0;">
          <label for="search">Search</label>
          <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Filename or original name">
        </div>
        <div class="form-group" style="width:160px;margin-bottom:0;">
          <label for="category_filter">Category</label>
          <select id="category_filter" name="category">
            <option value="">All</option>
            <option value="proposal" <?php echo $filter_category === 'proposal' ? 'selected' : ''; ?>>Proposals</option>
            <option value="gallery" <?php echo $filter_category === 'gallery' ? 'selected' : ''; ?>>Gallery</option>
            <option value="document" <?php echo $filter_category === 'document' ? 'selected' : ''; ?>>Documents</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
        <?php if ($search !== '' || $filter_category !== ''): ?>
          <a href="uploads.php" class="btn btn-outline">Clear</a>
        <?php endif; ?>
      </form>
    </div>

    <div style="overflow-x:auto;">
      <table>
        <thead>
          <tr>
            <th>Preview</th>
            <th>Original Name</th>
            <th>Category</th>
            <th>Size</th>
            <th>Uploaded</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($uploads) === 0): ?>
            <tr><td colspan="6" style="text-align:center;padding:20px;">No files uploaded yet.</td></tr>
          <?php else: ?>
            <?php while ($u = mysqli_fetch_assoc($uploads)): ?>
              <?php
                $ext = strtolower(pathinfo($u['filename'], PATHINFO_EXTENSION));
                $is_image = in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true);
                $file_url = '../' . UPLOAD_URL . rawurlencode($u['filename']);
                $size_kb = round($u['file_size'] / 1024, 1);
                $size_display = $size_kb > 1024 ? round($size_kb / 1024, 1) . ' MB' : $size_kb . ' KB';
              ?>
              <tr>
                <td style="width:80px;">
                  <?php if ($is_image): ?>
                    <img src="<?php echo htmlspecialchars($file_url); ?>" alt="" style="width:60px;height:60px;object-fit:cover;border-radius:4px;border:1px solid #ddd;">
                  <?php else: ?>
                    <div style="width:60px;height:60px;display:flex;align-items:center;justify-content:center;background:#f3f4f6;border-radius:4px;font-size:11px;color:var(--muted);text-align:center;padding:4px;"><?php echo strtoupper($ext); ?></div>
                  <?php endif; ?>
                </td>
                <td>
                  <a href="<?php echo htmlspecialchars($file_url); ?>" target="_blank" rel="noopener noreferrer">
                    <?php echo htmlspecialchars($u['original_name']); ?>
                  </a>
                </td>
                <td><span class="badge badge-<?php echo $u['category'] === 'proposal' ? 'approved' : ($u['category'] === 'gallery' ? 'pending' : 'rejected'); ?>"><?php echo htmlspecialchars($u['category']); ?></span></td>
                <td><?php echo $size_display; ?></td>
                <td><?php echo htmlspecialchars(date('d M Y', strtotime($u['created_at']))); ?></td>
                <td>
                  <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this file?');">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="upload_id" value="<?php echo (int) $u['id']; ?>">
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>
</body>
</html>
