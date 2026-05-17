-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2026 at 11:42 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gestion_todo`
--

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `priority` enum('low','normal','high') NOT NULL DEFAULT 'normal',
  `is_done` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `user_id`, `title`, `description`, `due_date`, `priority`, `is_done`, `created_at`) VALUES
(1, 1, 'Acheter du lait', 'Passer au supermarché en rentrant du travail', '2026-05-17', 'normal', 0, '2026-05-16 15:08:27'),
(2, 1, 'Réviser PHP', 'Relire le code du TP todo et tester l’API', '2026-05-20', 'high', 0, '2026-05-16 15:08:27'),
(4, 1, 'Payer la facture d’électricité', 'Vérifier le montant avant paiement', '2026-05-10', 'high', 0, '2026-05-16 15:08:27'),
(5, 1, 'Appeler le dentiste', 'Prendre rendez-vous pour contrôle', '2026-05-25', 'normal', 0, '2026-05-16 15:08:27'),
(7, 2, 'test', NULL, NULL, 'normal', 0, '2026-05-17 23:13:35'),
(8, 1, 'Répondre aux emails', 'Faire le tri dans la boîte de réception et répondre aux messages importants', '2026-05-26', 'normal', 0, '2026-05-17 20:00:00'),
(9, 1, 'Planifier la semaine', 'Lister les tâches prioritaires pour la semaine prochaine', '2026-05-27', 'high', 0, '2026-05-17 20:05:00'),
(10, 1, 'Faire une sauvegarde du code', 'Sauvegarder le projet Todo List sur Git ou sur une clé USB', '2026-05-28', 'normal', 0, '2026-05-17 20:10:00'),
(11, 1, 'Lire un article sur PHP', 'Choisir un article sur les bonnes pratiques PDO', '2026-05-29', 'low', 0, '2026-05-17 20:15:00'),
(12, 1, 'Tester les messages d’erreur', 'Vérifier les cas de login/inscription avec mauvais mot de passe', '2026-05-30', 'normal', 0, '2026-05-17 20:20:00'),
(13, 1, 'Nettoyer la base de données', 'Supprimer les données de test inutiles dans gestion_todo', '2026-05-31', 'low', 0, '2026-05-17 20:25:00'),
(14, 1, 'Ajouter des commentaires au code', 'Documenter les parties importantes dans index.php et login.php', '2026-06-01', 'normal', 0, '2026-05-17 20:30:00'),
(15, 1, 'Tester la déconnexion', 'S’assurer que logout.php détruit bien la session', '2026-06-02', 'high', 0, '2026-05-17 20:35:00'),
(16, 1, 'Vérifier la sécurité', 'Contrôler que les pages protégées redirigent bien vers login.php', '2026-06-03', 'high', 0, '2026-05-17 20:40:00'),
(17, 1, 'Essayer un autre navigateur', 'Tester l’application sur Chrome, Firefox et Edge', '2026-06-04', 'low', 0, '2026-05-17 20:45:00'),
(18, 1, 'Améliorer le CSS', 'Ajuster les marges et la mise en page des tâches', '2026-06-05', 'normal', 0, '2026-05-17 20:50:00'),
(19, 1, 'Tester la pagination', 'Naviguer entre les pages 1, 2, 3 et vérifier le nombre de tâches', '2026-06-06', 'high', 0, '2026-05-17 20:55:00'),
(20, 1, 'Essayer des tâches terminées', 'Marquer quelques tâches comme faites pour voir le style .task-done', '2026-06-07', 'normal', 0, '2026-05-17 21:00:00'),
(21, 1, 'Créer une tâche en retard', 'Ajouter une tâche avec due_date dans le passé pour tester .task-late', '2026-05-10', 'high', 0, '2026-05-17 21:05:00'),
(22, 1, 'Relire le code', 'Parcourir tous les fichiers PHP pour repérer les améliorations possibles', '2026-06-08', 'normal', 0, '2026-05-17 21:10:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password_hash`, `created_at`) VALUES
(1, 'trucbox@gmail.com', '$2y$10$y.i5l3n1KpbwJ9N.gwetQuLssljzgvWNg1gGEIf9ooNskYbosNCdK', '2026-05-13 23:46:29'),
(2, 'compteultrasecret@gmail.com', '$2y$10$3QaKnIS27PzYL.Ydk01Tk.Oe5uCjd3T1K7uGi9cuA/HVhnmlGXSAO', '2026-05-17 23:12:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tasks_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `fk_tasks_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
