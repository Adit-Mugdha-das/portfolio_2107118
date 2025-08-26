<?php
require __DIR__ . '/auth.php';
require_login();
require __DIR__ . '/db.php';

$notice = '';

// Delete
if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  $d = $conn->prepare("DELETE FROM messages WHERE id=?");
  $d->bind_param('i', $id);
  $d->execute();
  header('Location: admin_messages.php?msg=deleted');
  exit;
}

// Mark as read
if (isset($_GET['read'])) {
  $id = (int)$_GET['read'];
  $r = $conn->prepare("UPDATE messages SET is_read=1 WHERE id=?");
  $r->bind_param('i', $id);
  $r->execute();
  header('Location: admin_messages.php?msg=marked');
  exit;
}

$rows = $conn->query("SELECT * FROM messages ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin • Messages</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    body.is-dark { background:#0b1020; color:#e5e7eb; }
    .wrap{ max-width:1100px; margin:32px auto; padding:0 16px }
    .head{ display:flex; justify-content:space-between; align-items:center; margin-bottom:16px }
    .card{ background:#0f152b; border:1px solid #1f2a46; border-radius:16px; box-shadow:0 8px 24px rgba(0,0,0,.25); padding:16px }
    .table{ width:100%; border-collapse:collapse }
    .table th,.table td{ border-bottom:1px solid #1f2937; padding:10px; vertical-align:top }
    .badge{ font-size:12px; padding:2px 8px; border-radius:999px; background:#1e293b; }
    .admin-nav a{ color:#cbd5e1; margin-left:12px; text-decoration:none; }
  </style>
</head>
<body class="is-dark">
  <div class="wrap">
    <div class="head">
      <h2 style="margin:0">Admin • Messages</h2>
      <div class="admin-nav">
        <a href="homepage.php">Home</a>
        <a href="projects.php">Public Projects</a>
        <a href="admin_projects.php">Projects Admin</a>
        <a href="admin_certificates.php">Certs Admin</a>
        <a href="admin_achievements.php">Achvmts Admin</a>
        <a href="logout.php">Logout</a>
      </div>
    </div>

    <?php if (!empty($_GET['msg'])): ?>
      <div class="card" style="margin-bottom:12px; color:#93c5fd"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <div class="card">
      <table class="table">
        <thead>
          <tr>
            <th>From</th>
            <th>Message</th>
            <th>When</th>
            <th>Status</th>
            <th style="width:140px">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!$rows): ?>
            <tr><td colspan="5" style="color:#94a3b8">No messages yet.</td></tr>
          <?php else: foreach($rows as $m): ?>
            <tr>
              <td>
                <strong><?= htmlspecialchars($m['name']) ?></strong><br>
                <a href="mailto:<?= htmlspecialchars($m['email']) ?>"><?= htmlspecialchars($m['email']) ?></a><br>
                <span style="color:#94a3b8">IP: <?= htmlspecialchars($m['ip_addr'] ?? '') ?></span>
              </td>
              <td><?= nl2br(htmlspecialchars($m['message'])) ?></td>
              <td><?= htmlspecialchars(date('Y-m-d H:i', strtotime($m['created_at']))) ?></td>
              <td><?= $m['is_read'] ? '<span class="badge">Read</span>' : '<span class="badge" style="background:#0ea5e9;color:#081019">New</span>' ?></td>
              <td>
                <?php if (!$m['is_read']): ?>
                  <a href="admin_messages.php?read=<?= $m['id'] ?>">Mark read</a> |
                <?php endif; ?>
                <a href="admin_messages.php?delete=<?= $m['id'] ?>" onclick="return confirm('Delete this message?')">Delete</a>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
