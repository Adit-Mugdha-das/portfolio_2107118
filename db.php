<?php
// db.php
$host = '127.0.0.1';   // safer than 'localhost' on Windows
$user = 'root';
$pass = '';            // phpMyAdmin opens without a password → keep empty
$db   = 'portfolio';
$port = 3307;          // <-- IMPORTANT: match phpMyAdmin’s server port

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // show real errors
$conn = new mysqli($host, $user, $pass, $db, $port);
$conn->set_charset('utf8mb4');
?>
