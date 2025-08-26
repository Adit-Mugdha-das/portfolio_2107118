<?php require 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Honors & Awards</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/css/style.css" />
  <style>
    :root{ --line:rgba(167,139,250,.2) }
    body.is-dark{ background:#0b1120; color:#e5e7eb }
    .wrap{max-width:1120px;margin:0 auto;padding:28px 16px}
    .title{font-weight:800;text-align:center;margin:12px 0 28px;font-size:clamp(28px,4vw,44px);color:#fff;text-shadow:0 0 12px rgba(192,132,252,.55)}
    .grid{display:grid;gap:24px;grid-template-columns:repeat(auto-fill,minmax(300px,1fr))}
    .card{background:rgba(0,0,0,.6);border:1px solid var(--line);border-radius:16px;padding:16px;box-shadow:0 8px 20px rgba(0,0,0,.35)}
    .cover{width:100%;height:200px;object-fit:cover;border-radius:12px;margin-bottom:14px;border:1px solid var(--line);cursor:zoom-in}
    .h{font-size:22px;margin:0 0 6px;font-weight:900;color:#e9d5ff;text-shadow:0 0 4px #a855f7}
    .sub{color:#cbd5e1;font-weight:700;margin-bottom:6px}
    .desc{color:#d1d5db;font-size:15px;line-height:1.55;margin:10px 0}
    .actions{display:flex;gap:16px;flex-wrap:wrap;margin-top:8px}
    .link{color:#c084fc;text-decoration:underline;font-weight:700}
    .link:hover{color:#fff}
    /* header copied style (same as other pages) */
    .site-header{position:sticky;top:0;z-index:10;background:rgba(15,23,42,.72);border-bottom:1px solid var(--line);backdrop-filter:blur(8px)}
    .nav{display:flex;align-items:center;justify-content:space-between;padding:14px 20px}
    .brand{font-weight:800;color:#fff;text-shadow:0 0 14px rgba(192,132,252,.35)}
    nav{display:flex;gap:18px}
    nav a{color:#cbd5e1;text-decoration:none;padding:8px 12px;border-radius:10px;transition:.2s}
    nav a:hover{color:#fff;background:#0b1220}
    nav a.active{color:#fff;background:#0b1220;box-shadow:0 0 0 1px #0b1220,0 0 14px rgba(168,85,247,.35)}
    .hamburger{display:none;background:transparent;border:0;color:#fff;font-size:22px}
    @media (max-width:900px){
      .hamburger{display:block}
      nav{position:absolute;right:20px;top:64px;display:none;flex-direction:column;background:#0b1220;border:1px solid var(--line);border-radius:14px;padding:10px}
      nav.open{display:flex}
    }
    .modal{position:fixed;inset:0;display:none;align-items:center;justify-content:center;z-index:50}
    .modal.open{display:flex}
    .backdrop{position:absolute;inset:0;background:rgba(0,0,0,.85)}
    .modal img{position:relative;max-width:min(92vw,1100px);max-height:90vh;border-radius:12px;box-shadow:0 0 0 4px #a855f7}
  </style>
</head>
<body class="is-dark">

<header class="site-header">
  <div class="nav">
    <div class="brand">Adit Mugdha Das</div>
    <button class="hamburger" onclick="document.querySelector('nav').classList.toggle('open')">☰</button>
    <nav>
      <a href="homepage.php">Home</a>
      <a href="about.php">About</a>
      <a href="education.php">Education</a>
      <a href="skills.php">Skills</a>
      <a href="projects.php">Projects</a>
      <a href="certifications.php">Certifications</a>
      <a href="achievements.php" class="active">Honors & Awards</a>
      <a href="contact.php">Contact</a>
      <?php if (session_status()===PHP_SESSION_NONE) session_start(); ?>
      <?php if (!empty($_SESSION['admin_id'])): ?>
        <a href="admin_achievements.php" style="color:#fbbf24">Admin</a>
        <a href="logout.php" style="color:#fbbf24">Logout</a>
      <?php else: ?>
        <a href="login.php" style="color:#fbbf24">Admin</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<main class="wrap">
  <h1 class="title">Honors & Awards</h1>
  <section class="grid">
    <?php
      $q = $conn->query("SELECT * FROM achievements ORDER BY id DESC");
      if (!$q || $q->num_rows === 0){
        echo "<p style='text-align:center;color:#94a3b8'>No achievements yet.</p>";
      } else {
        while ($a = $q->fetch_assoc()) {
          $img = !empty($a['image']) ? 'assets/awards/'.htmlspecialchars($a['image']) : 'assets/edu/myproject.png';
          echo '<article class="card">';
          echo '  <img class="cover" src="'.$img.'" alt="'.htmlspecialchars($a['title']).'" onclick="openModal(this.src)">';
          echo '  <h2 class="h">'.htmlspecialchars($a['title']).'</h2>';
          $sub = trim(($a['org'] ?? '').(!empty($a['issued']) ? ' — '.$a['issued'] : ''));
          if ($sub !== '') echo '  <div class="sub">'.htmlspecialchars($sub).'</div>';
          if (!empty($a['blurb'])) echo '  <div class="desc">'.nl2br(htmlspecialchars($a['blurb'])).'</div>';

          $links = [];
          if (!empty($a['verify_url']))  $links[] = '<a class="link" href="'.htmlspecialchars($a['verify_url']).'" target="_blank" rel="noopener">View</a>';
          if (!empty($a['download_url']))$links[] = '<a class="link" href="'.htmlspecialchars($a['download_url']).'" target="_blank" rel="noopener">Download</a>';
          if ($links) echo '<div class="actions">'.implode('', $links).'</div>';

          echo '</article>';
        }
      }
    ?>
  </section>
</main>

<div id="imgModal" class="modal" onclick="closeModal(event)">
  <div class="backdrop"></div>
  <img id="modalImg" src="" alt="">
</div>

<script>
  function openModal(src){ document.getElementById('modalImg').src = src; document.getElementById('imgModal').classList.add('open'); }
  function closeModal(e){ if (e.target.classList.contains('backdrop') || e.target.id==='imgModal') document.getElementById('imgModal').classList.remove('open'); }
  document.addEventListener('keydown', e => { if (e.key==='Escape') document.getElementById('imgModal').classList.remove('open'); });
</script>
</body>
</html>
