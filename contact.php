<?php
require __DIR__ . '/db.php';   // <-- make sure $conn is available

// Start session for navbar auth state (needed for Admin link logic)
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Simple form handler
$notice = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name    = trim($_POST['name'] ?? '');
  $email   = trim($_POST['email'] ?? '');
  $message = trim($_POST['message'] ?? '');

  if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && $message) {
    // Save to DB
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $stmt = $conn->prepare("INSERT INTO messages (name, email, message, ip_addr) VALUES (?,?,?,?)");
    if ($stmt) {
      $stmt->bind_param('ssss', $name, $email, $message, $ip);
      $stmt->execute();
      $stmt->close();
      $notice = "✅ Thanks! Your message has been sent.";
    } else {
      // If the table doesn't exist yet or another DB error occurs:
      $notice = "⚠️ Could not save your message (database error).";
    }

    // (Optional) Email notification:
    // @mail('mailbox.mugdha@gmail.com', 'New message from portfolio', "From: $name <$email>\n\n$message");

  } else {
    $notice = "⚠️ Please fill out all fields correctly.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Contact - Mugdha</title>
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <style>
    body {background:#0b1120; color:#fff; font-family:system-ui,Segoe UI,Roboto,sans-serif; margin:0;}

    nav {background:rgba(0,0,0,.6); padding:16px 24px; display:flex; justify-content:space-between; align-items:center;}
    nav .brand {font-weight:bold; color:#c084fc; font-size:20px; text-shadow:0 0 10px #c084fc;}
    nav ul {list-style:none; display:flex; gap:18px; margin:0; padding:0;}
    nav ul li a {color:#fff; text-decoration:none; transition:.3s;}
    nav ul li a:hover {color:#c084fc; text-shadow:0 0 8px #c084fc;}
    nav ul li a.active {color:#c084fc;}
    nav .hamburger {display:none; font-size:24px; color:#c084fc; background:none; border:none;}

    @media(max-width:768px){
      nav ul {display:none; flex-direction:column; background:#000; position:absolute; top:60px; left:0; width:100%; padding:16px;}
      nav ul.open {display:flex;}
      nav .hamburger {display:block;}
    }

    .contact-section {padding:60px 20px; text-align:center;}
    .contact-title {font-size:32px; font-weight:800; color:#fff; text-shadow:0 0 20px #c084fc; margin-bottom:24px;}

    .contact-wrapper {max-width:1100px; margin:0 auto; display:flex; flex-wrap:wrap; gap:24px;
      background:rgba(0,0,0,.6); border-radius:16px; padding:28px;
      box-shadow:0 0 12px rgba(255,255,255,.15);}
    .contact-left,.contact-right {flex:1 1 420px;}

    .contact-left h2 {
      font-size:22px;
      font-weight:700;
      margin-bottom:12px;
      text-align:left;
    }

    .contact-left p {
      margin:10px 0;
      color:#cbd5e1;
      display:flex;
      align-items:center;
      gap:10px;
      padding-left:5px;
    }
    .contact-left i {
      color:#c084fc;
      min-width:20px;
      text-align:center;
    }

    .socials {margin-top:20px; display:flex; gap:20px; font-size:26px;}
    .socials a {color:#c084fc; transition:.3s;}
    .socials a:hover {transform:scale(1.2); text-shadow:0 0 6px #9333ea;}

    .btn {
      display:inline-block;
      margin-top:20px;
      background:#c084fc;
      color:#fff;
      border-radius:10px;
      font-weight:600;
      text-decoration:none;
      padding:12px 20px;
      text-align:left;
    }
    .btn:hover {background:#a855f7;}

    form .input,form textarea {width:100%; background:rgba(255,255,255,.08); color:#fff;
      border:1px solid #a855f7; padding:12px; border-radius:10px; margin-bottom:14px;}
    form button {width:100%; padding:14px; background:#c084fc; border:0; border-radius:10px;
      font-weight:700; color:#fff; cursor:pointer;}
    form button:hover {background:#a855f7;}

    .notice {margin-bottom:16px; padding:10px; border-radius:10px;
      background:#0f172a; color:#fff;}
  </style>
</head>
<body>

<!-- Navbar -->
<nav>
  <div class="brand">Adit Mugdha Das</div>
  <button class="hamburger" id="hamburger" aria-label="Open menu"><i class="fas fa-bars"></i></button>
  <ul id="navLinks">
    <li><a href="homepage.php">Home</a></li>
    <li><a href="about.php">About</a></li>
    <li><a href="education.php">Education</a></li>
    <li><a href="skills.php">Skills</a></li>
    <li><a href="projects.php">Projects</a></li>
    <li><a href="certifications.php">Certifications</a></li>
    <li><a href="achievements.php">Honors & Awards</a></li>
    <li><a href="contact.php" class="active">Contact</a></li>

    <?php if (!empty($_SESSION['admin_id'])): ?>
      <li><a href="admin_projects.php">Admin Panel</a></li>
      <li><a href="logout.php" style="color:#fbbf24">Logout</a></li>
    <?php else: ?>
      <li><a href="login.php" style="color:#fbbf24">Admin</a></li>
    <?php endif; ?>
  </ul>
</nav>

<!-- Contact -->
<section class="contact-section">
  <h1 class="contact-title">SEND ME A MESSAGE</h1>

  <?php if($notice): ?>
    <div class="notice"><?= htmlspecialchars($notice) ?></div>
  <?php endif; ?>

  <div class="contact-wrapper">
    <!-- Left -->
    <div class="contact-left">
      <h2>Getting in touch is easy!</h2>
      <p><i class="fas fa-map-marker-alt"></i> Road 14, Adabor, Dhaka, Bangladesh</p>
      <p><i class="fas fa-phone"></i> +8801718108344</p>
      <p><i class="fas fa-envelope"></i> mailbox.mugdha@gmail.com</p>

      <div class="socials">
        <a href="https://www.linkedin.com/in/adit-mugdha-das-0a6723314/" target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin"></i></a>
        <a href="https://github.com/Adit-Mugdha-das" target="_blank" rel="noopener noreferrer"><i class="fab fa-github"></i></a>
        <a href="https://wa.me/8801718108344" target="_blank" rel="noopener noreferrer"><i class="fab fa-whatsapp"></i></a>
        <a href="https://www.facebook.com/aditmugdha.das.3" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook"></i></a>
      </div>

      <div style="text-align:left;">
        <a href="assets/cv/Adit_Mugdha_Das_CV.pdf" download class="btn">Download CV</a>
      </div>
    </div>

    <!-- Right -->
    <div class="contact-right">
      <form method="post">
        <input class="input" type="text" name="name" placeholder="Your Name" required>
        <input class="input" type="email" name="email" placeholder="Your Email (Use a valid email address)" required>
        <textarea class="input" name="message" rows="5" placeholder="Let me know how I can support your next big thing." required></textarea>
        <button type="submit">Send Message</button>
      </form>
    </div>
  </div>
</section>

<script>
document.getElementById('hamburger').addEventListener('click', ()=>{
  document.getElementById('navLinks').classList.toggle('open');
});
</script>

</body>
</html>
