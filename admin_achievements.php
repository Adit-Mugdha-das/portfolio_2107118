<?php
require __DIR__ . '/auth.php';
require_login();
require __DIR__ . '/db.php';

$notice = '';

// CREATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {
  $title      = trim($_POST['title'] ?? '');
  $org        = trim($_POST['org'] ?? '');
  $issued     = trim($_POST['issued'] ?? '');
  $blurb      = trim($_POST['blurb'] ?? '');
  $verify_url = trim($_POST['verify_url'] ?? '');
  $download   = trim($_POST['download_url'] ?? '');

  // image upload (single)
  $imgName = '';
  if (!empty($_FILES['image']['name'])) {
    $orig = $_FILES['image']['name'];
    $ext  = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
    if (in_array($ext, ['jpg','jpeg','png','webp','gif'])) {
      if (!is_dir(__DIR__.'/assets/awards')) @mkdir(__DIR__.'/assets/awards', 0775, true);
      $safe  = preg_replace('/[^a-zA-Z0-9_\.-]/','_', $orig);
      $imgName = time().'_'.$safe;
      $dest  = __DIR__ . '/assets/awards/' . $imgName;
      move_uploaded_file($_FILES['image']['tmp_name'], $dest);
    }
  }

  if ($title !== '') {
    $sql = "INSERT INTO achievements (title, org, issued, blurb, verify_url, download_url, image)
            VALUES (?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssss', $title, $org, $issued, $blurb, $verify_url, $download, $imgName);
    $stmt->execute();
    $notice = '✅ Achievement added.';
  } else {
    $notice = '⚠️ Title is required.';
  }
}

// DELETE
if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];

  // delete image file
  $s = $conn->prepare("SELECT image FROM achievements WHERE id=?");
  $s->bind_param('i', $id);
  $s->execute();
  if ($row = $s->get_result()->fetch_assoc()) {
    if (!empty($row['image'])) {
      $p = __DIR__ . '/assets/awards/' . $row['image'];
      if (is_file($p)) @unlink($p);
    }
  }

  $d = $conn->prepare("DELETE FROM achievements WHERE id=?");
  $d->bind_param('i', $id);
  $d->execute();
  header('Location: admin_achievements.php?msg=deleted');
  exit;
}

// LIST
$rows = $conn->query("SELECT id, title, org, issued, image FROM achievements ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin • Achievements</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <style>
    body.is-dark { background:#0b1020; color:#e5e7eb; }
    .admin-wrap { max-width:1100px; margin:32px auto; padding:0 16px; }
    .admin-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:18px; }
    .admin-title { font-size:28px; font-weight:800; letter-spacing:.3px; }
    .admin-nav a { color:#cbd5e1; margin-left:12px; text-decoration:none; }
    .notice { margin:10px 0 18px; padding:10px 12px; border-radius:10px; background:#0f172a; border:1px solid #1f2937; }
    .card { background:#0f152b; border:1px solid #1f2a46; border-radius:16px; box-shadow:0 8px 24px rgba(0,0,0,.25); }
    .card-pad { padding:16px; }
    .grid-2 { display:grid; grid-template-columns: 1fr 1.2fr; gap:16px; }
    .form-grid { display:grid; gap:10px; }
    .input, .textarea, .file, .btn { width:100%; }
    .input, .textarea, .file { padding:12px; background:#0b1120; color:#e5e7eb; border:1px solid #1f2a46; border-radius:12px; }
    .row-2 { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
    .btn { padding:12px 14px; border:0; border-radius:12px; background:#0ea5e9; color:#081019; font-weight:700; cursor:pointer; }
    .table { width:100%; border-collapse:collapse; }
    .table th, .table td { border-bottom:1px solid #1e293b; padding:12px; }
    .thumb { width:92px; height:60px; object-fit:cover; border-radius:10px; background:#0b1120; border:1px solid #1f2a46; margin-right:6px; }
    .muted { color:#94a3b8; font-size:13px; }
    @media (max-width: 900px){ .grid-2{ grid-template-columns:1fr } }
  </style>
</head>
<body class="is-dark">
  <div class="admin-wrap">
    <div class="admin-header">
      <div class="admin-title">Admin • Achievements</div>
      <div class="admin-nav">
        <a href="homepage.php">Home</a>
        <a href="achievements.php">Public Achievements</a>
        <a href="admin_projects.php">Projects Admin</a>
        <a href="admin_certificates.php">Certs Admin</a>
        <a href="logout.php">Logout</a>
      </div>
    </div>

    <?php if(!empty($_GET['msg']) || !empty($notice)): ?>
      <div class="notice"><?= htmlspecialchars($_GET['msg'] ?? $notice) ?></div>
    <?php endif; ?>

    <div class="grid-2">
      <!-- Create -->
      <div class="card card-pad">
        <h3 style="margin:0 0 10px; font-size:18px;">Add Achievement</h3>
        <form class="form-grid" method="post" enctype="multipart/form-data">
          <input type="hidden" name="action" value="create">
          <input class="input" type="text" name="title" placeholder="Title (e.g., Best Project Award)" required />
          <textarea class="textarea" name="blurb" rows="4" placeholder='Short description (supports basic HTML)'></textarea>

          <div class="row-2">
            <input class="input" type="text" name="org" placeholder="Issuer / Organization (e.g., KUET)" />
            <input class="input" type="text" name="issued" placeholder="Issued (e.g., Dec 2024)" />
          </div>

          <div class="row-2">
            <input class="input" type="url" name="verify_url" placeholder="View / Show credential URL (optional)" />
            <input class="input" type="url" name="download_url" placeholder="Download URL (optional, PDF or image)" />
          </div>

          <input class="file" type="file" name="image" accept=".jpg,.jpeg,.png,.webp,.gif" />
          <button class="btn" type="submit">Add Achievement</button>
          <div class="muted">Images are stored in <code>assets/awards/</code></div>
        </form>
      </div>

      <!-- List -->
      <div class="card card-pad">
        <h3 style="margin:0 0 10px; font-size:18px;">All Achievements</h3>
        <table class="table">
          <thead>
            <tr><th>Thumb</th><th>Title</th><th>Issuer</th><th>Issued</th><th>Actions</th></tr>
          </thead>
          <tbody>
          <?php if (!$rows): ?>
            <tr><td colspan="5" class="muted">No achievements yet.</td></tr>
          <?php else: foreach($rows as $r): ?>
            <tr>
              <td>
                <?php if (!empty($r['image'])): ?>
                  <img class="thumb" src="<?='assets/awards/'.htmlspecialchars($r['image'])?>" alt="">
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($r['title']) ?></td>
              <td><?= htmlspecialchars($r['org'] ?? '') ?></td>
              <td><?= htmlspecialchars($r['issued'] ?? '') ?></td>
              <td>
                <a href="<?='admin_achievement_edit.php?id='.$r['id']?>">Edit</a>
                &nbsp;|&nbsp;
                <a href="<?='admin_achievements.php?delete='.$r['id']?>" onclick="return confirm('Delete this achievement?')">Delete</a>
              </td>
            </tr>
          <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
