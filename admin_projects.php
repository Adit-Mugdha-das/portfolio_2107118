<?php
require 'db.php';

/* ---------- Session auth guard ---------- */
require __DIR__ . '/auth.php';   // starts session + helpers
require_login();                 // redirects to login.php if not logged in

$notice = '';

/* ---------- CREATE: add new project ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {
  $title     = trim($_POST['title'] ?? '');
  $desc      = trim($_POST['description'] ?? '');
  $link      = trim($_POST['link'] ?? '');
  $duration  = trim($_POST['duration'] ?? '');
  $tech      = trim($_POST['tech'] ?? '');          // comma-separated chips
  $github    = trim($_POST['github'] ?? '');
  $readme    = trim($_POST['readme'] ?? '');
  $download  = trim($_POST['download'] ?? '');
  $award     = trim($_POST['award'] ?? '');

  $filenames = [];

  // Handle multiple images (optional)
  if (!empty($_FILES['images']['name'][0])) {
    foreach ($_FILES['images']['name'] as $i => $orig) {
      if (!is_uploaded_file($_FILES['images']['tmp_name'][$i])) continue;
      $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
      if (!in_array($ext, ['jpg','jpeg','png','webp','gif'])) continue;

      $safe  = preg_replace('/[^a-zA-Z0-9_\.-]/','_', $orig);
      $fname = time().'_'.$i.'_'.$safe;
      $dest  = __DIR__.'/assets/projects/'.$fname;
      if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $dest)) {
        $filenames[] = $fname;
      }
    }
  }

  $allImages = implode(',', $filenames); // can be empty

  if ($title !== '') {
    $sql = "INSERT INTO projects
              (title, description, image, link, duration, tech, github, readme, download, award)
            VALUES (?,?,?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
      'ssssssssss',
      $title, $desc, $allImages, $link, $duration, $tech, $github, $readme, $download, $award
    );
    $stmt->execute();
    $notice = '✅ Project added.';
  } else {
    $notice = '⚠️ Title is required.';
  }
}

/* ---------- DELETE ---------- */
if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];

  // remove image files if exist
  $imgStmt = $conn->prepare("SELECT image FROM projects WHERE id=?");
  $imgStmt->bind_param('i', $id);
  $imgStmt->execute();
  if ($row = $imgStmt->get_result()->fetch_assoc()) {
    if (!empty($row['image'])) {
      foreach (explode(',', $row['image']) as $img) {
        $img = trim($img);
        if (!$img) continue;
        $path = __DIR__ . '/assets/projects/' . $img;
        if (is_file($path)) @unlink($path);
      }
    }
  }

  $del = $conn->prepare("DELETE FROM projects WHERE id=?");
  $del->bind_param('i', $id);
  $del->execute();
  header('Location: admin_projects.php?msg=deleted'); // <-- no key
  exit;
}

/* ---------- LIST ---------- */
$rows = $conn->query("SELECT id, title, image, link FROM projects ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin • Projects</title>
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
      <div class="admin-title">Admin • Projects</div>
      <div class="admin-nav">
        <a href="homepage.php">Home</a>
        <a href="projects.php">Public Projects</a>
        <a href="admin_certificates.php">Certificates Admin</a>
        <a href="logout.php">Logout</a>
      </div>
    </div>

    <?php if(!empty($_GET['msg']) || !empty($notice)): ?>
      <div class="notice"><?= htmlspecialchars($_GET['msg'] ?? $notice) ?></div>
    <?php endif; ?>

    <div class="grid-2">
      <!-- Create card -->
      <div class="card card-pad">
        <h3 style="margin:0 0 10px; font-size:18px;">Add Project</h3>
        <form class="form-grid" method="post" enctype="multipart/form-data">
          <input type="hidden" name="action" value="create">

          <input class="input" type="text" name="title" placeholder="Project title" required />
          <textarea class="textarea" name="description" rows="4" placeholder="Short description (optional)"></textarea>

          <div class="row-2">
            <input class="input" type="text" name="duration" placeholder="Duration (e.g., Jul–Dec 2024)" />
            <input class="input" type="text" name="tech" placeholder="Tech chips (e.g., JavaFX, MySQL, CSS)" />
          </div>

          <div class="row-2">
            <input class="input" type="url" name="github" placeholder="GitHub URL (optional)" />
            <input class="input" type="url" name="readme" placeholder="Read More / README URL (optional)" />
          </div>

          <div class="row-2">
            <input class="input" type="url" name="download" placeholder="Download URL (optional)" />
            <input class="input" type="url" name="link" placeholder="Open / Demo URL (optional)" />
          </div>

          <input class="input" type="text" name="award" placeholder="Award text (e.g., Best Project Award In KUET)" />

          <input class="file" type="file" name="images[]" accept=".jpg,.jpeg,.png,.webp,.gif" multiple />

          <button class="btn" type="submit">Add Project</button>
          <div class="muted">Images are stored in <code>assets/projects/</code></div>
        </form>
      </div>

      <!-- List card -->
      <div class="card card-pad">
        <h3 style="margin:0 0 10px; font-size:18px;">All Projects</h3>
        <table class="table">
          <thead>
            <tr><th>Thumb</th><th>Title</th><th>Link</th><th>Actions</th></tr>
          </thead>
          <tbody>
          <?php if (!$rows): ?>
            <tr><td colspan="4" class="muted">No projects yet.</td></tr>
          <?php else: foreach($rows as $r): ?>
            <tr>
              <td>
                <?php
                  $first = '';
                  if (!empty($r['image'])) {
                    $imgs = explode(',', $r['image']);
                    $first = trim($imgs[0] ?? '');
                  }
                  if ($first) {
                    echo "<img class='thumb' src='assets/projects/".htmlspecialchars($first, ENT_QUOTES)."' alt=''>";
                  }
                ?>
              </td>
              <td><?= htmlspecialchars($r['title']) ?></td>
              <td><?php if(!empty($r['link'])): ?><a href="<?= htmlspecialchars($r['link'])?>" target="_blank" rel="noopener">Open</a><?php endif; ?></td>
              <td>
                <a href="<?='admin_project_edit.php?id='.$r['id']?>">Edit</a>
                &nbsp;|&nbsp;
                <a href="<?='admin_projects.php?delete='.$r['id']?>" onclick="return confirm('Delete this project?')">Delete</a>
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
