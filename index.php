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
<p>Ca fonctionne !</p>