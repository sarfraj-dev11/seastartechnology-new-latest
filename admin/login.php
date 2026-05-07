<?php
session_start();
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['password']) && password_verify($_POST['password'], ADMIN_PASSWORD_HASH)) {
        $_SESSION['mcc_admin'] = true;
        header('Location: dashboard.php');
        exit;
    }
    $login_error = 'Invalid password. Please try again.';
}
if (!empty($_SESSION['mcc_admin'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Admin Login — seastartechnology</title>
  <meta name="robots" content="noindex,nofollow">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="admin-login-wrap">
  <div class="admin-login-card">
    <div class="admin-login-logo">
      <div class="icon"><i class="fas fa-laptop-medical"></i></div>
      <div>
        <div class="name">seastartechnology</div>
        <div class="sub">Admin Panel</div>
      </div>
    </div>
    <h2>Sign In</h2>
    <p class="sub-text">Enter your admin password to continue.</p>
    <?php if (!empty($login_error)): ?>
      <div class="msg-error"><i class="fas fa-exclamation-circle"></i> <?php echo $login_error; ?></div>
    <?php endif; ?>
    <form method="POST" action="login.php">
      <div class="login-form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="••••••••••" required autofocus>
      </div>
      <button type="submit" class="btn-login">
        <i class="fas fa-arrow-right-to-bracket"></i> Sign In
      </button>
    </form>
    <p class="back-link"><a href="../index.php">← Back to site</a></p>
  </div>
</div>
</body>
</html>
