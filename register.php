<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$pdo = getPDO();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if ($email === '' || $password === '' || $password_confirm === '') {
        $error = 'Tous les champs sont obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email invalide.';
    } elseif ($password !== $password_confirm) {
        $error = 'Les mots de passe ne correspondent pas.';
    } else {

        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $error = 'Un compte existe déjà avec cet email.';
        } else {

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                'INSERT INTO users (email, password_hash) VALUES (:email, :password_hash)'
            );

            try {
                $stmt->execute([
                    ':email'         => $email,
                    ':password_hash' => $passwordHash,
                ]);
                header('Location: login.php');
                exit;
            } catch (PDOException $e) {
                $error = 'Impossible de créer le compte (email déjà utilisé).';
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Inscription - Todo List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <main>
        <h1>Inscription</h1>

        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <p class="success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <form method="post" action="register.php">
            <div>
                <label for="email">Email</label><br>
                <input type="email" name="email" id="email" required>
            </div>

            <div>
                <label for="password">Mot de passe</label><br>
                <input type="password" name="password" id="password" required>
            </div>

            <div>
                <label for="password_confirm">Confirmer le mot de passe</label><br>
                <input type="password" name="password_confirm" id="password_confirm" required>
            </div>

            <button type="submit">Créer mon compte</button>
        </form>

        <p>Déjà inscrit ? <a href="login.php">Se connecter</a></p>
    </main>

</body>
</html>