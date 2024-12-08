<?php
require 'includes/db.php';
require 'includes/auth.php';
require 'includes/functions.php';

$search = $_GET['search'] ?? '';
$posts = fetch_posts($search);
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
            background-color: #fdf6e3;
            background-image: linear-gradient(135deg, #fdf6e3 0%, #f6e6cb 100%);
            color: #5d4037;
            min-height: 100vh;
            padding: 20px;
        }

        header {
            background: linear-gradient(135deg, #c17817, #8d4004);
            color: white;
            padding: 15px 30px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(193, 120, 23, 0.2);
        }

        header p {
            font-size: 1.1em;
        }

        header a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            margin-left: 20px;
            transition: all 0.3s ease;
        }

        header a:hover {
            color: #ffe0b2;
            transform: translateY(-2px);
        }

        /* Container principal pour la grille */
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Conteneur des formulaires */
        .forms-section {
            display: flex;
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        /* Formulaires */
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(139, 69, 19, 0.1);
            flex: 1;
            border: 1px solid #deb887;
        }

        .form-container input[type="text"],
        .form-container textarea,
        .form-container input[type="file"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 2px solid #deb887;
            border-radius: 10px;
            transition: all 0.3s ease;
            background-color: #fefaf4;
            color: #5d4037;
        }

        .form-container input[type="text"]:focus,
        .form-container textarea:focus {
            border-color: #c17817;
            outline: none;
            box-shadow: 0 0 0 3px rgba(193, 120, 23, 0.1);
        }

        .form-container button {
            background: linear-gradient(135deg, #c17817, #8d4004);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            width: 100%;
            font-size: 1em;
            transition: all 0.3s ease;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-container button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(193, 120, 23, 0.2);
            background: linear-gradient(135deg, #d68b1c, #a54d05);
        }

        .form-container button:disabled {
            background: #d7ccc8;
            cursor: not-allowed;
            transform: none;
        }

        /* Post container */
        .post-container {
            background-color: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(139, 69, 19, 0.1);
            transition: transform 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid #deb887;
        }

        .post-container:hover {
            transform: translateY(-5px);
            border-color: #c17817;
        }

        .post-container p {
            margin: 0 0 15px;
            font-size: 1.1em;
            line-height: 1.6;
        }

        .post-image {
            width: 100%;
            border-radius: 10px;
            margin: 10px 0;
            object-fit: cover;
            height: 200px;
        }

        .admin-badge {
            background: linear-gradient(135deg, #8d4004, #5d2a01);
            color: white;
            font-size: 0.8em;
            padding: 5px 10px;
            border-radius: 20px;
            margin-left: 8px;
            font-weight: bold;
        }

        .post-link {
            color: #c17817;
            text-decoration: none;
            font-weight: bold;
            margin-top: auto;
            transition: all 0.3s ease;
        }

        .post-link:hover {
            color: #8d4004;
            text-decoration: none;
        }

        /* Grille des posts */
        .posts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            width: 100%;
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

        /* Media Queries */
        @media (max-width: 768px) {
            .posts-grid {
                grid-template-columns: 1fr;
            }
            
            .forms-section {
                flex-direction: column;
            }
            
            body {
                padding: 10px;
            }
            
            header {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
        }
    </style>
</head>

<body>
    <header>
        <div>
            <?php if (is_logged_in()): ?>
                <p>
                    Bienvenue, <?= htmlspecialchars($_SESSION['username'] ?? 'Utilisateur') ?>
                    <?php if ($_SESSION['is_admin']): ?>
                        <span class="admin-badge">ADMIN</span>
                    <?php endif; ?>
                </p>
            <?php else: ?>
                <p><a href="login.php">Connexion/Inscription</a></p>
            <?php endif; ?>
        </div>
        <?php if (is_logged_in()): ?>
            <a href="logout.php">Déconnexion</a>
        <?php endif; ?>
    </header>

    <div class="main-container">
        <div class="forms-section">
            <div class="form-container">
                <form method="GET">
                    <input type="text" name="search" placeholder="Rechercher des posts..."
                        value="<?= htmlspecialchars($search) ?>">
                    <button type="submit">Rechercher</button>
                </form>
            </div>

            <div class="form-container">
                <?php if (is_logged_in()): ?>
                    <form method="POST" action="create_post.php" enctype="multipart/form-data">
                        <textarea name="content" placeholder="Écrivez quelque chose..." rows="4"></textarea>
                        <input type="file" name="image" accept="image/jpeg,image/png,image/gif">
                        <button type="submit">Poster</button>
                    </form>
                <?php else: ?>
                    <textarea placeholder="Connectez-vous pour poster" disabled rows="4"></textarea>
                    <button disabled>Poster</button>
                <?php endif; ?>
            </div>
        </div>

        <div class="posts-grid">
            <?php foreach ($posts as $post): ?>
                <div class="post-container">
                    <p><strong><?= htmlspecialchars($post['username']) ?></strong> : <?= htmlspecialchars($post['content']) ?></p>

                    <?php if (!empty($post['image_name'])): ?>
                        <img src="uploads/<?= htmlspecialchars($post['image_name']) ?>" alt="Image du post" class="post-image">
                    <?php endif; ?>

                    <a href="post.php?id=<?= $post['id'] ?>" class="post-link">Voir les commentaires</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>