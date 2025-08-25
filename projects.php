<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Projects</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <style>
    /* minimal styles in case style.css is empty; keep or move to style.css */
    body { font-family: system-ui, Arial, sans-serif; margin: 24px; color:#0f172a; }
    h1 { font-size: 32px; margin-bottom: 18px; }
    .grid { display:grid; grid-template-columns: repeat(auto-fill,minmax(260px,1fr)); gap:16px; }
    .card { border:1px solid #e5e7eb; border-radius:16px; padding:14px; box-shadow: 0 4px 12px rgba(0,0,0,.05); background:#fff; }
    .thumb { width:100%; height:160px; object-fit:cover; border-radius:12px; background:#f3f4f6; }
    .title { font-size:18px; margin:10px 0 6px; font-weight:700; }
    .desc { font-size:14px; color:#334155; margin-bottom:10px; line-height:1.4; }
    .actions a { font-size:14px; text-decoration:underline; }
  </style>
</head>
<body>
  <h1>My Projects</h1>
  <div class="grid">
    <?php
      $stmt = $conn->prepare("SELECT id, title, description, image, link FROM projects ORDER BY id DESC");
      $stmt->execute();
      $res = $stmt->get_result();
      if ($res->num_rows === 0) {
        echo "<p>No projects yet.</p>";
      } else {
        while ($row = $res->fetch_assoc()) {
          // If you stored only filename in DB, we load from assets/projects/
          $img = trim($row['image'] ?? '');
          $imgSrc = $img ? "assets/projects/" . htmlspecialchars($img) : "assets/edu/myproject.png"; // fallback image if you want
          echo "<div class='card'>";
          echo "  <img class='thumb' src='". $imgSrc ."' alt='". htmlspecialchars($row['title']) ."'>";
          echo "  <div class='title'>". htmlspecialchars($row['title']) ."</div>";
          echo "  <div class='desc'>". nl2br(htmlspecialchars($row['description'])) ."</div>";
          if (!empty($row['link'])) {
            echo "  <div class='actions'><a href='". htmlspecialchars($row['link']) ."' target='_blank' rel='noopener'>View Project</a></div>";
          }
          echo "</div>";
        }
      }
    ?>
  </div>
</body>
</html>
