<?php

require 'includes/db.php';
require 'includes/auth.php';


if ( $_SESSION['is_admin'] == 1) {

}else{
    header('Location: index.php');
    exit;
}


// Fetch all users and posts
$users = $pdo->query("SELECT id, username, admin FROM users")->fetchAll(PDO::FETCH_ASSOC);
$posts = $pdo->query("SELECT posts.id, posts.content, users.username FROM posts JOIN users ON posts.user_id = users.id")->fetchAll(PDO::FETCH_ASSOC);

// Handle deletions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_user'])) {
        $user_id = (int) $_POST['user_id'];
        delete_user($user_id);
    } elseif (isset($_POST['delete_post'])) {
        $post_id = (int) $_POST['post_id'];
        delete_post($post_id);
    }
}

function delete_user($user_id) {
    global $pdo;
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("DELETE FROM comments WHERE post_id IN (SELECT id FROM posts WHERE user_id = ?)");
        $stmt->execute([$user_id]);

        $stmt = $pdo->prepare("DELETE FROM posts WHERE user_id = ?");
        $stmt->execute([$user_id]);

        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user_id]);

        $pdo->commit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Error deleting user: " . $e->getMessage());
    }
}

function delete_post($post_id) {
    global $pdo;
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("DELETE FROM comments WHERE post_id = ?");
        $stmt->execute([$post_id]);

        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->execute([$post_id]);

        $pdo->commit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Error deleting post: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Page</title>
    <style>
        .admin-badge {
            color: red;
            font-weight: bold;
            margin-left: 5px;
        }
    </style>    
</head>
<body>
<?php if (is_logged_in()): ?> 
    <p>
        Bienvenue, <?= htmlspecialchars($_SESSION['username'] ?? 'Utilisateur') ?> 
        <?php if ($_SESSION['is_admin']): ?>
            <span class="admin-badge">[ADMIN]</span>
        <?php endif; ?>
        <a href="logout.php">Déconnexion</a>
    </p>
    <?php else: ?> 
    <a href="login.php">Connexion/Inscription</a> 
    <?php endif; ?> 
    <h1>Admin Dashboard</h1>
    <h2>Users</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Admin</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= $user['admin'] ? 'Yes' : 'No' ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                    <button type="submit" name="delete_user">Delete User</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Posts</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Content</th>
            <th>Username</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($posts as $post): ?>
        <tr>
            <td><?= htmlspecialchars($post['id']) ?></td>
            <td><?= htmlspecialchars($post['content']) ?></td>
            <td><?= htmlspecialchars($post['username']) ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <button type="submit" name="delete_post">Delete Post</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
