<?php
session_start();

// Check if the user is logged in
function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

// Function to log in the user
function login($username, $password)
{
    global $pdo;

    // Intentionally vulnerable version
    // no prepare request, using variable in sql request
    $query = "SELECT id, username, admin, password FROM users WHERE username = '$username' AND password = '$password'";
    $result = $pdo->query($query);
    $user = $result->fetch();

    if ($user) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = (int)$user['admin'];
        return true;
    }

    return false;
}



// Function to sign up the user

function signup($username, $password)
{
    global $pdo;

    // Version vulnérable plus simple
    $query = "SELECT id FROM users WHERE username = '$username'";
    $result = $pdo->query($query);
    if ($result->fetch()) {
        return false;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Concaténation directe des valeurs
    $query = "INSERT INTO users SET username = '$username', 
                                  password = '$hashed_password', 
                                  admin = 0";
    return $pdo->query($query);
}

// Function to log out the user
function logout()
{
    session_start();
    session_destroy();
    header('Location: index.php');
    exit;
}

// Record failed login attempts in the database
function record_failed_attempt($ip_address)
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM failed_logins WHERE ip_address = ?");
    $stmt->execute([$ip_address]);
    $record = $stmt->fetch();

    if ($record) {
        // Update attempts count and timestamp
        $stmt = $pdo->prepare("UPDATE failed_logins SET attempts = attempts + 1, last_attempt = NOW() WHERE ip_address = ?");
        $stmt->execute([$ip_address]);
    } else {
        // Insert a new record for this IP
        $stmt = $pdo->prepare("INSERT INTO failed_logins (ip_address, attempts, last_attempt) VALUES (?, 1, NOW())");
        $stmt->execute([$ip_address]);
    }
}

// Check if login attempts exceed the limit
function check_login_attempts($ip_address)
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT attempts, last_attempt FROM failed_logins WHERE ip_address = ?");
    $stmt->execute([$ip_address]);
    $record = $stmt->fetch();

    if ($record) {
        $time_since_last_attempt = time() - strtotime($record['last_attempt']);
        if ($time_since_last_attempt < 300 && $record['attempts'] >= 5) {
            // Block login attempts for 5 minutes after 5 failed attempts
            return false;
        } elseif ($time_since_last_attempt >= 300) {
            // Reset the attempt count after 5 minutes
            reset_login_attempts($ip_address);
        }
    }

    return true;
}

// Reset login attempts after a successful login or timeout
function reset_login_attempts($ip_address)
{
    global $pdo;

    $stmt = $pdo->prepare("DELETE FROM failed_logins WHERE ip_address = ?");
    $stmt->execute([$ip_address]);
}

// Helper function to get the client's IP address
function get_client_ip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}
