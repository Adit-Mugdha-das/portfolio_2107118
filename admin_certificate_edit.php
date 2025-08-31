<?php
require __DIR__ . '/db.php';
require __DIR__ . '/auth.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$st = $conn->prepare("SELECT * FROM certificates WHERE id=?");
$st->bind_param('i',$id);
$st->execute();
$cert = $st->get_result()->fetch_assoc();
if (!$cert) { exit('Certificate not found.'); }

$notice = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title          = trim($_POST['title'] ?? '');
  $issuer         = trim($_POST['issuer'] ?? '');
  $issued_at      = trim($_POST['issued_at'] ?? '');
  $credential_id  = trim($_POST['credential_id'] ?? '');
  $skills         = trim($_POST['skills'] ?? '');
  $credential_url = trim($_POST['credential_url'] ?? '');
  $download_url   = trim($_POST['download_url'] ?? '');
  $description    = trim($_POST['description'] ?? '');

  $imgName = $cert['image']; // keep
  if (!empty($_FILES['image']['name'])) {
    if (is_uploaded_file($_FILES['image']['tmp_name'])) {
      $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
      if (in_array($ext, ['jpg','jpeg','png','webp','gif'])) {
        $safe  = preg_replace('/[^a-zA-Z0-9_\.-]/','_', $_FILES['image']['name']);
        $new = time().'_'.$safe;
        $dest = __DIR__ . '/assets/projects/' . $new;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
          if (!empty($imgName)) {
            $old = __DIR__ . '/assets/projects/' . $imgName;
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
  }

  if ($title !== '') {
    $sql = "UPDATE certificates
               SET title=?, issuer=?, issued_at=?, credential_id=?, skills=?, credential_url=?, download_url=?, image=?, description=?
             WHERE id=?";
    $u = $conn->prepare($sql);
    $u->bind_param('sssssssssi',
      $title, $issuer, $issued_at, $credential_id, $skills, $credential_url, $download_url, $imgName, $description, $id
    );
    $u->execute();
    header('Location: admin_certificates.php?msg=updated');
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

  <title>Edit Certificate</title>
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
    .thumb { width:180px; height:120px; object-fit:cover; border-radius:10px; border:1px solid #1f2a46; background:#0b1120; }
  </style>
</head>
<body class="is-dark">
  <div class="wrap">
    <div class="top">
      <h2 style="margin:0;">Edit Certificate</h2>
      <a href="admin_certificates.php">← Back to Admin</a>
    </div>

    <?php if($notice): ?><div class="notice"><?=htmlspecialchars($notice)?></div><?php endif; ?>

    <div class="card">
      <form method="post" enctype="multipart/form-data">
        <label>Title</label>
        <input class="input" type="text" name="title" value="<?=htmlspecialchars($cert['title'])?>" required>

        <label>Description</label>
        <textarea class="textarea" name="description" rows="4"><?=htmlspecialchars($cert['description'] ?? '')?></textarea>

        <div class="row-2">
          <div><label>Issuer</label><input class="input" type="text" name="issuer" value="<?=htmlspecialchars($cert['issuer'] ?? '')?>"></div>
          <div><label>Issued (e.g., Jun 2025)</label><input class="input" type="text" name="issued_at" value="<?=htmlspecialchars($cert['issued_at'] ?? '')?>"></div>
        </div>

        <div class="row-2">
          <div><label>Credential ID</label><input class="input" type="text" name="credential_id" value="<?=htmlspecialchars($cert['credential_id'] ?? '')?>"></div>
          <div><label>Skills (comma-separated)</label><input class="input" type="text" name="skills" value="<?=htmlspecialchars($cert['skills'] ?? '')?>"></div>
        </div>

        <div class="row-2">
          <div><label>Show credential URL</label><input class="input" type="url" name="credential_url" value="<?=htmlspecialchars($cert['credential_url'] ?? '')?>"></div>
          <div><label>Download URL</label><input class="input" type="url" name="download_url" value="<?=htmlspecialchars($cert['download_url'] ?? '')?>"></div>
        </div>

        <label>Current image</label><br>
        <?php if(!empty($cert['image'])): ?>
          <img class="thumb" src="<?='assets/projects/'.htmlspecialchars($cert['image'])?>" alt="">
        <?php else: ?>
          <span style="color:#94a3b8">— none —</span>
        <?php endif; ?>
        <br><br>

        <label>Replace image (optional)</label>
        <input class="file" type="file" name="image" accept=".jpg,.jpeg,.png,.webp,.gif">

        <button class="btn" type="submit">Save Changes</button>
      </form>
    </div>
  </div>
</body>
</html>
