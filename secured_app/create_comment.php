<?php
require 'includes/db.php';
require 'includes/auth.php';

if (!is_logged_in()) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'] ?? null;
    $content = trim($_POST['content'] ?? '');

    if (!$post_id || empty($content)) {
        // Redirect back to the post page with an error if validation fails
        header("Location: post.php?id=$post_id&error=empty_comment");
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (:post_id, :user_id, :content)");
        $stmt->execute([
            'post_id' => $post_id,
            'user_id' => $_SESSION['user_id'], // Store the user_id
            'content' => $content,
        ]);
        

        // Redirect back to the post page after successful comment creation
        header("Location: post.php?id=$post_id");
        exit;
    } catch (PDOException $e) {
        // Handle any database errors
        die("Erreur lors de la création du commentaire : " . $e->getMessage());
    }
} else {
    // If the request is not POST, redirect to the homepage
    header('Location: ../index.php');
    exit;
}
?>
