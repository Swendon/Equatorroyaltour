<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'Home';
$active = 'home';
$basePath = '';
include __DIR__ . '/includes/header.php';
?>

<section class="hero">
  <div class="container hero-grid">
    <div class="hero-copy">
      <div class="eyebrow">Baringo County · Est. 1926 · 100 Years of Resilience</div>
      <h1>Reviving safe trade corridors, heritage rail, and river catchment livelihoods.</h1>
      <p class="lead">Equator Royal Tour CBO is empowering 500 women traders, restoring the Mau–Mumberes catchment, and unlocking tourism and transport opportunities across the corridor.</p>
      <div class="hero-actions">
        <a href="register.php" class="btn btn-primary">Register as a Trader</a>
        <a href="project.php" class="btn btn-outline">Discover the Project</a>
      </div>
    </div>

    <aside class="hero-panel">
      <div class="hero-panel-card">
        <span class="stat-big">500+</span>
        <span>Women traders organized into cooperatives for safer, fairer markets.</span>
      </div>
      <div class="hero-panel-card">
        <span class="stat-big">10,000</span>
        <span>Indigenous trees planted to protect the Mau–Mumberes watershed.</span>
      </div>
      <div class="hero-panel-card">
        <span class="stat-big">9</span>
        <span>Trading centres connected through heritage railway and safe trading bays.</span>
      </div>
    </aside>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head">
      <div class="eyebrow">By the Numbers</div>
      <h2>Impact that strengthens communities</h2>
      <p>Tracking progress across trade, transport, and conservation to support long-term resilience.</p>
    </div>
    <div class="grid grid-4">
      <div class="stat"><div class="num">500</div><div class="label">Women Traders Organized</div></div>
      <div class="stat"><div class="num">4</div><div class="label">Safe Trading Bays</div></div>
      <div class="stat"><div class="num">10,000</div><div class="label">Trees Planted</div></div>
      <div class="stat"><div class="num">9</div><div class="label">Markets & Trading Centres</div></div>
    </div>
  </div>
</section>

<section class="section alt">
  <div class="container">
    <div class="section-head">
      <div class="eyebrow">The Challenge</div>
      <h2>Why this mission matters</h2>
      <p>Over 3,000 women traders rely on roadside markets, but lack safe shelters, sanitation, and fair market access. Our work brings dignity, protection and shared prosperity.</p>
    </div>
    <div class="grid grid-3">
      <div class="card">
        <h3>Poor Infrastructure</h3>
        <p>Roadside sellers face exposure, limited storage, and no sanitation facilities.</p>
      </div>
      <div class="card">
        <h3>Market Inequality</h3>
        <p>Women traders struggle with broker control, unstable prices, and high loan costs.</p>
      </div>
      <div class="card">
        <h3>Heritage Underused</h3>
        <p>The Equator–Timboroa rail corridor can support cargo, tourism, and safer transport.</p>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head">
      <div class="eyebrow">How We Work</div>
      <h2>Building corridors for trade, travel and conservation</h2>
      <p>From safe trading bays to watershed restoration, every activity is designed to protect livelihoods and reconnect communities.</p>
    </div>
    <div class="grid grid-3">
      <div class="card">
        <h3>Safe Trade Bays</h3>
        <p>Secure market spaces with shade, storage and wash facilities for women traders.</p>
      </div>
      <div class="card">
        <h3>Railway Revival</h3>
        <p>Rehabilitating heritage railway stops to promote cargo movement and tourist routes.</p>
      </div>
      <div class="card">
        <h3>Catchment Restoration</h3>
        <p>Planting 10,000 indigenous trees and protecting the Mau–Mumberes watershed.</p>
      </div>
    </div>
  </div>
</section>

<section class="section alt">
  <div class="container">
    <div class="section-head">
      <div class="eyebrow">Trading Centres</div>
      <h2>Registration is open across nine centres</h2>
    </div>
    <div class="centres-list">
      <span class="centre-chip">Nakuru Railway</span>
      <span class="centre-chip">Makutano</span>
      <span class="centre-chip">Mlango Moja</span>
      <span class="centre-chip">Mlango Tatu</span>
      <span class="centre-chip">Mlango Nne</span>
      <span class="centre-chip">Equator</span>
      <span class="centre-chip">Hill Tea</span>
      <span class="centre-chip">Boito</span>
      <span class="centre-chip">Timboroa</span>
    </div>
  </div>
</section>

<section class="section">
  <div class="container two-col">
    <div>
      <div class="eyebrow">Our Vision</div>
      <h2>A resilient, thriving Mumberes corridor</h2>
      <p>We are working toward a future where women traders have safe markets, the river catchment is protected, and the railway is a shared asset for tourism and commerce.</p>
      <a href="about.php" class="btn btn-primary">Read Our Story</a>
    </div>
    <div class="card">
      <h3>Banking Details</h3>
      <p><strong>Account Name:</strong> Equator Royal Tour</p>
      <p><strong>Banks:</strong> Equity Bank &amp; KCB</p>
      <p><strong>Branch:</strong> Eldama Ravine</p>
      <p style="margin-top:12px;"><strong>Online Registration:</strong> www.equatorroyaltour.com</p>
    </div>
  </div>
</section>

<section class="section cta-banner">
  <div class="container cta-grid">
    <div>
      <h3>Want to support the corridor?</h3>
      <p>Partner with us to expand safe trading bays, restore heritage infrastructure, and protect the watershed.</p>
    </div>
    <a href="contact.php" class="btn btn-primary">Contact Us</a>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
