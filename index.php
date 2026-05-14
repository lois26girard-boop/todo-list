<?php
require_once 'config.php';
$pdo = getPDO();


session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$userId = $_SESSION['user_id'];