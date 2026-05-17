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
case 'toggle_done':
        toggleDone($pdo, $userId);
        break;

case 'delete_done':
        deleteDoneTasks($pdo, $userId);
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

function toggleDone(PDO $pdo, int $userId): void {
    // Récupérer le JSON envoyé en POST
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'ID de tâche manquant']);
        return;
    }

    $taskId = (int) $data['id'];

    // Lire l'état actuel pour cette tâche appartenant à l'utilisateur
    $stmt = $pdo->prepare(
        "SELECT is_done FROM tasks WHERE id = :id AND user_id = :user_id"
    );
    $stmt->execute([
        ':id'      => $taskId,
        ':user_id' => $userId,
    ]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$task) {
        http_response_code(404);
        echo json_encode(['error' => 'Tâche introuvable']);
        return;
    }

    $newIsDone = $task['is_done'] ? 0 : 1;

    $update = $pdo->prepare(
        "UPDATE tasks SET is_done = :is_done WHERE id = :id AND user_id = :user_id"
    );
    $update->execute([
        ':is_done' => $newIsDone,
        ':id'      => $taskId,
        ':user_id' => $userId,
    ]);

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'id'      => $taskId,
        'is_done' => $newIsDone,
    ]);
}

function deleteDoneTasks(PDO $pdo, int $userId): void {
    $stmt = $pdo->prepare(
        "DELETE FROM tasks WHERE user_id = :user_id AND is_done = 1"
    );
    $stmt->execute([':user_id' => $userId]);

    $deleted = $stmt->rowCount();

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'deleted' => $deleted,
    ]);
}