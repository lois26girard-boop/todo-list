<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$pdo = getPDO();
$_SESSION['user_id'] = $user['id'];


$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Identifiants incorrects.';
    } else {
        $stmt = $pdo->prepare('SELECT id, password_hash FROM users WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Identifiants incorrects.';
        }
    }
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