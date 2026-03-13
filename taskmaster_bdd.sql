-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 13 mars 2026 à 22:17
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `taskmaster_bdd`
--
CREATE DATABASE IF NOT EXISTS `taskmaster_bdd` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `taskmaster_bdd`;

-- --------------------------------------------------------

--
-- Structure de la table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('todo','in_progress','done') NOT NULL DEFAULT 'todo',
  `priority` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `status`, `priority`, `created_at`, `updated_at`) VALUES
(1, 'Réviser les APIs REST', 'Lire le cours et faire les exercices', 'in_progress', 1, '2026-03-13 15:19:27', '2026-03-13 15:19:27'),
(2, 'Configurer l\'environnement', 'Installer XAMPP et créer la BDD', 'done', 3, '2026-03-13 15:19:27', '2026-03-13 15:19:27'),
(3, 'Créer le routeur PHP', NULL, 'todo', 2, '2026-03-13 15:19:27', '2026-03-13 15:19:27');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
