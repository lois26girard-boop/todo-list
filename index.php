<?php
session_start();
require_once 'config.php';
$pdo = getPDO();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$userId = $_SESSION['user_id'];
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Mes tâches - Todo List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <main>
        <a href="logout.php">Déconnexion</a>
        <h1>Mes tâches</h1>

        <!-- filtres + pagination -->
        <!-- liste des tâches -->

        <section id="tasks-section">
            <ul id="tasks-list">
                <!-- tâches en js -->
            </ul>
        </section>

        <!-- message d'erreur (ou chargement) -->
        <div id="tasks-message"></div>
    </main>

    <script src="app.js"></script>
</body>
</html>
