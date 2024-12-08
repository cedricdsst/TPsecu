<?php
require 'includes/auth.php';

// Vérifie si l'utilisateur est connecté
if (!is_logged_in()) {
    header('Location: login.php'); // Redirige vers la page de connexion si non connecté
    exit;
}

// Récupère les informations utilisateur depuis la session
$username = $_SESSION['username'] ?? 'Utilisateur inconnu';
$email = $_SESSION['email'] ?? 'Email non défini';
$isAdmin = $_SESSION['is_admin'] ?? false;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .profile-container {
            width: 100%;
            max-width: 600px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .profile-container h1 {
            font-size: 1.5em;
            margin-bottom: 20px;
            text-align: center;
        }

        .profile-container p {
            font-size: 1.1em;
            margin-bottom: 10px;
        }

        .profile-container .admin-badge {
            background-color: #dc3545;
            color: white;
            font-size: 0.9em;
            padding: 5px 10px;
            border-radius: 12px;
        }

        a {
            color: #007BFF;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="profile-container">
        <h1>Profil de l'utilisateur</h1>
        <p><strong>Nom d'utilisateur :</strong> <?= htmlspecialchars($username) ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($email) ?></p>
        <p>
            <strong>Statut :</strong>
            <?= $isAdmin ? '<span class="admin-badge">Administrateur</span>' : 'Utilisateur' ?>
        </p>
        <hr>
        <p><a href="index.php">Retour à l'accueil</a></p>
    </div>
</body>

</html>