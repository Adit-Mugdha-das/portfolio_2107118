<?php
require __DIR__ . '/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Certifications - Mugdha</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <style>
    :root{
      --bg:#0b1120; --panel:rgba(0,0,0,.6); --line:rgba(167,139,250,.2);
      --text:#e5e7eb; --muted:#cbd5e1; --brand:#c084fc; --brand-2:#a855f7;
    }
    body{background:var(--bg); color:var(--text); font-family:system-ui,Segoe UI,Roboto,Arial,sans-serif; margin:0}

    /* Navbar (same as other pages) */
    .site-header{position:sticky; top:0; z-index:10; background:rgba(15,23,42,.72); border-bottom:1px solid var(--line); backdrop-filter:blur(8px)}
    .nav{display:flex; align-items:center; justify-content:space-between; padding:14px 20px}
    .brand{font-weight:800; color:#fff; letter-spacing:.3px; text-shadow:0 0 14px rgba(192,132,252,.35)}
    nav{display:flex; gap:18px}
    nav a{color:#cbd5e1; text-decoration:none; padding:8px 12px; border-radius:10px; transition:.2s}
    nav a:hover{color:#fff; background:#0b1220}
    nav a.active{color:#fff; background:#0b1220; box-shadow:0 0 0 1px #0b1220,0 0 14px rgba(168,85,247,.35)}
    .hamburger{display:none; background:transparent; border:0; color:#fff; font-size:22px}
    @media (max-width:900px){
      .hamburger{display:block}
      nav{position:absolute; right:20px; top:64px; display:none; flex-direction:column; background:#0b1220; border:1px solid var(--line); border-radius:14px; padding:10px}
      nav.open{display:flex}
    }

    .wrap{max-width:1120px; margin:0 auto; padding:28px 16px}
    .title{font-weight:800; text-align:center; margin:12px 0 28px; font-size:clamp(28px,4vw,44px); color:#fff; text-shadow:0 0 12px rgba(192,132,252,.55)}

    .grid{display:grid; gap:24px; grid-template-columns:repeat(auto-fill,minmax(300px,1fr))}
    .card{background:var(--panel); border:1px solid var(--line); border-radius:16px; padding:16px; box-shadow:0 8px 20px rgba(0,0,0,.35); transition:transform .25s ease, box-shadow .25s ease}
    .card:hover{transform:scale(1.03); box-shadow:0 0 14px var(--brand-2)}
    .cover{width:100%; height:180px; object-fit:cover; border-radius:12px; margin-bottom:14px; box-shadow:0 0 0 1px var(--line)}
    .card h2{font-size:22px; margin:0 0 6px; font-weight:900; color:#e9d5ff; text-shadow:0 0 4px var(--brand-2)}
    .issuer{font-weight:700; color:#cbd5e1}
    .meta{color:#cbd5e1; margin:4px 0 8px}
    .desc{color:#d1d5db; font-size:15px; line-height:1.55; margin:8px 0 10px}

    .chips{display:flex; gap:8px; flex-wrap:wrap; margin:6px 0 12px}
    .chip{background:rgba(192,132,252,.12); color:#e9d5ff; border:1px solid rgba(168,85,247,.35); padding:5px 10px; font-weight:700; font-size:12.5px; border-radius:999px}

    .actions{display:flex; gap:14px; flex-wrap:wrap; margin-top:8px}
    .link{color:#c084fc; text-decoration:underline; font-weight:700}
    .link:hover{color:#fff}
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
      <a href="projects.php">Projects</a>
      <a href="certifications.php" class="active">Certifications</a>
      <a href="achievements.php">Honors & Awards</a>
      <a href="contact.php">Contact</a>
      <?php if (!empty($_SESSION['admin_id'])): ?>
        <a href="admin_certificates.php">Admin Panel</a>
        <a href="logout.php" style="color:#fbbf24">Logout</a>
      <?php else: ?>
        <a href="login.php" style="color:#fbbf24">Admin</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<main class="wrap">
  <h1 class="title">Certifications</h1>

  <section class="grid">
    <?php
      $res = $conn->query("SELECT * FROM certificates ORDER BY id DESC");
      if (!$res || $res->num_rows === 0) {
        echo "<p style='text-align:center;color:#94a3b8'>No certificates yet.</p>";
      } else {
        while ($row = $res->fetch_assoc()) {
          $cover = !empty($row['image'])
            ? 'assets/projects/' . htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8')
            : 'assets/edu/myproject.png';

          echo '<article class="card">';
          echo '  <img class="cover" src="'.$cover.'" alt="'.htmlspecialchars($row['title']).'">';
          echo '  <h2>'.htmlspecialchars($row['title']).'</h2>';

          if (!empty($row['issuer'])) {
            echo '  <div class="issuer">'.htmlspecialchars($row['issuer']).'</div>';
          }

          $metaBits = [];
          if (!empty($row['issued_at']))     $metaBits[] = 'Issued '.$row['issued_at'];
          if (!empty($row['credential_id'])) $metaBits[] = 'Credential ID: '.htmlspecialchars($row['credential_id']);
          if (!empty($metaBits)) echo '<div class="meta">'.implode(' &nbsp;•&nbsp; ', array_map('htmlspecialchars',$metaBits)).'</div>';

          if (!empty($row['description'])) {
            echo '  <p class="desc">'.nl2br(htmlspecialchars($row['description'])).'</p>';
          }

          if (!empty($row['skills'])) {
            echo '<div class="chips">';
            foreach (explode(',', $row['skills']) as $s) {
              $s = trim($s);
              if ($s !== '') echo '<span class="chip">'.htmlspecialchars($s).'</span>';
            }
            echo '</div>';
          }

          $links = [];
          if (!empty($row['credential_url'])) $links[] = '<a class="link" href="'.htmlspecialchars($row['credential_url']).'" target="_blank" rel="noopener">Show Credential</a>';
          if (!empty($row['download_url']))   $links[] = '<a class="link" href="'.htmlspecialchars($row['download_url']).'" target="_blank" rel="noopener">Download</a>';
          if ($links) echo '<div class="actions">'.implode('', $links).'</div>';

          echo '</article>';
        }
      }
    ?>
  </section>
</main>

</body>
</html>
