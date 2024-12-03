<?php
require 'includes/db.php';
require 'includes/auth.php';

if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['content'] ?? '');

    if (empty($content)) {
        // If the post content is empty, redirect with an error message
        header('Location: index.php?error=empty_post');
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO posts (user_id, content) VALUES (:user_id, :content)");
        $stmt->execute([
            'user_id' => $_SESSION['user_id'], // Store the user_id
            'content' => $content,
        ]);
        

        // Redirect back to the homepage
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        // Handle any database errors
        die("Erreur lors de la création du post : " . $e->getMessage());
    }
} else {
    // If the request is not POST, redirect to the homepage
    header('Location: index.php');
    exit;
}
?>
