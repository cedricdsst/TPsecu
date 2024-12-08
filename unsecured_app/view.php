<?php
include 'includes/auth.php';
include 'includes/db.php';

$page = $_GET['page'] ?? 'home';
include("pages/" . $page . ".php");  // Vulnérable à la LFI
