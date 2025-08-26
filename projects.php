<?php
include 'db.php';

// make navbar aware of auth state
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Projects - Mugdha</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <style>
    :root{
      --bg:#0b1120; --panel:rgba(0,0,0,.6); --line:rgba(167,139,250,.2);
      --text:#e5e7eb; --muted:#cbd5e1; --brand:#c084fc; --brand-2:#a855f7;
    }
    body{background:var(--bg); color:var(--text); font-family:system-ui,Segoe UI,Roboto,Arial,sans-serif; margin:0}

    /* Navbar (matches your other pages) */
    .site-header{position:sticky; top:0; z-index:10; background:rgba(15,23,42,.72);
      border-bottom:1px solid var(--line); backdrop-filter:blur(8px)}
    .nav{display:flex; align-items:center; justify-content:space-between; padding:14px 20px}
    .brand{font-weight:800; color:#fff; letter-spacing:.3px; text-shadow:0 0 14px rgba(192,132,252,.35)}
    nav{display:flex; gap:18px}
    nav a{color:#cbd5e1; text-decoration:none; padding:8px 12px; border-radius:10px; transition:.2s}
    nav a:hover{color:#fff; background:#0b1220}
    nav a.active{color:#fff; background:#0b1220; box-shadow:0 0 0 1px #0b1220,0 0 14px rgba(168,85,247,.35)}
    .hamburger{display:none; background:transparent; border:0; color:#fff; font-size:22px}
    @media (max-width:900px){
      .hamburger{display:block}
      nav{position:absolute; right:20px; top:64px; display:none; flex-direction:column;
        background:#0b1220; border:1px solid var(--line); border-radius:14px; padding:10px}
      nav.open{display:flex}
    }

    /* Page wrapper */
    .wrap{max-width:1120px; margin:0 auto; padding:28px 16px}
    .title{font-weight:800; text-align:center; margin:12px 0 28px;
      font-size:clamp(28px,4vw,44px); color:#fff; text-shadow:0 0 12px rgba(192,132,252,.55)}

    /* Grid + Cards */
    .grid{display:grid; gap:24px; grid-template-columns:repeat(auto-fill,minmax(300px,1fr))}
    .card{background:var(--panel); border:1px solid var(--line); border-radius:16px; padding:16px;
      box-shadow:0 8px 20px rgba(0,0,0,.35); transition:transform .25s ease, box-shadow .25s ease}
    .card:hover{transform:scale(1.03); box-shadow:0 0 14px var(--brand-2)}
    .cover{width:100%; height:200px; object-fit:cover; border-radius:12px; margin-bottom:14px;
      cursor:zoom-in; box-shadow:0 0 0 1px var(--line)}

    .card h2{font-size:22px; margin:0 0 6px; font-weight:900; color:#e9d5ff; text-decoration:underline;
      text-underline-offset:3px; text-shadow:0 0 4px var(--brand-2)}
    .desc{color:#d1d5db; font-size:15px; line-height:1.55; margin:0 0 10px}

    .meta{font-weight:700; color:#cbd5e1; margin:6px 0 10px}
    .meta .val{color:#fff}

    .chips{display:flex; gap:8px; flex-wrap:wrap; margin:0 0 12px}
    .chip{background:rgba(192,132,252,.12); color:#e9d5ff; border:1px solid rgba(168,85,247,.35);
      padding:5px 10px; font-weight:700; font-size:12.5px; border-radius:999px}

    .thumbs{display:flex; gap:8px; flex-wrap:wrap; margin:6px 0 12px}
    .thumbs img{width:74px; height:54px; object-fit:cover; border-radius:8px; border:1px solid var(--line); cursor:zoom-in}

    .actions{display:flex; gap:14px; flex-wrap:wrap; margin-top:6px}
    .link{color:#c084fc; text-decoration:underline; font-weight:700}
    .link:hover{color:#fff}

    .award{margin-top:8px; color:#ffd166; font-weight:800}

    /* Modal */
    .modal{position:fixed; inset:0; display:none; align-items:center; justify-content:center; z-index:50}
    .modal.open{display:flex}
    .backdrop{position:absolute; inset:0; background:rgba(0,0,0,.85)}
    .modal img{position:relative; max-width:min(92vw,1100px); max-height:90vh; border-radius:12px;
      box-shadow:0 0 0 4px var(--brand-2)}
  </style>
</head>
<body>

<header class="site-header">
  <div class="nav">
    <div class="brand">Adit Mugdha Das</div>
    <button class="hamburger" onclick="document.querySelector('nav').classList.toggle('open')">☰</button>
    <nav>
      <a href="homepage.php">Home</a>
      <a href="about.php">About</a>
      <a href="education.php">Education</a>
      <a href="skills.php">Skills</a>
      <a href="projects.php" class="active">Projects</a>
      <a href="certifications.php">Certifications</a>
      <a href="achievements.php">Honors & Awards</a>
      <a href="contact.php">Contact</a>

      <?php if (!empty($_SESSION['admin_id'])): ?>
        <a href="admin_projects.php">Admin Panel</a>
        <a href="logout.php" style="color:#fbbf24">Logout</a>
      <?php else: ?>
        <a href="login.php" style="color:#fbbf24">Admin</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<main class="wrap">
  <h1 class="title">My Projects</h1>

  <section class="grid">
    <?php
      // Use SELECT * so optional columns won't cause errors if missing.
      $res = $conn->query("SELECT * FROM projects ORDER BY id DESC");

      if (!$res || $res->num_rows === 0){
        echo "<p style='text-align:center;color:#94a3b8'>No projects yet. Add some from the admin panel.</p>";
      } else {
        while ($row = $res->fetch_assoc()) {

          // IMAGES (single or comma-separated)
          $images = [];
          if (!empty($row['image'])) {
            foreach (explode(',', $row['image']) as $piece) {
              $piece = trim($piece);
              if ($piece) $images[] = 'assets/projects/' . htmlspecialchars($piece, ENT_QUOTES, 'UTF-8');
            }
          }
          $cover = count($images) ? $images[0] : 'assets/edu/myproject.png';

          // OPTIONAL FIELDS (show only if present in table & not empty)
          $duration = isset($row['duration']) ? trim($row['duration']) : '';
          $tech     = isset($row['tech']) ? trim($row['tech']) : ''; // comma-separated
          $github   = isset($row['github']) ? trim($row['github']) : '';
          $readme   = isset($row['readme']) ? trim($row['readme']) : '';
          $download = isset($row['download']) ? trim($row['download']) : '';
          $award    = isset($row['award']) ? trim($row['award']) : '';
          $open     = isset($row['link']) ? trim($row['link']) : '';

          echo '<article class="card">';

          // Cover
          echo '  <img class="cover" src="'.$cover.'" alt="'.htmlspecialchars($row['title']).'" onclick="openModal(this.src)">';

          // Title
          echo '  <h2>'.htmlspecialchars($row['title']).'</h2>';

          // Description
          if (!empty($row['description'])) {
            echo '  <p class="desc">'.nl2br(htmlspecialchars($row['description'])).'</p>';
          }

          // Duration
          if ($duration !== '') {
            echo '  <div class="meta">Duration: <span class="val">'.htmlspecialchars($duration).'</span></div>';
          }

          // Tech chips
          if ($tech !== '') {
            echo '  <div class="chips">';
            foreach (explode(',', $tech) as $t) {
              $t = trim($t);
              if ($t !== '') echo '<span class="chip">'.htmlspecialchars($t).'</span>';
            }
            echo '  </div>';
          }

          // Extra thumbnails
          if (count($images) > 1) {
            echo '<div class="thumbs">';
            for ($i=1; $i<count($images); $i++){
              echo '<img src="'.$images[$i].'" alt="" onclick="openModal(this.src)">';
            }
            echo '</div>';
          }

          // Links row
          $links = [];
          if ($github   !== '') $links[] = '<a class="link" href="'.htmlspecialchars($github).'" target="_blank" rel="noopener">GitHub</a>';
          if ($readme   !== '') $links[] = '<a class="link" href="'.htmlspecialchars($readme).'" target="_blank" rel="noopener">Read More</a>';
          if ($download !== '') $links[] = '<a class="link" href="'.htmlspecialchars($download).'" target="_blank" rel="noopener">Download</a>';
          if ($open     !== '') $links[] = '<a class="link" href="'.htmlspecialchars($open).'" target="_blank" rel="noopener">Open</a>';
          if (!empty($links)) {
            echo '  <div class="actions">'.implode('', $links).'</div>';
          }

          // Award
          if ($award !== '') {
            echo '  <div class="award">🏆 '.htmlspecialchars($award).'</div>';
          }

          echo '</article>';
        }
      }
    ?>
  </section>
</main>

<!-- Image Modal -->
<div id="imgModal" class="modal" onclick="closeModal(event)">
  <div class="backdrop"></div>
  <img id="modalImg" src="" alt="">
</div>

<script>
  function openModal(src){
    document.getElementById('modalImg').src = src;
    document.getElementById('imgModal').classList.add('open');
  }
  function closeModal(e){
    if (e.target.classList.contains('backdrop') || e.target.id === 'imgModal'){
      document.getElementById('imgModal').classList.remove('open');
    }
  }
  document.addEventListener('keydown', (e)=> {
    if (e.key === 'Escape') document.getElementById('imgModal').classList.remove('open');
  });
</script>

</body>
</html>
