<?php
require 'includes/db.php';
require 'includes/auth.php';

if (!$_SESSION['is_admin']) {
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

function delete_user($user_id)
{
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
        die("Erreur lors de la suppression de l'utilisateur : " . $e->getMessage());
    }
}

function delete_post($post_id)
{
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
        die("Erreur lors de la suppression du post : " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fdf6e3;
            color: #5d4037;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        h1,
        h2 {
            color: #5d4037;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
            box-sizing: border-box;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #5d4037;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        button {
            background-color: #dc3545;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        a {
            color: #c17817;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        a:hover {
            color: #5d4037;
            transform: translateY(-2px);
        }

        button:hover {
            background-color: #c82333;
        }

        .logout {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <p>Bienvenue, <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?>
            <span style="color:red; font-weight:bold;">[ADMIN]</span>
            <a class="logout" href="logout.php" style="margin-left:20px;">Déconnexion</a>
        </p>

        <h2>Utilisateurs</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nom d'utilisateur</th>
                <th>Admin</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= $user['admin'] ? 'Oui' : 'Non' ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <button type="submit" name="delete_user">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h2>Publications</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Contenu</th>
                <th>Utilisateur</th>
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
                            <button type="submit" name="delete_post">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>

</html>