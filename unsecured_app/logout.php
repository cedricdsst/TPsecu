<?php
require 'includes/auth.php';

// Clear session and redirect to homepage
session_start();
session_destroy();
header('Location: index.php');
exit;
?>
