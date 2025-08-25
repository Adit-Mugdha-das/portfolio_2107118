<?php
// auth.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db.php';

function current_admin_id(): ?int {
  return isset($_SESSION['admin_id']) ? (int)$_SESSION['admin_id'] : null;
}

function require_login(): void {
  if (!current_admin_id()) {
    $redirect = urlencode($_SERVER['REQUEST_URI'] ?? 'admin_projects.php');
    header('Location: login.php?redirect=' . $redirect);
    exit;
  }
}
