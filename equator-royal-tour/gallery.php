<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'Project Gallery';
$pageDescription = 'Photo gallery of the Equator Royal Tour CBO project — safe trade bays, heritage railway revival, catchment restoration, and women traders across Baringo County, Kenya.';
$active = 'gallery';
$basePath = '';
$showFloatingWhatsapp = true;

$gallery = mysqli_query($conn, "SELECT filename, original_name, created_at FROM uploads WHERE category = 'gallery' ORDER BY created_at DESC");
$total_gallery = mysqli_num_rows($gallery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($pageTitle); ?> | Equator Royal Tour CBO</title>
<link rel="stylesheet" href="<?php echo $basePath ?? ''; ?>css/style.css?v=<?php echo filemtime(__DIR__ . '/css/style.css'); ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
  .gallery-hero {
    background: linear-gradient(120deg, rgba(31,61,43,0.85), rgba(31,61,43,0.7));
    color: var(--cream);
    padding: 60px 0 50px;
    text-align: center;
  }
  .gallery-hero h1 { margin: 0 0 10px; font-size: 36px; }
  .gallery-hero p { margin: 0; opacity: 0.9; font-size: 16px; }
  .gallery-page-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
  }
  .gallery-page-item {
    position: relative;
    border-radius: var(--radius);
    overflow: hidden;
    cursor: pointer;
    box-shadow: var(--shadow);
    background: var(--white);
  }
  .gallery-page-item img {
    width: 100%;
    height: 240px;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
  }
  .gallery-page-item:hover img { transform: scale(1.03); }
  .gallery-page-item .gallery-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(0deg, rgba(31,61,43,0.75) 0%, rgba(31,61,43,0.0) 50%);
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    align-items: flex-end;
    padding: 16px;
  }
  .gallery-page-item:hover .gallery-overlay { opacity: 1; }
  .gallery-page-item .gallery-overlay span {
    color: var(--cream);
    font-size: 14px;
    font-weight: 600;
  }
  .gallery-empty {
    text-align: center;
    padding: 60px 20px;
    color: var(--muted);
  }
  .gallery-empty i { font-size: 48px; margin-bottom: 16px; opacity: 0.4; }
  .gallery-empty h3 { margin: 0 0 10px; color: var(--ink); }
  .lightbox {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.9);
    z-index: 1000;
    justify-content: center;
    align-items: center;
    padding: 40px 20px;
  }
  .lightbox.active { display: flex; }
  .lightbox img {
    max-width: 90%;
    max-height: 85vh;
    border-radius: var(--radius);
    box-shadow: 0 20px 60px rgba(0,0,0,0.5);
  }
  .lightbox-close {
    position: absolute;
    top: 20px;
    right: 30px;
    color: var(--cream);
    font-size: 36px;
    cursor: pointer;
    background: none;
    border: none;
    line-height: 1;
  }
  .lightbox-caption {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    color: var(--cream);
    background: rgba(31,61,43,0.85);
    padding: 10px 20px;
    border-radius: 6px;
    font-size: 14px;
    max-width: 90%;
    text-align: center;
  }
  @media (max-width: 768px) {
    .gallery-hero h1 { font-size: 26px; }
    .gallery-page-grid { grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 14px; }
    .gallery-page-item img { height: 180px; }
  }
</style>
</head>
<body>
<section class="gallery-hero">
  <div class="container">
    <h1>Project Gallery</h1>
    <p>Photos from the corridor — heritage railway, safe trade bays, catchment restoration, and our traders.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <?php if ($total_gallery === 0): ?>
      <div class="gallery-empty">
        <i class="fa-regular fa-images"></i>
        <h3>No photos yet</h3>
        <p>The admin will upload project photos here soon. Check back later.</p>
      </div>
    <?php else: ?>
      <div class="gallery-page-grid">
        <?php while ($g = mysqli_fetch_assoc($gallery)): ?>
          <div class="gallery-page-item" onclick="openLightbox('uploads/<?php echo htmlspecialchars(rawurlencode($g['filename'])); ?>', '<?php echo htmlspecialchars(addslashes($g['original_name'])); ?>')">
            <img src="uploads/<?php echo htmlspecialchars(rawurlencode($g['filename'])); ?>" alt="<?php echo htmlspecialchars($g['original_name']); ?>" loading="lazy">
            <div class="gallery-overlay">
              <span><?php echo htmlspecialchars($g['original_name']); ?></span>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<div class="lightbox" id="lightbox" onclick="closeLightbox()">
  <button class="lightbox-close" aria-label="Close">&times;</button>
  <img src="" alt="" id="lightbox-img">
  <div class="lightbox-caption" id="lightbox-caption"></div>
</div>

<script>
function openLightbox(src, caption) {
  const lightbox = document.getElementById('lightbox');
  const img = document.getElementById('lightbox-img');
  const cap = document.getElementById('lightbox-caption');
  img.src = src;
  cap.textContent = caption;
  lightbox.classList.add('active');
  document.body.style.overflow = 'hidden';
}
function closeLightbox() {
  const lightbox = document.getElementById('lightbox');
  lightbox.classList.remove('active');
  document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeLightbox();
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
