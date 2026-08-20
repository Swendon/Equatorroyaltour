<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'Home';
$pageDescription = 'Equator Royal Tour CBO homepage — empowering 500 women traders, restoring the Mau–Mumberes catchment, and unlocking tourism and transport opportunities across Baringo County, Kenya.';
$active = 'home';
$basePath = '';
$showFloatingWhatsapp = true;

$gallery = mysqli_query($conn, "SELECT filename, original_name FROM uploads WHERE category = 'gallery' ORDER BY created_at DESC LIMIT 6");
$proposal = mysqli_query($conn, "SELECT filename, original_name FROM uploads WHERE category = 'proposal' ORDER BY created_at DESC LIMIT 1");
$proposal_row = mysqli_fetch_assoc($proposal);
$proposal_url = $proposal_row ? 'uploads/' . rawurlencode($proposal_row['filename']) : 'assets/proposal.pdf';

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
      <?php
      $centres = [];
      $result = mysqli_query($conn, 'SELECT name FROM trading_centres ORDER BY name');
      if ($result) {
          while ($row = mysqli_fetch_assoc($result)) {
              $centres[] = $row['name'];
          }
      }
      foreach ($centres as $centre): ?>
        <span class="centre-chip"><?php echo htmlspecialchars($centre); ?></span>
      <?php endforeach; ?>
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
    <?php include __DIR__ . '/includes/banking-card.php'; ?>
  </div>
</section>

<section class="section alt">
  <div class="container">
    <div class="section-head">
      <div class="eyebrow">Impact Stories</div>
      <h2>Hear from our traders</h2>
      <p>Real voices from the Mumberes corridor showing how safe trading bays and cooperative support are changing lives.</p>
    </div>
    <div class="grid grid-3">
      <div class="card testimonial-card">
        <p class="testimonial-text">"Since joining the cooperative, I no longer worry about my produce getting rained on. The trading bay gives us shelter and a fair place to sell."</p>
        <p class="testimonial-author"><strong>Grace Chelimo</strong> — Trader, Timboroa Centre</p>
      </div>
      <div class="card testimonial-card">
        <p class="testimonial-text">"The financial literacy training helped me understand how to save and grow my business. I now employ two other women from my village."</p>
        <p class="testimonial-author"><strong>Mary Njeri</strong> — Trader, Equator Centre</p>
      </div>
      <div class="card testimonial-card">
        <p class="testimonial-text">"Having a recognized trading space means county officers can protect us instead of chasing us away. Our safety matters now."</p>
        <p class="testimonial-author"><strong>Sarah Chepkemoi</strong> — Trader, Boito Centre</p>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head">
      <div class="eyebrow">Heritage &amp; Environment</div>
      <h2>Corridor gallery</h2>
      <p>Historic railway infrastructure, community tree planting, and the landscapes we are working to protect.</p>
    </div>
    <div class="grid grid-3 gallery-grid">
      <?php if (mysqli_num_rows($gallery) === 0): ?>
        <div class="gallery-item">
          <div class="gallery-placeholder" role="img" aria-label="Historic Equator railway sign along the corridor">
            <span>Equator Heritage Sign</span>
          </div>
          <p class="gallery-caption">Historic Equator sign — a landmark of the corridor since 1926</p>
        </div>
        <div class="gallery-item">
          <div class="gallery-placeholder" role="img" aria-label="Timboroa Railway Station heritage restoration site">
            <span>Timboroa Station</span>
          </div>
          <p class="gallery-caption">Timboroa Railway Station — future Heritage Agricultural Hub</p>
        </div>
        <div class="gallery-item">
          <div class="gallery-placeholder" role="img" aria-label="Community tree planting in Mau Mumberes water catchment">
            <span>Catchment Restoration</span>
          </div>
          <p class="gallery-caption">Community tree planting in the Mau–Mumberes catchment</p>
        </div>
      <?php else: ?>
        <?php while ($g = mysqli_fetch_assoc($gallery)): ?>
          <div class="gallery-item">
            <img src="uploads/<?php echo htmlspecialchars(rawurlencode($g['filename'])); ?>" alt="<?php echo htmlspecialchars($g['original_name']); ?>" style="width:100%;height:220px;object-fit:cover;border-radius:var(--radius);">
            <p class="gallery-caption"><?php echo htmlspecialchars($g['original_name']); ?></p>
          </div>
        <?php endwhile; ?>
      <?php endif; ?>
    </div>
    <div style="text-align:center;margin-top:24px;">
      <a href="gallery.php" class="btn btn-primary">View Full Gallery</a>
    </div>
  </div>
</section>

<section class="section alt">
  <div class="container">
    <div class="section-head">
      <div class="eyebrow">Partner With Us</div>
      <h2>Download the full proposal</h2>
      <p>Corporate partners, NGOs, and development institutions can review the complete project proposal for investment and partnership.</p>
    </div>
    <div style="text-align:center;">
      <a href="<?php echo htmlspecialchars($proposal_url); ?>" class="btn btn-primary" target="_blank" rel="noreferrer">
        <i class="fa-solid fa-file-pdf" aria-hidden="true"></i>
        Download Full Proposal (PDF)
      </a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
