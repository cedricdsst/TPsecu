<?php
require 'includes/db.php';
require 'includes/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
   

    if (login($username, $password)) {
        header('Location: index.php');
        exit;
    } else {
        $error = "username ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
</head>
<body>
    <h1>Connexion</h1>
    <?php if ($error): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST">
        <label>Username :</label><br>
        <input type="text" name="username" required><br>
        <label>Mot de passe :</label><br>
        <input type="password" name="password" required><br>
        <button type="submit">Se connecter</button>
    </form>
    <p>Pas encore de compte ? <a href="signup.php">S'inscrire</a></p>
</body>
</html>
