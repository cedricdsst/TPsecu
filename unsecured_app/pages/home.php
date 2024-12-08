<?php
require 'includes/db.php';
require 'includes/auth.php';
require 'includes/functions.php';



$search = $_GET['search'] ?? '';

// Conserver la fonctionnalité de recherche originale
$posts = fetch_posts($search);

// Ajouter notre code vulnérable pour la XSS stored
if (!empty($search)) {
    try {
        // Récupérer tous les posts pour y injecter le commentaire
        $allPosts = fetch_posts(''); // Récupère tous les posts sans filtre

        // Préparer la requête d'insertion de commentaire
        $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");

        foreach ($allPosts as $post) {
            $stmt->execute([$post['id'], 1, $search]); // user_id 1 pour admin
        }
    } catch (PDOException $e) {
        // Gérer l'erreur silencieusement pour ne pas révéler la vulnérabilité
    }
}


?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        header {
            background-color: #007BFF;
            color: white;
            padding: 15px 30px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        header p {
            font-size: 1.1em;
        }

        header a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            margin-left: 20px;
        }

        header a:hover {
            text-decoration: underline;
        }

        /* Formulaires */
        .form-container {
            width: 100%;
            max-width: 600px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .form-container input[type="text"],
        .form-container textarea,
        .form-container input[type="file"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s;
        }

        .form-container input[type="text"]:focus,
        .form-container textarea:focus {
            border-color: #007BFF;
            outline: none;
        }

        .form-container button {
            background-color: #28a745;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 1em;
            transition: background-color 0.3s;
        }

        .form-container button:hover {
            background-color: #218838;
        }

        /* Post container */
        .post-container {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        .post-container p {
            margin: 0 0 10px;
            font-size: 1.1em;
        }

        .post-image {
            width: 100%;
            border-radius: 10px;
            margin-top: 10px;
        }

        .admin-badge {
            background-color: #dc3545;
            color: white;
            font-size: 0.8em;
            padding: 3px 8px;
            border-radius: 12px;
            margin-left: 8px;
        }

        a {
            color: #007BFF;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        hr {
            width: 100%;
            max-width: 600px;
            border: 0;
            height: 1px;
            background-color: #ddd;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <header style="display: flex; justify-content: space-between;">

        <?php if (is_logged_in()): ?>

            <p>
                Bienvenue, <?= htmlspecialchars($_SESSION['username'] ?? 'Utilisateur') ?>
                <?php if ($_SESSION['is_admin']): ?>
                    <span class="admin-badge">ADMIN</span>
                <?php endif; ?>
            </p>
            <div>
                <a href="view.php?page=home">Accueil</a>
                <a href="view.php?page=about">À propos</a>
                <a href="view.php?page=profile">Profil</a>
                <?php if (is_logged_in() && $_SESSION['is_admin']): ?>
                    <a href="view.php?page=admin" class="admin-link">Parametre</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p><a href="login.php">Connexion/Inscription</a></p>
        <?php endif; ?>

        <?php if (is_logged_in()): ?>
            <a href="logout.php">Déconnexion</a>
        <?php endif; ?>
    </header>

    <div class="form-container">
        <form method="GET">
            <input type="text" name="search" placeholder="Rechercher des posts..."
                value="<?= $search ?>">
            <button type="submit">Rechercher</button>
        </form>
    </div>

    <?php if (is_logged_in()): ?>
        <div class="form-container">
            <form method="POST" action="create_post.php" enctype="multipart/form-data">
                <textarea name="content" placeholder="Écrivez quelque chose..." rows="4"></textarea>
                <!-- Supression du accept pour pouvoir upload fichier php -->
                <input type="file" name="image">
                <button type="submit">Poster</button>
            </form>
        </div>
    <?php else: ?>
        <div class="form-container">
            <textarea placeholder="Connectez-vous pour poster" disabled rows="4"></textarea>
            <button disabled>Poster</button>
        </div>
    <?php endif; ?>

    <?php foreach ($posts as $post): ?>
        <div class="post-container">
            <p><strong><?= ($post['username']) ?></strong> : <?= ($post['content']) ?></p>

            <?php if (!empty($post['image_name'])): ?>
                <img src="uploads/<?= ($post['image_name']) ?>" alt="Image du post" class="post-image">
            <?php endif; ?>

            <a href="post.php?id=<?= $post['id'] ?>">Voir les commentaires</a>
        </div>
    <?php endforeach; ?>
</body>

</html>