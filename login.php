<?php
require_once __DIR__ . '/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$error = '';
$redirect = $_GET['redirect'] ?? 'admin_projects.php';

// === Handle "Forget saved username" action early ===
if (!empty($_POST['forget_user'])) {
  setcookie('remember_user', '', time() - 3600, '/', '', isset($_SERVER['HTTPS']), true);
  header('Location: login.php');
  exit;
}

// Prefill username from cookie
$savedUser = $_COOKIE['remember_user'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['forget_user'])) {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';
  $remember = !empty($_POST['remember']);

  if ($username && $password) {
    $stmt = $conn->prepare('SELECT id, password_hash FROM admins WHERE username=?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if ($res && password_verify($password, $res['password_hash'])) {
      $_SESSION['admin_id'] = (int)$res['id'];

      if ($remember) {
        setcookie(
          'remember_user',
          $username,
          time() + 86400, // 1 day
          '/',
          '',
          isset($_SERVER['HTTPS']),
          true
        );
      } else {
        setcookie('remember_user', '', time() - 3600, '/', '', isset($_SERVER['HTTPS']), true);
      }

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
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Admin Login</title>
  <style>
    body.is-dark {
      background:#0b1020;
      color:#e5e7eb;
      margin:0;
      min-height:100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      font-family: system-ui, Arial, sans-serif;
    }
    .wrap {
      max-width:520px;   /* bigger width */
      width:100%;
      padding:0 20px;
    }
    .card {
      background:#0f152b;
      border:1px solid #1f2a46;
      border-radius:18px;
      box-shadow:0 10px 30px rgba(0,0,0,.35);
      padding:28px;       /* bigger padding */
    }
    h2 {
      margin:0 0 20px;
      text-align:center;
      font-size:28px;
    }
    .input {
      width:100%;
      padding:10px;       /* bigger input */
      font-size:16px;
      background:#0b1120;
      color:#e5e7eb;
      border:1px solid #1f2a46;
      border-radius:12px;
      margin-bottom:14px;
    }
    .btn {
      width:100%;
      padding:14px 16px;   /* bigger button */
      font-size:16px;
      border:0;
      border-radius:12px;
      background:#0ea5e9;
      color:#081019;
      font-weight:700;
      cursor:pointer;
    }
    .btn-danger {
      background:#7f1d1d;
      color:#fff;
    }
    .error {
      margin-bottom:14px;
      padding:12px;
      border-radius:10px;
      background:#3b1a1a;
      border:1px solid #7f1d1d;
      color:#fecaca;
      text-align:center;
      font-size:15px;
    }
    a {
      color:#93c5fd;
      text-decoration:none;
    }
    label {
      display:flex;
      align-items:center;
      gap:8px;
      margin:10px 0;
      font-size:15px;
      color:#cbd5e1;
    }
  </style>
</head>
<body class="is-dark">
  <div class="wrap">
    <h2>Admin Login</h2>
    <?php if($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <div class="card">
      <form method="post">
        <input class="input" type="text" name="username" placeholder="Username"
               value="<?= htmlspecialchars($savedUser) ?>" required>
        <input class="input" type="password" name="password" placeholder="Password" required>
        <label><input type="checkbox" name="remember" <?= $savedUser ? 'checked' : '' ?>> Remember me</label>
        <button class="btn" type="submit">Sign in</button>
      </form>

      <?php if (!empty($_COOKIE['remember_user'])): ?>
        <form method="post" style="margin-top:10px;">
          <input type="hidden" name="forget_user" value="1">
          <button type="submit" class="btn btn-danger">Forget saved username</button>
        </form>
      <?php endif; ?>

      <div style="margin-top:12px; text-align:center;">
        <a href="homepage.php">&larr; Back to site</a>
      </div>
    </div>
  </div>
</body>
</html>
