<?php
// simple PHP variables so you can change text in one place later
$name    = "Adit Mugdha Das";
$tagline = "3rd‑year CSE student @ KUET — AI • Machine Learning • Deep Learning";
$cvLink  = "#"; // replace with /assets/Adit_Mugdha_CV.pdf when ready
$email   = "mailto:adit@example.com";
$linkedin= "https://www.linkedin.com/in/your-id";
$github  = "https://github.com/your-id";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($name) ?> — Portfolio</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="css/style.css" rel="stylesheet">
  <script defer src="js/app.js"></script>
</head>
<body>

<header class="site-header">
  <div class="container nav">
    <div class="brand">Adit Mugdha Das</div>
    <button class="hamburger" id="hamburger" aria-label="Open menu">☰</button>
    <nav id="nav">
      <a href="homepage.php" class="active">Home</a>
      <a href="about.php">About</a>
      <a href="education.php">Education</a>
      <a href="skills.php">Skills</a>
      <a href="projects.php">Projects</a>
      <a href="certifications.php">Certifications</a>
      <a href="awards.php">Honors & Awards</a>
      <a href="contact.php">Contact</a>
    </nav>
  </div>
</header>

<main class="container">
  <!-- hero -->
  <section class="hero card">
    <div class="hero-media">
      <img src="assets/profile.jpeg" alt="Profile photo of <?= htmlspecialchars($name) ?>">
    </div>
    <div class="hero-content">
      <p class="eyebrow">Hello, I'm</p>
      <h1><?= htmlspecialchars($name) ?></h1>
      <p class="tagline"><?= htmlspecialchars($tagline) ?></p>

      <div class="cta">
        <a class="btn primary" href="<?= htmlspecialchars($cvLink) ?>">Download CV</a>
        <a class="btn ghost"   href="<?= htmlspecialchars($cvLink) ?>">View CV</a>
      </div>

      <div class="socials">
        <a href="<?= htmlspecialchars($linkedin) ?>" target="_blank" rel="noopener" aria-label="LinkedIn">
          <!-- LinkedIn SVG -->
          <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path d="M4.98 3.5C4.98 4.88 3.86 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1 4.98 2.12 4.98 3.5zM.5 8h4V24h-4V8zm7 0h3.84v2.18h.05c.53-1 1.83-2.18 3.77-2.18 4.03 0 4.78 2.65 4.78 6.09V24h-4v-7.16c0-1.7-.03-3.89-2.37-3.89-2.37 0-2.73 1.85-2.73 3.77V24h-4V8z" fill="currentColor"/></svg>
        </a>
        <a href="<?= htmlspecialchars($github) ?>" target="_blank" rel="noopener" aria-label="GitHub">
          <!-- GitHub SVG -->
          <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path d="M12 .5a12 12 0 0 0-3.79 23.39c.6.11.82-.26.82-.58v-2.02c-3.34.73-4.04-1.61-4.04-1.61-.55-1.39-1.34-1.76-1.34-1.76-1.09-.74.08-.73.08-.73 1.21.09 1.85 1.25 1.85 1.25 1.07 1.84 2.8 1.31 3.48 1 .11-.78.42-1.31.76-1.61-2.66-.3-5.47-1.33-5.47-5.93 0-1.31.47-2.38 1.24-3.22-.12-.3-.54-1.52.12-3.17 0 0 1.01-.32 3.3 1.23a11.46 11.46 0 0 1 6 0c2.29-1.55 3.3-1.23 3.3-1.23.66 1.65.24 2.87.12 3.17.77.84 1.24 1.91 1.24 3.22 0 4.6-2.81 5.63-5.49 5.93.43.37.82 1.1.82 2.22v3.29c0 .32.22.7.83.58A12 12 0 0 0 12 .5Z" fill="currentColor"/></svg>
        </a>
        <a href="<?= htmlspecialchars($email) ?>" aria-label="Email">
          <!-- Mail SVG -->
          <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2Zm0 4-8 5L4 8V6l8 5 8-5v2Z" fill="currentColor"/></svg>
        </a>
        <a href="<?= htmlspecialchars($cvLink) ?>" aria-label="Download CV">
          <!-- File icon -->
          <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2V8l-6-6Zm1 7h5l-5-5v5Z" fill="currentColor"/></svg>
        </a>
      </div>
    </div>
  </section>

  <!-- quick sections placeholder you can fill later -->
  <section class="grid">
    <article class="mini card"><h3>About</h3><p>Short intro about you, focus on AI/ML interests and goals.</p></article>
    <article class="mini card"><h3>Skills</h3><p>Python, Java, C++, TensorFlow, Keras, PHP, Laravel, MySQL…</p></article>
    <article class="mini card"><h3>Projects</h3><p>Mindmap, GeoCleanser, Service Locator, DreamWeaver…</p></article>
  </section>
</main>

<footer class="site-footer">
  <div class="container">
    <small>© <?= date('Y') ?> <?= htmlspecialchars($name) ?>. All rights reserved.</small>
  </div>
</footer>

</body>
</html>
