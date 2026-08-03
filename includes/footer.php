<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <div>
        <h4>Equator Royal Tour CBO</h4>
        <p style="color:#c9d1c2;font-size:14px;">Mumberes Safe Trade Corridor, Railway &amp; Catchment Protection Project — Baringo County, Kenya.</p>
      </div>
      <div>
        <h4>Quick Links</h4>
        <p><a href="<?php echo $basePath ?? ''; ?>about.php">About Us</a></p>
        <p><a href="<?php echo $basePath ?? ''; ?>project.php">The Project</a></p>
        <p><a href="<?php echo $basePath ?? ''; ?>register.php">Trader Registration</a></p>
      </div>
      <div>
        <h4>Registration Centres</h4>
        <p>Nakuru Railway &middot; Makutano &middot; Mlango Moja</p>
        <p>Mlango Tatu &middot; Mlango Nne &middot; Equator</p>
        <p>Hill Tea &middot; Boito &middot; Timboroa</p>
      </div>
      <div>
        <h4>Banking Details</h4>
        <p>Account Name: Equator Royal Tour</p>
        <p>Equity Bank &amp; KCB — Eldama Ravine Branch</p>
      </div>
    </div>
    <div class="footer-bottom">
      &copy; <?php echo date('Y'); ?> Equator Royal Tour Community-Based Organization. All rights reserved.
    </div>
  </div>
</footer>
<?php if (!isset($showFloatingWhatsapp)) { $showFloatingWhatsapp = false; } ?>
<?php if ($showFloatingWhatsapp): ?>
<!-- WhatsApp Floating Button -->
<a href="https://wa.me/254724696687?text=Hello%20Equator%20Royal%20Tour%2C%20I%20would%20like%20more%20information."
   class="whatsapp"
   target="_blank"
   rel="noopener noreferrer"
   aria-label="Chat with AI WhatsApp at +254 724 696 687">
  <span class="whatsapp-icon"><i class="fab fa-whatsapp" aria-hidden="true"></i></span>
  <span class="whatsapp-copy">
    <strong>AI WhatsApp</strong>
    <span>+254 724 696 687</span>
  </span>
</a>
<?php endif; ?>
<script src="<?php echo $basePath ?? ''; ?>js/main.js"></script>
</body>
</html>
