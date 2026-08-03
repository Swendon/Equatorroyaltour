<?php if (!isset($pageTitle)) { $pageTitle = 'Equator Royal Tour CBO'; } ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($pageTitle); ?> | Equator Royal Tour CBO</title>
<link rel="stylesheet" href="<?php echo $basePath ?? ''; ?>css/style.css?v=<?php echo filemtime(__DIR__ . '/../css/style.css'); ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
<header class="site-header">
  <div class="container">
    <a href="<?php echo $basePath ?? ''; ?>index.php" class="brand">
      <span class="brand-mark">ERT</span>
      <span class="brand-text">
        <span class="title">Equator Royal Tour</span>
        <span class="subtitle">Community Based Organization</span>
      </span>
    </a>
    <button class="mobile-toggle">Menu</button>
    <nav class="nav-links">
      <a href="<?php echo $basePath ?? ''; ?>index.php" class="<?php echo ($active ?? '') === 'home' ? 'active' : ''; ?>">Home</a>
      <a href="<?php echo $basePath ?? ''; ?>about.php" class="<?php echo ($active ?? '') === 'about' ? 'active' : ''; ?>">About Us</a>
      <a href="<?php echo $basePath ?? ''; ?>project.php" class="<?php echo ($active ?? '') === 'project' ? 'active' : ''; ?>">The Project</a>
      <a href="<?php echo $basePath ?? ''; ?>register.php" class="<?php echo ($active ?? '') === 'register' ? 'active' : ''; ?>">Trader Registration</a>
      <a href="<?php echo $basePath ?? ''; ?>contact.php" class="<?php echo ($active ?? '') === 'contact' ? 'active' : ''; ?>">Contact</a>
    </nav>
  </div>
</header>
