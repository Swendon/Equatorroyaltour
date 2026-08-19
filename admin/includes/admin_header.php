<?php
$pageTitle = $pageTitle ?? 'Admin Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($pageTitle); ?> | Equator Royal Tour CBO</title>
<link rel="stylesheet" href="../css/style.css">
<style>
  .admin-layout { display: grid; grid-template-columns: 240px 1fr; min-height: 100vh; }
  .admin-sidebar { background: var(--green-deep); color: var(--cream); padding: 20px 0; }
  .admin-sidebar .brand { padding: 0 20px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 10px; }
  .admin-sidebar .brand-mark { color: var(--amber); font-weight: 700; font-size: 18px; }
  .admin-sidebar .brand-text { display: block; color: var(--cream); font-size: 13px; margin-top: 4px; }
  .admin-nav a { display: block; padding: 12px 20px; color: var(--cream); text-decoration: none; font-size: 14px; border-left: 3px solid transparent; }
  .admin-nav a:hover, .admin-nav a.active { background: rgba(255,255,255,0.05); border-left-color: var(--amber); }
  .admin-nav a i { margin-right: 8px; width: 16px; text-align: center; }
  .admin-main { padding: 24px; background: var(--cream); }
  .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
  .admin-header h1 { margin: 0; font-size: 24px; }
  .admin-user { display: flex; align-items: center; gap: 12px; font-size: 14px; color: var(--muted); }
  .card { background: var(--white); border-radius: var(--radius); padding: 20px; box-shadow: var(--shadow); }
  .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
  .stat-card { background: var(--white); border-radius: var(--radius); padding: 20px; box-shadow: var(--shadow); border-top: 4px solid var(--amber); }
  .stat-card .num { font-size: 32px; font-weight: 700; color: var(--green-deep); }
  .stat-card .label { font-size: 13px; color: var(--muted); margin-top: 4px; }
  .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; border: none; cursor: pointer; }
  .btn-primary { background: var(--amber); color: var(--green-deep); }
  .btn-primary:hover { background: var(--amber-light); }
  .btn-outline { background: transparent; border: 1px solid var(--green-deep); color: var(--green-deep); }
  .btn-sm { padding: 4px 10px; font-size: 12px; }
  .btn-danger { background: #dc2626; color: white; }
  .btn-success { background: #16a34a; color: white; }
  .btn-warning { background: #d97706; color: white; }
  table { width: 100%; border-collapse: collapse; background: var(--white); border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); }
  th, td { padding: 12px 14px; text-align: left; font-size: 14px; border-bottom: 1px solid #e5e7eb; }
  th { background: var(--green-deep); color: var(--cream); font-weight: 600; }
  .badge { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
  .badge-pending { background: #fef3c7; color: #92400e; }
  .badge-approved { background: #d1fae5; color: #065f46; }
  .badge-rejected { background: #fee2e2; color: #991b1b; }
  .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; font-size: 14px; }
  .alert-success { background: #d1fae5; color: #065f46; }
  .alert-error { background: #fee2e2; color: #991b1b; }
  .form-group { margin-bottom: 14px; }
  .form-group label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; }
  .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; }
  .form-actions { display: flex; gap: 8px; }
  @media (max-width: 768px) {
    .admin-layout { grid-template-columns: 1fr; }
    .admin-sidebar { display: none; }
  }
</style>
</head>
<body>
<div class="admin-layout">
  <aside class="admin-sidebar">
    <div class="brand">
      <span class="brand-mark">ERT Admin</span>
      <span class="brand-text">Equator Royal Tour CBO</span>
    </div>
    <nav class="admin-nav">
      <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>"><i>&#9632;</i> Dashboard</a>
      <a href="registrations.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'registrations.php' ? 'active' : ''; ?>"><i>&#9786;</i> Registrations</a>
      <a href="messages.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'messages.php' ? 'active' : ''; ?>"><i>&#9993;</i> Messages</a>
      <a href="change-password.php"><i>&#9733;</i> Change Password</a>
      <a href="logout.php"><i>&#8594;</i> Log Out</a>
    </nav>
  </aside>
  <main class="admin-main">
