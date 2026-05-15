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

<a href="logout.php">Déconnexion</a>
<p>Ca fonctionne !</p>