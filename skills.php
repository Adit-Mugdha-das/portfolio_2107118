<?php
$name = "Adit Mugdha Das";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($name) ?> — Skills</title>
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
      <a href="education.php">Education</a>
      <a href="skills.php" class="active">Skills</a>
      <a href="projects.php">Projects</a>
      <a href="certifications.php">Certifications</a>
      <a href="achievements.php">Honors & Awards</a>
      <a href="contact.php">Contact</a>
    </nav>
  </div>
</header>

<main class="container">
  <section class="skills-section">
    <h1 class="skills-title">My Skills</h1>

    <div class="skills-grid">
      <!-- Left column -->
      <div class="skills-left">
        <article class="skill-card card">
          <h2>Programming Languages</h2>
          <ul>
            <li>Python</li><li>C / C++</li><li>Java</li><li>JavaScript</li>
          </ul>
        </article>

        <article class="skill-card card">
          <h2>Web Development</h2>
          <ul>
            <li>Laravel</li><li>PHP & MySQL</li><li>HTML, CSS, Tailwind</li><li>Node.js</li>
          </ul>
        </article>

        <article class="skill-card card">
          <h2>Tools & Platforms</h2>
          <ul>
            <li>Git & GitHub</li><li>VS Code</li><li>XAMPP</li><li>Jupyter Notebook</li><li>Microsoft Excel</li>
          </ul>
        </article>

        <article class="skill-card card">
          <h2>Android Development</h2>
          <ul>
            <li>Android Studio</li><li>Chaquopy</li><li>TFLite Integration</li><li>Flask</li><li>Tkinter</li>
          </ul>
        </article>

        <article class="skill-card card">
          <h2>Databases & Cloud</h2>
          <ul>
            <li>MySQL</li><li>Oracle</li><li>Firebase</li>
          </ul>
        </article>

        <article class="skill-card card">
          <h2>Additional Technical Skills</h2>
          <ul>
            <li>Problem Solving (600+ LeetCode solved)</li>
            <li>Data Cleaning</li>
            <li>Data Migration & Automation</li>
            <li>Digital Circuit Design (Logisim)</li>
          </ul>
        </article>
      </div>

      <!-- Right column -->
      <div class="skills-right">
        <article class="skill-card card">
          <h2>Machine Learning & AI</h2>
          <ul>
            <li>Supervised &amp; Unsupervised Learning</li>
    <li>Regression &amp; Classification Algorithms</li>
    <li>Scikit-learn, TensorFlow, Keras</li>
    <li>Regularization, Optimization</li>
    <li>Hyperparameter Tuning</li>
    <li>XGBoost</li>
    <li>Deep Learning</li>
    <li>Artificial Neural Networks (ANNs)</li>
    <li>ResNets, InceptionNet, MobileNet</li>
    <li>Classic CNNs (LeNet-5, AlexNet)</li>
    <li>Reinforcement Learning</li>
    <li>Computer Vision</li>
    <li>Object Detection (YOLO Algorithm)</li>
    <li>Neural Style Transfer</li>
    <li>U-Net, Siamese Networks</li>
    <li>Recurrent Neural Networks (RNNs)</li>
    <li>GRUs, LSTMs, Attention Mechanism</li>
    <li>Natural Language Processing (NLP)</li>
    <li>Sequence-to-Sequence Models</li>
    <li>Transformer Models</li>
    <li>HuggingFace Tokenizers</li>
          </ul>
        </article>
      </div>
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
