<?php
require 'includes/db.php';
require 'includes/auth.php';

if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['content'] ?? '');
    $image_name = null;

    // Handle image upload
    if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB

        // Validate file type and size
        if (in_array($_FILES['image']['type'], $allowed_types) && 
            $_FILES['image']['size'] <= $max_size) {
            
            // Create uploads directory if it doesn't exist
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Generate unique filename with timestamp
            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image_name = 'post_' . time() . '_' . uniqid() . '.' . $file_extension;
            $upload_path = $upload_dir . $image_name;

            // Move uploaded file
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                // Handle upload error
                header('Location: index.php?error=upload_failed');
                exit;
            }
        } else {
            // Invalid file type or size
            header('Location: index.php?error=invalid_file');
            exit;
        }
    }

    // Validate text content (optional image upload)
    if (empty($content) && empty($image_name)) {
        header('Location: index.php?error=empty_post');
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO posts (user_id, content, image_name) VALUES (:user_id, :content, :image_name)");
        $stmt->execute([
            'user_id' => $_SESSION['user_id'],
            'content' => $content,
            'image_name' => $image_name,
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