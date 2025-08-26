<?php
require __DIR__ . '/db.php';
require __DIR__ . '/auth.php';
require_login();

$notice = '';

/* CREATE */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {
  $title          = trim($_POST['title'] ?? '');
  $issuer         = trim($_POST['issuer'] ?? '');
  $issued_at      = trim($_POST['issued_at'] ?? '');
  $credential_id  = trim($_POST['credential_id'] ?? '');
  $skills         = trim($_POST['skills'] ?? '');          // comma chips
  $credential_url = trim($_POST['credential_url'] ?? '');
  $download_url   = trim($_POST['download_url'] ?? '');
  $description    = trim($_POST['description'] ?? '');

  // optional single image
  $imgName = null;
  if (!empty($_FILES['image']['name'])) {
    if (is_uploaded_file($_FILES['image']['tmp_name'])) {
      $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
      if (in_array($ext, ['jpg','jpeg','png','webp','gif'])) {
        $safe  = preg_replace('/[^a-zA-Z0-9_\.-]/','_', $_FILES['image']['name']);
        $imgName = time().'_'.$safe;
        $dest = __DIR__ . '/assets/projects/' . $imgName; // reuse same folder
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
          $imgName = null;
          $notice = '⚠️ Image upload failed.';
        }
      } else {
        $notice = '⚠️ Invalid image type.';
      }
    }
  }

  if ($title !== '') {
    $sql = "INSERT INTO certificates
              (title, issuer, issued_at, credential_id, skills, credential_url, download_url, image, description)
            VALUES (?,?,?,?,?,?,?,?,?)";
    $st = $conn->prepare($sql);
    $st->bind_param('sssssssss',
      $title, $issuer, $issued_at, $credential_id, $skills, $credential_url, $download_url, $imgName, $description
    );
    $st->execute();
    $notice = $notice ?: '✅ Certificate added.';
  } else {
    $notice = $notice ?: '⚠️ Title is required.';
  }
}

/* DELETE */
if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];

  // remove image if any
  $q = $conn->prepare("SELECT image FROM certificates WHERE id=?");
  $q->bind_param('i',$id);
  $q->execute();
  if ($r = $q->get_result()->fetch_assoc()) {
    if (!empty($r['image'])) {
      $p = __DIR__ . '/assets/projects/' . $r['image'];
      if (is_file($p)) @unlink($p);
    }
  }

  $del = $conn->prepare("DELETE FROM certificates WHERE id=?");
  $del->bind_param('i',$id);
  $del->execute();
  header('Location: admin_certificates.php?msg=deleted');
  exit;
}

/* LIST */
$rows = $conn->query("SELECT id, title, issuer, issued_at, image FROM certificates ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin • Certifications</title>
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
      <div class="admin-title">Admin • Certifications</div>
      <div class="admin-nav">
        <a href="homepage.php">Home</a>
        <a href="certifications.php">Public Certifications</a>
        <a href="admin_projects.php">Projects Admin</a>
        <a href="admin_achievements.php">Achvmts Admin</a>
        <a href="logout.php">Logout</a>
      </div>
      </div>
    </div>

    <?php if(!empty($_GET['msg']) || !empty($notice)): ?>
      <div class="notice"><?= htmlspecialchars($_GET['msg'] ?? $notice) ?></div>
    <?php endif; ?>

    <div class="grid-2">
      <!-- Create -->
      <div class="card card-pad">
        <h3 style="margin:0 0 10px; font-size:18px;">Add Certificate</h3>
        <form class="form-grid" method="post" enctype="multipart/form-data">
          <input type="hidden" name="action" value="create">

          <input class="input" type="text" name="title" placeholder="Certificate title" required />
          <textarea class="textarea" name="description" rows="3" placeholder="Short description (optional)"></textarea>

          <div class="row-2">
            <input class="input" type="text" name="issuer" placeholder="Issuer (e.g., DeepLearning.AI)" />
            <input class="input" type="text" name="issued_at" placeholder="Issued (e.g., Jun 2025)" />
          </div>

          <div class="row-2">
            <input class="input" type="text" name="credential_id" placeholder="Credential ID (optional)" />
            <input class="input" type="text" name="skills" placeholder="Skills (comma-separated)" />
          </div>

          <div class="row-2">
            <input class="input" type="url"  name="credential_url" placeholder="Show credential URL (optional)" />
            <input class="input" type="url"  name="download_url"   placeholder="Download URL (optional)" />
          </div>

          <input class="file" type="file" name="image" accept=".jpg,.jpeg,.png,.webp,.gif" />
          <button class="btn" type="submit">Add Certificate</button>
          <div class="muted">Images are stored in <code>assets/projects/</code></div>
        </form>
      </div>

      <!-- List -->
      <div class="card card-pad">
        <h3 style="margin:0 0 10px; font-size:18px;">All Certificates</h3>
        <table class="table">
          <thead>
            <tr><th>Thumb</th><th>Title</th><th>Issuer</th><th>Issued</th><th>Actions</th></tr>
          </thead>
          <tbody>
            <?php if (!$rows): ?>
              <tr><td colspan="5" class="muted">No certificates yet.</td></tr>
            <?php else: foreach($rows as $r): ?>
              <tr>
                <td><?php if(!empty($r['image'])): ?><img class="thumb" src="<?='assets/projects/'.htmlspecialchars($r['image'])?>" alt=""><?php endif; ?></td>
                <td><?= htmlspecialchars($r['title']) ?></td>
                <td><?= htmlspecialchars($r['issuer'] ?? '') ?></td>
                <td><?= htmlspecialchars($r['issued_at'] ?? '') ?></td>
                <td>
                  <a href="<?='admin_certificate_edit.php?id='.$r['id']?>">Edit</a>
                  &nbsp;|&nbsp;
                  <a href="<?='admin_certificates.php?delete='.$r['id']?>" onclick="return confirm('Delete this certificate?')">Delete</a>
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
