<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'Trader Registration';
$pageDescription = 'Join the Mumberes Women Traders Cooperative. Register as a trader to access safe trading bays across nine centres in Baringo County, Kenya.';
$active = 'register';
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

    $full_name = trim($_POST['full_name'] ?? '');
    $id_number = trim($_POST['id_number'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $trading_centre = trim($_POST['trading_centre'] ?? '');
    $produce_type = trim($_POST['produce_type'] ?? '');
    $gender = trim($_POST['gender'] ?? 'Female');

    if ($full_name === '') { $errors[] = 'Full name is required.'; }
    if ($id_number === '') { $errors[] = 'National ID number is required.'; }
    if ($phone === '') { $errors[] = 'Phone number is required.'; }
    if ($trading_centre === '') { $errors[] = 'Please select a trading centre.'; }
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = 'Please enter a valid email address.'; }

    if (empty($errors)) {
        $id_number_encrypted = encrypt_field($id_number);

        $check = mysqli_prepare($conn, 'SELECT id FROM registrations WHERE id_number = ?');
        mysqli_stmt_bind_param($check, 's', $id_number_encrypted);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);
        if (mysqli_stmt_num_rows($check) > 0) {
            $errors[] = 'This National ID number has already been registered. Please contact the office if you believe this is an error.';
        }
        mysqli_stmt_close($check);

        if (empty($errors)) {
            $stmt = mysqli_prepare($conn, 'INSERT INTO registrations (full_name, id_number, phone, email, trading_centre, produce_type, gender) VALUES (?, ?, ?, ?, ?, ?, ?)');
            mysqli_stmt_bind_param($stmt, 'sssssss', $full_name, $id_number_encrypted, $phone, $email, $trading_centre, $produce_type, $gender);
            if (mysqli_stmt_execute($stmt)) {
                $success = true;
                @mail('info@equatorroyaltour.com', 'New Trader Registration', "Name: $full_name\nPhone: $phone\nCentre: $trading_centre\nProduce: $produce_type");
            } else {
                $errors[] = 'Something went wrong while saving your registration. Please try again.';
            }
            mysqli_stmt_close($stmt);
        }
    }
}

$centres = [];
$result = mysqli_query($conn, 'SELECT name FROM trading_centres ORDER BY name');
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $centres[] = $row['name'];
    }
}

include __DIR__ . '/includes/header.php';
?>

<section class="page-banner">
  <div class="container">
    <h1>Trader Registration</h1>
    <p>Join the Mumberes Women Traders Cooperative and register for a safe trading bay.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="form-card">
      <?php if ($success): ?>
        <div class="alert alert-success">Thank you! Your registration has been received. Our team will contact you regarding next steps.</div>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
          <?php foreach ($errors as $err) { echo htmlspecialchars($err) . '<br>'; } ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="register.php">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
        <div style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden;" aria-hidden="true">
          <label for="website">Website</label>
          <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>" required>
          </div>
          <div class="form-group">
            <label for="id_number">National ID Number</label>
            <input type="text" id="id_number" name="id_number" value="<?php echo htmlspecialchars($_POST['id_number'] ?? ''); ?>" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" required>
          </div>
          <div class="form-group">
            <label for="email">Email Address (optional)</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="trading_centre">Trading Centre</label>
            <select id="trading_centre" name="trading_centre" required>
              <option value="">Select a trading centre</option>
              <?php foreach ($centres as $centre): ?>
                <option value="<?php echo htmlspecialchars($centre); ?>" <?php echo (($_POST['trading_centre'] ?? '') === $centre) ? 'selected' : ''; ?>><?php echo htmlspecialchars($centre); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="gender">Gender</label>
            <select id="gender" name="gender">
              <option value="Female" <?php echo (($_POST['gender'] ?? 'Female') === 'Female') ? 'selected' : ''; ?>>Female</option>
              <option value="Male" <?php echo (($_POST['gender'] ?? '') === 'Male') ? 'selected' : ''; ?>>Male</option>
              <option value="Other" <?php echo (($_POST['gender'] ?? '') === 'Other') ? 'selected' : ''; ?>>Other</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="produce_type">Type of Produce Traded</label>
          <input type="text" id="produce_type" name="produce_type" placeholder="e.g. Potatoes, tomatoes, onions" value="<?php echo htmlspecialchars($_POST['produce_type'] ?? ''); ?>">
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;">Submit Registration</button>
      </form>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
