<?php
session_start();

// ── CHANGE THESE CREDENTIALS ──────────────────────────
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'tip2024admin'); // Change this!
// ──────────────────────────────────────────────────────

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_name']      = 'Administrator';
        header("Location: admin_dashboard.php");
    } else {
        header("Location: admin_login.php?error=1");
    }
    exit();
}

header("Location: admin_login.php");
exit();
?>
