<?php
// Start session for navbar auth state
if (session_status() === PHP_SESSION_NONE) session_start();

// ===== Editable profile data =====
$name     = "Adit Mugdha Das";
$hello    = "Hello, I'm";
$tagline  = "3rd-year CSE student @ KUET — AI • Machine Learning • Deep Learning";
$bio      = "I'm a 3rd-year CSE student at KUET, driven by curiosity in AI, Machine Learning, and Deep Learning. I thrive on solving real-world problems and crafting unique, innovative tech with clean, elegant code.";
$cvDownload = "assets/cv/Adit_Mugdha_Das_CV.pdf";  // local file for download
$cvView     = "assets/cv/Adit_Mugdha_Das_CV.pdf";   // e.g. Google Drive link
$links = [
  'linkedin' => 'https://www.linkedin.com/in/adit-mugdha-das-0a6723314/',
  'github'   => 'https://github.com/Adit-Mugdha-das',
  'email'    => 'mailto:mailbox.mugdha@gmail.com',
  'whatsapp' => 'https://wa.me/8801718108344',
  'leetcode' => 'https://leetcode.com/u/Mugdha_118/',
  'facebook' => 'https://www.facebook.com/aditmugdha.das.3',
];
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
<body class="is-dark">

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

<main class="container">
  <!-- Centered hero like your other site (no 3D BG) -->
  <section class="hero card centered">
    <img class="profile-img" src="assets/profile.jpeg" alt="Profile photo of <?= htmlspecialchars($name) ?>">

    <h2 class="eyebrow"><?= htmlspecialchars($hello) ?></h2>
    <h1 class="headline"><?= htmlspecialchars($name) ?></h1>

    <p class="tagline"><?= htmlspecialchars($tagline) ?></p>
    <p class="bio"><?= htmlspecialchars($bio) ?></p>

    <div class="cta">
      <a class="btn primary" href="<?= htmlspecialchars($cvDownload) ?>" download>Download CV</a>
      <a class="btn ghost"   href="<?= htmlspecialchars($cvView) ?>" target="_blank" rel="noopener">View CV</a>
    </div>

    <div class="socials">
      <!-- LinkedIn -->
      <a href="<?= htmlspecialchars($links['linkedin']) ?>" target="_blank" rel="noopener" aria-label="LinkedIn">
        <svg viewBox="0 0 24 24" width="26" height="26" aria-hidden="true"><path fill="currentColor" d="M4.98 3.5A2.5 2.5 0 1 1 0 3.5a2.5 2.5 0 0 1 4.98 0zM.5 8h4v16h-4V8zm7 0h3.84v2.18h.05c.53-1 1.83-2.18 3.77-2.18 4.03 0 4.78 2.65 4.78 6.09V24h-4v-7.16c0-1.7-.03-3.89-2.37-3.89-2.37 0-2.73 1.85-2.73 3.77V24h-4V8z"/></svg>
      </a>
      <!-- GitHub -->
      <a href="<?= htmlspecialchars($links['github']) ?>" target="_blank" rel="noopener" aria-label="GitHub">
        <svg viewBox="0 0 24 24" width="26" height="26" aria-hidden="true"><path fill="currentColor" d="M12 .5a12 12 0 0 0-3.79 23.39c.6.11.82-.26.82-.58v-2.02c-3.34.73-4.04-1.61-4.04-1.61-.55-1.39-1.34-1.76-1.34-1.76-1.09-.74.08-.73.08-.73 1.21.09 1.85 1.25 1.85 1.25 1.07 1.84 2.8 1.31 3.48 1 .11-.78.42-1.31.76-1.61-2.66-.3-5.47-1.33-5.47-5.93 0-1.31.47-2.38 1.24-3.22-.12-.3-.54-1.52.12-3.17 0 0 1.01-.32 3.3 1.23a11.46 11.46 0 0 1 6 0c2.29-1.55 3.3-1.23 3.3-1.23.66 1.65.24 2.87.12 3.17.77.84 1.24 1.91 1.24 3.22 0 4.6-2.81 5.63-5.49 5.93.43.37.82 1.1.82 2.22v3.29c0 .32.22.7.83.58A12 12 0 0 0 12 .5Z"/></svg>
      </a>
      <!-- Email -->
      <a href="<?= htmlspecialchars($links['email']) ?>" aria-label="Email">
        <svg viewBox="0 0 24 24" width="26" height="26" aria-hidden="true"><path fill="currentColor" d="M20 4H4C2.9 4 2 4.9 2 6v12c0 1.1.9 2 2 2h16a2 2 0 0 0 2-2V6c0-1.1-.9-2-2-2Zm0 4-8 5L4 8V6l8 5 8-5v2Z"/></svg>
      </a>
      <!-- WhatsApp -->
      <a href="<?= htmlspecialchars($links['whatsapp']) ?>" target="_blank" rel="noopener" aria-label="WhatsApp">
        <svg viewBox="0 0 24 24" width="26" height="26" aria-hidden="true"><path fill="currentColor" d="M20.5 3.5a10 10 0 0 0-17 10.5L2 22l8.3-1.5a10 10 0 0 0 10.2-17Z M12 4a8 8 0 0 1 6.3 12.8 8 8 0 0 1-8.7 2L6 20l.9-3.6A8 8 0 0 1 12 4Zm4.3 9.6c-.2-.1-1.4-.7-1.6-.8-.2-.1-.3-.1-.5.1-.1.2-.6.8-.7.9-.1.1-.3.2-.5.1a6.4 6.4 0 0 1-3.5-3 .5.5 0 0 1 .1-.5l.4-.6c.1-.1.1-.3 0-.4l-.8-1.9c-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.5.1-.7.3-.7.7-1 1.6-1 2.5 0 .3.1.6.2.9a10.9 10.9 0 0 0 5.3 5.6c.6.3 1.2.5 1.8.6.7.1 1.3 0 1.9-.3.3-.2 1.1-.5 1.2-1.1.1-.4.1-.8 0-1.2 0-.2-.2-.2-.3-.3Z"/></svg>
      </a>
      <!-- LeetCode (code icon) -->
      <a href="<?= htmlspecialchars($links['leetcode']) ?>" target="_blank" rel="noopener" aria-label="LeetCode">
        <svg viewBox="0 0 24 24" width="26" height="26" aria-hidden="true"><path fill="currentColor" d="M9.4 16.6 5.8 13l3.6-3.6L8 8l-5 5 5 5 1.4-1.4Zm5.2 0 1.4 1.4 5-5-5-5-1.4 1.4L18.2 13l-3.6 3.6ZM13 6h-2l-2 12h2l2-12Z"/></svg>
      </a>
      <!-- Facebook -->
      <a href="<?= htmlspecialchars($links['facebook']) ?>" target="_blank" rel="noopener" aria-label="Facebook">
        <svg viewBox="0 0 24 24" width="26" height="26" aria-hidden="true"><path fill="currentColor" d="M22 12a10 10 0 1 0-11.6 9.9v-7h-2.4V12h2.4V9.8c0-2.4 1.4-3.7 3.6-3.7 1 0 2 .2 2 .2v2.2H15c-1.2 0-1.6.8-1.6 1.6V12h2.7l-.4 2.9H13.4v7A10 10 0 0 0 22 12Z"/></svg>
      </a>
    </div>
  </section>
</main>

<footer class="site-footer">
  <div class="container">
    <small>© <?= date('Y') ?> <?= htmlspecialchars($name) ?>. All rights reserved.</small>
  </div>
</footer>

</body>
</html>
