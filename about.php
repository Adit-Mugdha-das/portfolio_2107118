<?php
// ---- editable page data ----
$name       = "Adit Mugdha Das";
$pageTitle  = "About Me";
$photoPath  = "assets/aboutphoto.png"; // or "assets/profile.jpeg"
$intro1 = "Hi, I’m <strong>Adit Mugdha Das</strong>, a disciplined and passionate <span class=\"highlight\">Computer Science and Engineering</span> undergrad at <strong>KUET</strong>. I’m deeply engaged with <span class=\"highlight\">Machine Learning</span> and <span class=\"highlight\">Deep Learning</span>, and love solving complex problems that can create impactful real-world solutions.";
$intro2 = "My academic foundation (CGPA: 3.69) is backed by hands-on experiences in the field, such as my <span class=\"highlight\">Data Analysis experience</span> at Summit Communications Ltd., where I developed the <strong>Internet Service Locator</strong> using Google Places API to automate the retrieval of nearby locations. Additionally, I contributed to the <strong>GeoCleanser</strong> GIS tool by enhancing its data validation features, improving the accuracy of geographic data processing. I’ve also worked as a QNA teacher at UDVASH, sharpening my analytical and communication skills.";
$intro3 = "I’ve been recognized for my achievements, including the <span class=\"highlight\">Best Project Award</span> at KUET for my project \"Mindmap,\" and I was also honored as the <span class=\"highlight\">Best Talent of the Year</span> in Math & CS during my school years.";
$intro4 = "Beyond academics, I’m passionate about sports (cricket, chess, football), traveling, and always striving to grow personally and professionally. My long-term goal is to pursue a <span class=\"highlight\">Ph.D. in Machine Learning</span> and contribute to cutting-edge research.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title><?= htmlspecialchars($name) ?> — About</title>
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
      <a href="about.php" class="active">About</a>
      <a href="education.php">Education</a>
      <a href="skills.php">Skills</a>
      <a href="projects.php">Projects</a>
      <a href="certifications.php">Certifications</a>
      <a href="achievements.php">Honors & Awards</a>
      <a href="contact.php">Contact</a>
    </nav>
  </div>
</header>

<main class="container">
  <!-- matches the structure/sizing of your old page, no external libs -->
  <section class="about-wrapper card">
    <!-- Left: image -->
    <div class="profile-image">
      <img src="<?= htmlspecialchars($photoPath) ?>" alt="Photo of <?= htmlspecialchars($name) ?>">
    </div>

    <!-- Right: text -->
    <div class="about-text-content">
      <h1 class="about-title"><?= htmlspecialchars($pageTitle) ?></h1>

      <p class="about-text"><?= $intro1 ?></p>
      <p class="about-text"><?= $intro2 ?></p>
      <p class="about-text"><?= $intro3 ?></p>
      <p class="about-text"><?= $intro4 ?></p>

      <p class="about-text">
        If you’re interested in collaborating or discussing ideas in AI,
        <a class="cta-link" href="contact.php">feel free to contact me</a>.
      </p>
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
