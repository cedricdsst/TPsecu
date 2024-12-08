<?php
// Database configuration
$host = 'db'; // Docker service name for MySQL
$db = 'projet'; // Database name
$user = 'user'; // Database user (from MYSQL_USER in docker-compose.yml)
$password = 'password'; // Database password (from MYSQL_PASSWORD in docker-compose.yml)

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   
} catch (PDOException $e) {
    // Handle connection error
    die("Database connection failed: " . $e->getMessage());
}
?>
