<?php
require_once __DIR__ . '/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$error = '';
$redirect = $_GET['redirect'] ?? 'admin_projects.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';

  if ($username && $password) {
    $stmt = $conn->prepare('SELECT id, password_hash FROM admins WHERE username=?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if ($res && password_verify($password, $res['password_hash'])) {
      $_SESSION['admin_id'] = (int)$res['id'];
      header('Location: ' . $redirect);
      exit;
    } else {
      $error = 'Invalid username or password.';
    }
  } else {
    $error = 'Please enter username and password.';
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Login</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <style>
    body.is-dark{background:#0b1020;color:#e5e7eb}
    .wrap{max-width:420px;margin:46px auto;padding:0 16px}
    .card{background:#0f152b;border:1px solid #1f2a46;border-radius:16px;box-shadow:0 8px 24px rgba(0,0,0,.25);padding:18px}
    .input{width:100%;padding:12px;background:#0b1120;color:#e5e7eb;border:1px solid #1f2a46;border-radius:12px;margin-bottom:10px}
    .btn{width:100%;padding:12px 14px;border:0;border-radius:12px;background:#0ea5e9;color:#081019;font-weight:700;cursor:pointer}
    .error{margin-bottom:12px;padding:10px;border-radius:10px;background:#3b1a1a;border:1px solid #7f1d1d;color:#fecaca}
    a{color:#93c5fd;text-decoration:none}
  </style>
</head>
<body class="is-dark">
  <div class="wrap">
    <h2 style="margin:0 0 12px">Admin Login</h2>
    <?php if($error): ?><div class="error"><?=htmlspecialchars($error)?></div><?php endif; ?>
    <div class="card">
      <form method="post">
        <input class="input" type="text" name="username" placeholder="Username" required>
        <input class="input" type="password" name="password" placeholder="Password" required>
        <button class="btn" type="submit">Sign in</button>
      </form>
      <div style="margin-top:10px"><a href="homepage.php">&larr; Back to site</a></div>
    </div>
  </div>
</body>
</html>
