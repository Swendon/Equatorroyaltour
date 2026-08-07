<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'Contact Us';
$pageDescription = 'Contact Equator Royal Tour CBO for partnerships, development enquiries, and corporate sponsorship opportunities across Baringo County, Kenya.';
$active = 'contact';
$basePath = '';
$showFloatingWhatsapp = true;

$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;

$success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = 'Invalid request. Please try again.';
    }
    if (!empty($_POST['honeypot'])) {
        $success = true;
        exit;
    }

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
            @mail(NOTIFY_EMAIL, 'New Contact Message: ' . $subject, "From: $name <$email>\n\n$message");
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
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
        <div style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden;" aria-hidden="true">
          <label for="website">Website</label>
          <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
        </div>
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
        <?php
        $centres = [];
        $result = mysqli_query($conn, 'SELECT name FROM trading_centres ORDER BY name');
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $centres[] = $row['name'];
            }
        }
        echo '<p>' . htmlspecialchars(implode(', ', $centres)) . '.</p>';
        ?>
      </div>
      <div class="card whatsapp-card" style="margin-bottom:20px;">
        <span class="contact-kicker">Instant support</span>
        <h3><i class="fa-brands fa-whatsapp" aria-hidden="true"></i> WhatsApp</h3>
        <p>Tap the button below to start a guided WhatsApp chat for faster communication and partnership enquiries.</p>
        <p><strong>WhatsApp:</strong> <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> <a href="https://wa.me/254724696687" target="_blank" rel="noreferrer">+254 724 696 687</a></p>
        <a class="btn btn-primary whatsapp-contact-btn" href="https://wa.me/254724696687?text=Hello%20Equator%20Royal%20Tour%2C%20I%20would%20like%20more%20information." target="_blank" rel="noreferrer">
          <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
          Contact us on WhatsApp
        </a>
      </div>
      <?php include __DIR__ . '/includes/banking-card.php'; ?>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
