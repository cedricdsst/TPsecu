<?php 
require 'includes/db.php'; 
require 'includes/auth.php'; 
require 'includes/functions.php'; 
 
$search = $_GET['search'] ?? ''; 
$posts = fetch_posts($search); 
?> 
<!DOCTYPE html> 
<html> 
<head> 
    <title>Accueil</title>
    <style>
        .admin-badge {
            color: red;
            font-weight: bold;
            margin-left: 5px;
        }
        .post-image {
            max-width: 300px;
            max-height: 300px;
            object-fit: cover;
            margin-top: 10px;
        }
        .post-container {
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
    </style>
</head> 
<body> 
    <header> 
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
    </header> 
    
    <form method="GET"> 
        <input type="text" name="search" placeholder="Recherche" value="<?= htmlspecialchars($search) ?>"> 
        <button type="submit">Rechercher</button> 
    </form> 
    
    <?php if (is_logged_in()): ?> 
        <form method="POST" action="create_post.php" enctype="multipart/form-data"> 
            <textarea name="content" placeholder="Écrivez quelque chose..."></textarea> 
            <input type="file" name="image" accept="image/jpeg,image/png,image/gif">
            <button type="submit">Poster</button> 
        </form> 
    <?php else: ?> 
        <textarea placeholder="Connectez-vous pour poster" disabled></textarea> 
        <button disabled>Poster</button> 
    <?php endif; ?> 
    
    <hr> 
    <?php foreach ($posts as $post): ?> 
    <div class="post-container"> 
        <p><strong><?= htmlspecialchars($post['username']) ?></strong>: <?= htmlspecialchars($post['content']) ?></p> 
        
        <?php if (!empty($post['image_name'])): ?>
            <img src="uploads/<?= htmlspecialchars($post['image_name']) ?>" 
                 alt="Post image" 
                 class="post-image">
        <?php endif; ?>
        
        <a href="post.php?id=<?= $post['id'] ?>">Voir les commentaires</a> 
    </div> 
    <?php endforeach; ?> 
 
</body> 
</html>