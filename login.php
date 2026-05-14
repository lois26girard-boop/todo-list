<?php
require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$pdo = getPDO();
$_SESSION['user_id'] = $user['id'];


$error = '';

// Traitement du formulaire (on remplira cette partie après)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //récupérer email + mot de passe ;
    //chercher utilisateur en base ;
    //vérifier mdp ; $_SESSION['user_id'] puis redirection si OK, sinon remplir var $error avec l'erreur
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Connexion - Todo List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <main>
        <h1>Connexion</h1>

        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <form method="post" action="login.php">
            <div>
                <label for="email">Email</label><br>
                <input type="email" name="email" id="email" required>
            </div>

            <div>
                <label for="password">Mot de passe</label><br>
                <input type="password" name="password" id="password" required>
            </div>

            <button type="submit">Se connecter</button>
        </form>
    </main>

</body>
</html>