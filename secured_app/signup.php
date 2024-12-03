<?php
require 'includes/db.php';
require 'includes/auth.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($password) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        if (signup($username, $password)) {
            $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        } else {
            $error = "Une erreur est survenue ou l'username est déjà utilisé.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
</head>
<body>
    <h1>Inscription</h1>
    <?php if ($error): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p style="color: green;"><?= htmlspecialchars($success) ?></p>
        <p><a href="login.php">Se connecter</a></p>
    <?php else: ?>
        <form method="POST">
            <label>Email :</label><br>
            <input type="text" name="username" required><br>
            <label>Mot de passe :</label><br>
            <input type="password" name="password" required><br>
            <label>Confirmer le mot de passe :</label><br>
            <input type="password" name="confirm_password" required><br>
            <button type="submit">S'inscrire</button>
        </form>
        <p>Déjà un compte ? <a href="login.php">Se connecter</a></p>
    <?php endif; ?>
</body>
</html>
