<?php
require_once 'config.php';
$pdo = getPDO();

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$userId = $_SESSION['user_id'];

$action = $_GET['action'] ?? 'list';

switch ($action) {
    case 'list':
        //ajouter paramètres de pagination / filtres
        listTasks($pdo, $userId);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Action inconnue']);
        break;
}

function listTasks(PDO $pdo, int $userId): void {

    //mettre LIMIT, OFFSET, filtres...

    $sql = "SELECT id, title, description, due_date, priority, is_done, created_at
            FROM tasks
            WHERE user_id = :user_id
            ORDER BY created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $userId]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode($tasks);
}