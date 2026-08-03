<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'About Us';
$active = 'about';
$basePath = '';
include __DIR__ . '/includes/header.php';
?>

<section class="page-banner">
  <div class="container">
    <h1>About Equator Royal Tour CBO</h1>
    <p>A registered community organization from Baringo County, Kenya.</p>
  </div>
</section>

<section class="section">
  <div class="container two-col">
    <div>
      <h2>Who we are</h2>
      <p>Equator Royal Tour is a registered Community-Based Organization dedicated to promoting sustainable livelihoods, heritage conservation, environmental restoration, tourism development, and economic empowerment within Baringo County and surrounding regions.</p>
      <p>We work closely with communities, government agencies, development partners, and private sector stakeholders to develop innovative solutions that combine local economic development with environmental conservation.</p>
      <p>Our roots trace back to the Nakuru–Eldoret trading corridor, active since 1926 — marking 100 years of resilience among the women and families who have traded along this route.</p>
    </div>
    <div class="grid" style="gap:20px;">
      <div class="card">
        <h3>Our Vision</h3>
        <p>A prosperous, environmentally sustainable and economically empowered community where heritage, agriculture, tourism and enterprise work together for inclusive development.</p>
      </div>
      <div class="card">
        <h3>Our Mission</h3>
        <p>To improve community livelihoods through sustainable economic empowerment, heritage preservation, environmental conservation, cooperative development, and strategic partnerships.</p>
      </div>
    </div>
  </div>
</section>

<section class="section alt">
  <div class="container">
    <div class="section-head">
      <div class="eyebrow">Our Partners</div>
      <h2>Working together across sectors</h2>
    </div>
    <div class="grid grid-4">
      <div class="card"><h3>KeNHA</h3><p>Trading bay approvals, road safety infrastructure, legal protection of markets.</p></div>
      <div class="card"><h3>Kenya Railways</h3><p>Station rehabilitation, cargo transportation, heritage train operations.</p></div>
      <div class="card"><h3>Baringo County</h3><p>Cooperative support, market policy, environmental conservation.</p></div>
      <div class="card"><h3>Corporate Partners</h3><p>CSR support for cold storage, the Debt Liberation Fund, water conservation and tourism.</p></div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
