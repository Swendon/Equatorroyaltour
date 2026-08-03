<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'Contact Us';
$active = 'contact';
$basePath = '';
$showFloatingWhatsapp = true;

$success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {s
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '') { $errors[] = 'Name is required.'; }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = 'A valid email is required.'; }
    if ($message === '') { $errors[] = 'Message cannot be empty.'; }

    if (empty($errors)) {
        $stmt = mysqli_prepare($conn, 'INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)');
        mysqli_stmt_bind_param($stmt, 'ssss', $name, $email, $subject, $message);
        if (mysqli_stmt_execute($stmt)) {
            $success = true;
        } else {
            $errors[] = 'Something went wrong while sending your message. Please try again.';
        }
        mysqli_stmt_close($stmt);
    }
}

include __DIR__ . '/includes/header.php';
?>

<section class="page-banner">
  <div class="container">
    <h1>Contact &amp; Partnerships</h1>
    <p>We welcome development partners, government institutions, corporate organizations and philanthropic foundations.</p>
  </div>
</section>

<section class="section">
  <div class="container two-col">
    <div class="form-card" style="margin:0;">
      <?php if ($success): ?>
        <div class="alert alert-success">Thank you for reaching out. We will respond as soon as possible.</div>
      <?php endif; ?>
      <?php if (!empty($errors)): ?>
        <div class="alert alert-error"><?php foreach ($errors as $err) { echo htmlspecialchars($err) . '<br>'; } ?></div>
      <?php endif; ?>
      <form method="POST" action="contact.php">
        <div class="form-group">
          <label for="name">Your Name</label>
          <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
          <label for="subject">Subject</label>
          <input type="text" id="subject" name="subject" value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>">
        </div>
        <div class="form-group">
          <label for="message">Message</label>
          <textarea id="message" name="message" rows="6" required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;">Send Message</button>
      </form>
    </div>
    <div>
      <div class="card" style="margin-bottom:20px;">
        <h3>Registration Centres</h3>
        <p>Nakuru Railway, Makutano, Mlango Moja, Mlango Tatu, Mlango Nne, Equator, Hill Tea, Boito, Timboroa.</p>
      </div>
      <div class="card ai-whatsapp-card" style="margin-bottom:20px;">
        <span class="contact-kicker">Instant support</span>
        <h3><i class="fa-brands fa-whatsapp" aria-hidden="true"></i> AI WhatsApp</h3>
        <p>Tap the button below to start a guided WhatsApp chat for faster communication and partnership enquiries.</p>
        <p><strong>WhatsApp:</strong> <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> <a href="https://wa.me/254724696687" target="_blank" rel="noreferrer">+254 724 696 687</a></p>
        <a class="btn btn-primary whatsapp-contact-btn" href="https://wa.me/254724696687?text=Hello%20Equator%20Royal%20Tour%2C%20I%20would%20like%20more%20information." target="_blank" rel="noreferrer">
          <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
          Contact AI WhatsApp
        </a>
      </div>
      <div class="card">
        <h3>Banking Details</h3>
        <p><strong>Account Name:</strong> Equator Royal Tour</p>
        <p><strong>Banks:</strong> Equity Bank &amp; KCB, Eldama Ravine Branch</p>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
