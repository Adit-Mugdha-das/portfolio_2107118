<?php
require __DIR__ . '/db.php';

require __DIR__ . '/auth.php';
require_login();   // must be logged in to edit

$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM projects WHERE id=?");
$stmt->bind_param('i', $id);
$stmt->execute();
$proj = $stmt->get_result()->fetch_assoc();
if (!$proj) { exit('Project not found.'); }

$notice = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title     = trim($_POST['title'] ?? '');
  $desc      = trim($_POST['description'] ?? '');
  $link      = trim($_POST['link'] ?? '');
  $duration  = trim($_POST['duration'] ?? '');
  $tech      = trim($_POST['tech'] ?? '');
  $github    = trim($_POST['github'] ?? '');
  $readme    = trim($_POST['readme'] ?? '');
  $download  = trim($_POST['download'] ?? '');
  $award     = trim($_POST['award'] ?? '');

  // Keep existing images by default (CSV list)
  $currentImagesCSV = $proj['image'] ?? '';
  $newImagesCSV     = $currentImagesCSV;

  // If user uploaded any new file(s), replace ALL images with the new list
  $hasNewUpload = !empty($_FILES['images']['name'][0]);
  if ($hasNewUpload) {
    $filenames = [];

    foreach ($_FILES['images']['name'] as $i => $orig) {
      if (!is_uploaded_file($_FILES['images']['tmp_name'][$i])) continue;
      $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
      if (!in_array($ext, ['jpg','jpeg','png','webp','gif'])) continue;

      $safe  = preg_replace('/[^a-zA-Z0-9_\.-]/','_', $orig);
      $fname = time().'_'.$i.'_'.$safe;
      $dest  = __DIR__ . '/assets/projects/' . $fname;
      if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $dest)) {
        $filenames[] = $fname;
      }
    }

    if (!empty($filenames)) {
      // delete old files
      if (!empty($currentImagesCSV)) {
        foreach (explode(',', $currentImagesCSV) as $old) {
          $old = trim($old);
          if (!$old) continue;
          $p = __DIR__ . '/assets/projects/' . $old;
          if (is_file($p)) @unlink($p);
        }
      }
      $newImagesCSV = implode(',', $filenames);
    } else {
      $notice = '⚠️ No valid new images uploaded; keeping previous images.';
    }
  }

  if ($title !== '') {
    $sql = "UPDATE projects
               SET title=?, description=?, link=?, image=?,
                   duration=?, tech=?, github=?, readme=?, download=?, award=?
             WHERE id=?";
    $u = $conn->prepare($sql);
    $u->bind_param(
      'ssssssssssi',
      $title, $desc, $link, $newImagesCSV,
      $duration, $tech, $github, $readme, $download, $award,
      $id
    );
    $u->execute();

    header('Location: admin_projects.php?msg=updated'); // <-- no key
    exit;
  } else {
    $notice = $notice ?: '⚠️ Title is required.';
  }
}

// Helper: explode current images for preview
$imagesArray = [];
if (!empty($proj['image'])) {
  foreach (explode(',', $proj['image']) as $p) {
    $p = trim($p);
    if ($p !== '') $imagesArray[] = $p;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Edit Project</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <style>
    body.is-dark { background:#0b1020; color:#e5e7eb; }
    .wrap { max-width:900px; margin:32px auto; padding:0 16px; }
    .card { background:#0f152b; border:1px solid #1f2a46; border-radius:16px; box-shadow:0 8px 24px rgba(0,0,0,.25); padding:16px; }
    .top { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; }
    a { color:#93c5fd; text-decoration:none; }

    .input, .textarea, .file, .btn { width:100%; }
    .input, .textarea, .file {
      padding:12px; background:#0b1120; color:#e5e7eb;
      border:1px solid #1f2a46; border-radius:12px; margin-bottom:10px;
    }
    .row-2 { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
    @media (max-width:700px){ .row-2 { grid-template-columns:1fr } }

    .btn { padding:12px 14px; border:0; border-radius:12px; background:#0ea5e9; color:#081019; font-weight:700; cursor:pointer; }
    .notice { margin-bottom:10px; padding:10px 12px; border-radius:10px; background:#0f172a; border:1px solid #1f2937; }

    .thumbs { display:flex; gap:10px; flex-wrap:wrap; margin:6px 0 10px; }
    .thumb { width:140px; height:92px; object-fit:cover; border-radius:10px; border:1px solid #1f2a46; background:#0b1120; }
    .help { color:#94a3b8; font-size:13px; margin-top:-4px; margin-bottom:10px; }
  </style>
</head>
<body class="is-dark">
  <div class="wrap">
    <div class="top">
      <h2 style="margin:0;">Edit Project</h2>
      <a href="admin_projects.php">← Back to Admin</a>
    </div>

    <?php if($notice): ?><div class="notice"><?=htmlspecialchars($notice)?></div><?php endif; ?>

    <div class="card">
      <form method="post" enctype="multipart/form-data">
        <label>Title</label>
        <input class="input" type="text" name="title" value="<?=htmlspecialchars($proj['title'])?>" required>

        <label>Description</label>
        <textarea class="textarea" name="description" rows="5"><?=htmlspecialchars($proj['description'])?></textarea>

        <div class="row-2">
          <div>
            <label>Open / Demo URL</label>
            <input class="input" type="url" name="link" value="<?=htmlspecialchars($proj['link'])?>">
          </div>
          <div>
            <label>Duration</label>
            <input class="input" type="text" name="duration" placeholder="e.g., Jul–Dec 2024" value="<?=htmlspecialchars($proj['duration'] ?? '')?>">
          </div>
        </div>

        <label>Tech chips (comma-separated)</label>
        <input class="input" type="text" name="tech" placeholder="JavaFX, MySQL, CSS" value="<?=htmlspecialchars($proj['tech'] ?? '')?>">

        <div class="row-2">
          <div>
            <label>GitHub URL</label>
            <input class="input" type="url" name="github" value="<?=htmlspecialchars($proj['github'] ?? '')?>">
          </div>
          <div>
            <label>Read More / README URL</label>
            <input class="input" type="url" name="readme" value="<?=htmlspecialchars($proj['readme'] ?? '')?>">
          </div>
        </div>

        <div class="row-2">
          <div>
            <label>Download URL</label>
            <input class="input" type="url" name="download" value="<?=htmlspecialchars($proj['download'] ?? '')?>">
          </div>
          <div>
            <label>Award text</label>
            <input class="input" type="text" name="award" placeholder="Best Project Award In KUET" value="<?=htmlspecialchars($proj['award'] ?? '')?>">
          </div>
        </div>

        <label>Current images</label>
        <?php if (!empty($imagesArray)): ?>
          <div class="thumbs">
            <?php foreach ($imagesArray as $img): ?>
              <img class="thumb" src="<?='assets/projects/'.htmlspecialchars($img)?>" alt="">
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="help">— none —</div>
        <?php endif; ?>

        <label>Replace images (optional)</label>
        <input class="file" type="file" name="images[]" accept=".jpg,.jpeg,.png,.webp,.gif" multiple>
        <div class="help">If you choose any files here, all previous images will be replaced.</div>

        <button class="btn" type="submit">Save Changes</button>
      </form>
    </div>
  </div>
</body>
</html>
