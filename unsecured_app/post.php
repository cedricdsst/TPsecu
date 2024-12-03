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
<html>
<head>
    <title>Post</title>
</head>
<body>
    <h1><?= htmlspecialchars($post['content']) ?></h1>
    <hr>
    <?php foreach ($comments as $comment): ?>
    <div>
        <p><strong><?= htmlspecialchars($comment['username']) ?></strong>: <?= htmlspecialchars($comment['content']) ?></p>
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
        <button disabled>Commenter</button>
    <?php endif; ?>
</body>
</html>
