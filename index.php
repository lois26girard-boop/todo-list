<?php
require_once 'config.php';
$pdo = getPDO();


session_start();
if (!isset($_SESSION['user_id'])) {
    // redirection vers login
}
$userId = $_SESSION['user_id'];