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

        <button id="open-task-modal">Nouvelle tâche</button>

        <div id="task-modal" class="modal" aria-hidden="true">
            <div class="modal-content">
                <h2>Nouvelle tâche</h2>
                <form id="task-form">
                    <div>
                        <label for="task-title">Titre</label><br>
                        <input type="text" id="task-title" name="title" required>
                    </div>

                    <div>
                        <label for="task-description">Description (optionnelle)</label><br>
                     <textarea id="task-description" name="description"></textarea>
                    </div>

                    <div>
                        <label for="task-due-date">Date d’échéance</label><br>
                        <input type="date" id="task-due-date" name="due_date">
                    </div>

                    <div>
                        <label for="task-priority">Priorité</label><br>
                        <select id="task-priority" name="priority">
                            <option value="low">Basse</option>
                            <option value="normal" selected>Normale</option>
                            <option value="high">Haute</option>
                        </select>
                    </div>

                    <button type="submit">Ajouter</button>
                    <button type="button" id="close-task-modal">Annuler</button>
                </form>
            </div>
        </div>


        
        <section id="tasks-filters">
            <label for="filter-status">Statut :</label>
            <select id="filter-status">
                <option value="all">Toutes</option>
                <option value="active">En cours</option>
                <option value="done">Terminées</option>
            </select>

            <label for="filter-priority">Priorité :</label>
            <select id="filter-priority">
                <option value="all">Toutes</option>
                <option value="low">Basse</option>
                <option value="normal">Normale</option>
                <option value="high">Haute</option>
            </select>

            <label for="sort-by">Trier par :</label>
            <select id="sort-by">
            <option value="created_at">Date de création</option>
                <option value="due_date">Date d'échéance</option>
                <option value="priority">Priorité</option>
            </select>
            <button id="delete-done-btn">Supprimer les tâches terminées</button>
        </section>
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
