<?php
require __DIR__ . '/auth.php';
require_login();
require __DIR__ . '/db.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM achievements WHERE id=?");
$stmt->bind_param('i', $id);
$stmt->execute();
$rec = $stmt->get_result()->fetch_assoc();
if (!$rec) { exit('Achievement not found.'); }

$notice = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title      = trim($_POST['title'] ?? '');
  $org        = trim($_POST['org'] ?? '');
  $issued     = trim($_POST['issued'] ?? '');
  $blurb      = trim($_POST['blurb'] ?? '');
  $verify_url = trim($_POST['verify_url'] ?? '');
  $download   = trim($_POST['download_url'] ?? '');

  // default keep old image
  $imgName = $rec['image'] ?? '';

  if (!empty($_FILES['image']['name'])) {
    $orig = $_FILES['image']['name'];
    $ext  = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
    if (in_array($ext, ['jpg','jpeg','png','webp','gif'])) {
      if (!is_dir(__DIR__.'/assets/awards')) @mkdir(__DIR__.'/assets/awards', 0775, true);
      $safe  = preg_replace('/[^a-zA-Z0-9_\.-]/','_', $orig);
      $new   = time().'_'.$safe;
      $dest  = __DIR__ . '/assets/awards/' . $new;
      if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
        // remove previous
        if (!empty($imgName)) {
          $old = __DIR__ . '/assets/awards/' . $imgName;
          if (is_file($old)) @unlink($old);
        }
        $imgName = $new;
      } else {
        $notice = '⚠️ Upload failed, keeping previous image.';
      }
    } else {
      $notice = '⚠️ Invalid image type, keeping previous image.';
    }
  }

  if ($title !== '') {
    $u = $conn->prepare("UPDATE achievements
                         SET title=?, org=?, issued=?, blurb=?, verify_url=?, download_url=?, image=?
                         WHERE id=?");
    $u->bind_param('sssssssi', $title, $org, $issued, $blurb, $verify_url, $download, $imgName, $id);
    $u->execute();
    header('Location: admin_achievements.php?msg=updated');
    exit;
  } else {
    $notice = $notice ?: '⚠️ Title is required.';
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Edit Achievement</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <style>
    body.is-dark { background:#0b1020; color:#e5e7eb; }
    .wrap { max-width:900px; margin:32px auto; padding:0 16px; }
    .card { background:#0f152b; border:1px solid #1f2a46; border-radius:16px; box-shadow:0 8px 24px rgba(0,0,0,.25); padding:16px; }
    .top { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; }
    a { color:#93c5fd; text-decoration:none; }
    .input, .textarea, .file, .btn { width:100%; }
    .input, .textarea, .file { padding:12px; background:#0b1120; color:#e5e7eb; border:1px solid #1f2a46; border-radius:12px; margin-bottom:10px; }
    .row-2 { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
    @media (max-width:700px){ .row-2 { grid-template-columns:1fr } }
    .btn { padding:12px 14px; border:0; border-radius:12px; background:#0ea5e9; color:#081019; font-weight:700; cursor:pointer; }
    .notice { margin-bottom:10px; padding:10px 12px; border-radius:10px; background:#0f172a; border:1px solid #1f2937; }
    .thumb { width:160px; height:110px; object-fit:cover; border-radius:10px; border:1px solid #1f2a46; background:#0b1120; }
  </style>
</head>
<body class="is-dark">
  <div class="wrap">
    <div class="top">
      <h2 style="margin:0;">Edit Achievement</h2>
      <a href="admin_achievements.php">← Back to Admin</a>
    </div>

    <?php if($notice): ?><div class="notice"><?=htmlspecialchars($notice)?></div><?php endif; ?>

    <div class="card">
      <form method="post" enctype="multipart/form-data">
        <label>Title</label>
        <input class="input" type="text" name="title" value="<?=htmlspecialchars($rec['title'])?>" required>

        <label>Description</label>
        <textarea class="textarea" name="blurb" rows="5"><?=htmlspecialchars($rec['blurb'])?></textarea>

        <div class="row-2">
          <div>
            <label>Issuer / Organization</label>
            <input class="input" type="text" name="org" value="<?=htmlspecialchars($rec['org'] ?? '')?>">
          </div>
          <div>
            <label>Issued (e.g., Dec 2024)</label>
            <input class="input" type="text" name="issued" value="<?=htmlspecialchars($rec['issued'] ?? '')?>">
          </div>
        </div>

        <div class="row-2">
          <div>
            <label>View / Show credential URL</label>
            <input class="input" type="url" name="verify_url" value="<?=htmlspecialchars($rec['verify_url'] ?? '')?>">
          </div>
          <div>
            <label>Download URL (PDF or image)</label>
            <input class="input" type="url" name="download_url" value="<?=htmlspecialchars($rec['download_url'] ?? '')?>">
          </div>
        </div>

        <label>Current image</label><br>
        <?php if (!empty($rec['image'])): ?>
          <img class="thumb" src="<?='assets/awards/'.htmlspecialchars($rec['image'])?>" alt=""><br><br>
        <?php else: ?>
          <div class="muted">— none —</div>
        <?php endif; ?>

        <label>Replace image (optional)</label>
        <input class="file" type="file" name="image" accept=".jpg,.jpeg,.png,.webp,.gif">

        <button class="btn" type="submit">Save Changes</button>
      </form>
    </div>
  </div>
</body>
</html>
