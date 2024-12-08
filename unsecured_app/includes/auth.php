<?php
if (!defined('AUTH_INCLUDED')) {
    // Marque le fichier comme inclus
    define('AUTH_INCLUDED', true);

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


        $query = "SELECT id, username, admin, password FROM users WHERE username = '$username'";

        try {
            $result = $pdo->query($query);
            $user = $result->fetch();

            // Si on trouve un utilisateur, on crée la session
            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int) $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_admin'] = (int) $user['admin'];
                return true;
            }
        } catch (PDOException $e) {
            // Log de l'erreur pour debug
            echo "Erreur SQL : " . $e->getMessage() . "<br>";
        }

        return false;
    }




    function signup($username, $password)
    {
        global $pdo;

        // Check if the username already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            return false; // Username already exists
        }

        // Hash the password and insert the new user
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        return $stmt->execute([$username, $hashed_password]);
    }

// Function to log out the user
function logout() {
    // Start session only if it is not already active
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Destroy the session
    session_destroy();

    // Clear all session variables
    $_SESSION = [];

    // Redirect to the login page
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
}
