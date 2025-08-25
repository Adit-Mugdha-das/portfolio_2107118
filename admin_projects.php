<?php
require 'db.php';

// --- tiny guard (optional): add ?key=YOURKEY to the URL ---
$ADMIN_KEY = 'changeme123';
if (!isset($_GET['key']) || $_GET['key'] !== $ADMIN_KEY) {
  http_response_code(403);
  exit('Forbidden. Add ?key=changeme123 to the URL (and change the key in the file).');
}

// handle create
$notice = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $desc  = trim($_POST['description'] ?? '');
  $link  = trim($_POST['link'] ?? '');
  $fname = null;

  // handle file upload (optional)
  if (!empty($_FILES['image']['name'])) {
    $orig = $_FILES['image']['name'];
    $ext  = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
    if (in_array($ext, ['jpg','jpeg','png','webp','gif'])) {
      $fname = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/','_', $orig);
      $dest  = __DIR__ . '/assets/projects/' . $fname;
      if (!move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
        $notice = 'Image upload failed.';
        $fname = null;
      }
    } else {
      $notice = 'Invalid image type.';
    }
  }

  if ($title) {
    $stmt = $conn->prepare("INSERT INTO projects (title, description, image, link) VALUES (?,?,?,?)");
    $stmt->bind_param('ssss', $title, $desc, $fname, $link);
    $stmt->execute();
    $notice = $notice ?: 'Project added.';
  } else {
    $notice = $notice ?: 'Title is required.';
  }
}

// handle delete via GET ?delete=id
if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  // fetch image to remove file
  $imgStmt = $conn->prepare("SELECT image FROM projects WHERE id=?");
  $imgStmt->bind_param('i', $id);
  $imgStmt->execute();
  $imgRes = $imgStmt->get_result()->fetch_assoc();
  if ($imgRes && !empty($imgRes['image'])) {
    $path = __DIR__ . '/assets/projects/' . $imgRes['image'];
    if (is_file($path)) @unlink($path);
  }
  $del = $conn->prepare("DELETE FROM projects WHERE id=?");
  $del->bind_param('i', $id);
  $del->execute();
  header('Location: admin_projects.php?key='.$ADMIN_KEY.'&msg=deleted');
  exit;
}

// fetch all
$rows = $conn->query("SELECT id, title, image, link FROM projects ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin • Projects</title>
  <style>
    body { font-family: system-ui, Arial; margin: 24px; }
    h1 { margin-bottom: 10px; }
    form { display:grid; gap:10px; max-width:520px; margin-bottom: 28px; padding:16px; border:1px solid #e5e7eb; border-radius:12px; }
    input, textarea { padding:10px; border:1px solid #cbd5e1; border-radius:8px; }
    button { padding:10px 14px; border:0; border-radius:10px; background:#111827; color:#fff; cursor:pointer; }
    .table { width:100%; border-collapse: collapse; }
    .table th, .table td { border-bottom:1px solid #e5e7eb; padding:10px; text-align:left; }
    .thumb { width:72px; height:48px; object-fit:cover; border-radius:8px; background:#f3f4f6; }
    .notice { margin: 10px 0; color: #065f46; }
  </style>
</head>
<body>
  <h1>Admin • Projects</h1>
  <?php if(!empty($_GET['msg'])) echo "<div class='notice'>".htmlspecialchars($_GET['msg'])."</div>"; ?>
  <?php if($notice) echo "<div class='notice'>".htmlspecialchars($notice)."</div>"; ?>

  <form method="post" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Project title" required />
    <textarea name="description" rows="4" placeholder="Short description (optional)"></textarea>
    <input type="url" name="link" placeholder="Project link (GitHub/demo) (optional)" />
    <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,.gif" />
    <button type="submit">Add Project</button>
  </form>

  <table class="table">
    <thead><tr><th>Thumb</th><th>Title</th><th>Link</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach($rows as $r): ?>
        <tr>
          <td><?php if(!empty($r['image'])): ?>
              <img class="thumb" src="<?='assets/projects/'.htmlspecialchars($r['image'])?>" alt="">
              <?php endif; ?>
          </td>
          <td><?=htmlspecialchars($r['title'])?></td>
          <td><?php if(!empty($r['link'])): ?><a href="<?=htmlspecialchars($r['link'])?>" target="_blank" rel="noopener">Open</a><?php endif; ?></td>
          <td><a href="?key=<?=$ADMIN_KEY?>&delete=<?=$r['id']?>" onclick="return confirm('Delete this project?')">Delete</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
