<?php
require 'includes/db.php';
require 'includes/auth.php';
require 'includes/functions.php';

$post_id = $_GET['id'] ?? null;
$post = fetch_post_by_id($post_id);
$comments = fetch_comments($post_id);

if (!$post) {
    die("Post introuvable.");
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            box-sizing: border-box;
            text-align: center;
        }

        h1 {
            color: #007BFF;
            margin-bottom: 20px;
        }

        .post-image {
            max-width: 100%;
            border-radius: 10px;
            margin-top: 15px;
        }

        .comment-section {
            margin-top: 30px;
            text-align: left;
        }

        .comment {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .comment p {
            margin: 5px 0;
        }

        .comment strong {
            color: #007BFF;
        }

        form textarea {
            width: 100%;
            height: 80px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            margin-top: 10px;
            resize: none;
            box-sizing: border-box;
        }

        button {
            background-color: #007BFF;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 1em;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        hr {
            border: 0;
            height: 1px;
            background: #ddd;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1><?= htmlspecialchars($post['content']) ?></h1>
        <?php if (!empty($post['image_name'])): ?>
            <img src="uploads/<?= htmlspecialchars($post['image_name']) ?>" alt="Post image" class="post-image">
        <?php endif; ?>

        <div class="comment-section">
            <h2>Commentaires</h2>
            <hr>
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <p><strong><?= htmlspecialchars($comment['username']) ?></strong> :
                        <?= htmlspecialchars($comment['content']) ?>
                    </p>
                </div>
            <?php endforeach; ?>

            <?php if (is_logged_in()): ?>
                <form method="POST" action="create_comment.php">
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <textarea name="content" placeholder="Ajouter un commentaire"></textarea>
                    <button type="submit">Commenter</button>
                </form>
            <?php else: ?>
                <textarea placeholder="Connectez-vous pour commenter" disabled></textarea>
                <button class="disabled" disabled>Commenter</button>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>