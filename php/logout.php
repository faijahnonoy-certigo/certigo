<?php
session_start();
include 'config.php';

// 🌐 Live server (cPanel)

// If user is logged in, destroy the session
if (isset($_SESSION['is_admin'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect with a message (optional)
    header("Location: ../login.html?logged_out=1");
    exit;
} else {
    // If user already logged out or no session exists
    header("Location: ../login.html");
    exit;
}
?>
