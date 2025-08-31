<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// clear session only
session_unset();
session_destroy();

// NOTE: do NOT delete the 'remember_user' cookie here
// setcookie('remember_user', '', time() - 3600, '/', '', isset($_SERVER['HTTPS']), true);

header('Location: homepage.php');
exit;
