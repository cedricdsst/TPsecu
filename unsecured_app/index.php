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
        <form method="POST" action="create_post.php"> 
            <textarea name="content" placeholder="Écrivez quelque chose..."></textarea> 
            <button type="submit">Poster</button> 
        </form> 
    <?php else: ?> 
        <textarea placeholder="Connectez-vous pour poster" disabled></textarea> 
        <button disabled>Poster</button> 
    <?php endif; ?> 
    
    <hr> 
    <?php foreach ($posts as $post): ?> 
    <div> 
        <p><strong><?= htmlspecialchars($post['username']) ?></strong>: <?= htmlspecialchars($post['content']) ?></p> 
        <a href="post.php?id=<?= $post['id'] ?>">Voir les commentaires</a> 
    </div> 
    <?php endforeach; ?> 
 
</body> 
</html>