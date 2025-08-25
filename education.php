<?php
$name = "Adit Mugdha Das";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($name) ?> — Education</title>
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
      <a href="homepage.php">Home</a>
      <a href="about.php">About</a>
      <a href="education.php" class="active">Education</a>
      <a href="skills.php">Skills</a>
      <a href="projects.php">Projects</a>
      <a href="certifications.php">Certifications</a>
      <a href="awards.php">Honors & Awards</a>
      <a href="contact.php">Contact</a>
    </nav>
  </div>
</header>

<main class="container">
  <section class="edu">
    <h1 class="edu-title">Education</h1>

    <!-- KUET -->
    <article class="edu-card card">
      <div class="edu-logo">
        <img src="assets/edu/kuet.png" alt="KUET logo">
      </div>
      <div class="edu-body">
        <h2 class="edu-school">Khulna University of Engineering and Technology</h2>
        <p class="edu-degree">B.Sc in Computer Science</p>
        <p class="edu-meta">Duration: Jan 2023 – Present</p>
        <p class="edu-highlight">CGPA: 3.69 <span class="muted">(from first four semesters)</span></p>
      </div>
    </article>

    <!-- BAF Shaheen -->
    <article class="edu-card card">
      <div class="edu-logo">
        <img src="assets/edu/baf.png" alt="BAF Shaheen logo">
      </div>
      <div class="edu-body">
        <h2 class="edu-school">BAF Shaheen College Dhaka</h2>
        <p class="edu-degree">Higher Secondary Certificate — Science</p>
        <p class="edu-meta">Duration: Aug 2019 – Jul 2021</p>
        <p class="edu-highlight">Grade: GPA-5 (Out of 5)</p>
      </div>
    </article>

    <!-- Shahjalal -->
    <article class="edu-card card">
      <div class="edu-logo">
        <img src="assets/edu/shahjalal.png" alt="Shahjalal N G F F School logo">
      </div>
      <div class="edu-body">
        <h2 class="edu-school">Shahjalal N G F F School</h2>
        <p class="edu-degree">Secondary School Certificate — Science</p>
        <p class="edu-meta">Duration: Jan 2009 – Mar 2019</p>
        <p class="edu-highlight">
          Grade: GPA-5
          <span class="badge">🏆 Best Talent of the Year</span>
          <a href="#" data-open="cert" class="edu-link">[View Certificate]</a>
        </p>
      </div>
    </article>
  </section>
</main>

<!-- Certificate Modal (vanilla JS, no libs) -->
<div class="modal" id="certModal" aria-hidden="true">
  <div class="modal-backdrop" data-close></div>
  <div class="modal-content">
    <button class="modal-close" title="Close" data-close>&times;</button>
    <img src="assets/edu/best_talent_certificate.jpeg" alt="Best Talent Certificate">
  </div>
</div>

<footer class="site-footer">
  <div class="container">
    <small>© <?= date('Y') ?> <?= htmlspecialchars($name) ?>. All rights reserved.</small>
  </div>
</footer>

<script>
  // open/close modal
  document.querySelectorAll('[data-open="cert"]').forEach(btn=>{
    btn.addEventListener('click', e=>{
      e.preventDefault();
      document.getElementById('certModal').classList.add('open');
      document.body.style.overflow = 'hidden';
    });
  });
  document.getElementById('certModal').addEventListener('click', e=>{
    if(e.target.hasAttribute('data-close') || e.target.id === 'certModal' || e.target.classList.contains('modal-backdrop')){
      document.getElementById('certModal').classList.remove('open');
      document.body.style.overflow = '';
    }
  });
</script>

</body>
</html>
