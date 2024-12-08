<?php
// Include authentication functions
require 'includes/auth.php';

// Check if a session is active before starting it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to the homepage
header('Location: index.php');
exit;
