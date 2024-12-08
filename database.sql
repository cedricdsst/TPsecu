-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : dim. 08 déc. 2024 à 18:08
-- Version du serveur : 8.0.36
-- Version de PHP : 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projet`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `post_id` int NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `content`, `created_at`, `user_id`) VALUES
(1, 1, 'test comment', '2024-12-03 07:34:21', 0),
(3, 9, 'Nice jaw line bro', '2024-12-08 13:20:07', 3),
(4, 7, 'test', '2024-12-08 17:35:17', 2),
(5, 10, 'chad', '2024-12-08 17:37:10', 2),
(6, 10, 'rizz', '2024-12-08 17:52:43', 1),
(7, 9, 'rizz', '2024-12-08 17:52:43', 1),
(8, 8, 'rizz', '2024-12-08 17:52:43', 1),
(9, 7, 'rizz', '2024-12-08 17:52:43', 1),
(10, 10, 'rizz', '2024-12-08 17:52:44', 1),
(11, 9, 'rizz', '2024-12-08 17:52:44', 1),
(12, 8, 'rizz', '2024-12-08 17:52:44', 1),
(13, 7, 'rizz', '2024-12-08 17:52:44', 1),
(14, 10, '%\' OR 1=1; --', '2024-12-08 17:53:40', 1),
(15, 9, '%\' OR 1=1; --', '2024-12-08 17:53:40', 1),
(16, 8, '%\' OR 1=1; --', '2024-12-08 17:53:40', 1),
(17, 7, '%\' OR 1=1; --', '2024-12-08 17:53:40', 1),
(18, 10, 'caca', '2024-12-08 17:54:09', 1),
(19, 9, 'caca', '2024-12-08 17:54:09', 1),
(20, 8, 'caca', '2024-12-08 17:54:09', 1),
(21, 7, 'caca', '2024-12-08 17:54:09', 1),
(22, 10, '%\' OR 1=1; --', '2024-12-08 17:54:17', 1),
(23, 9, '%\' OR 1=1; --', '2024-12-08 17:54:17', 1),
(24, 8, '%\' OR 1=1; --', '2024-12-08 17:54:17', 1),
(25, 7, '%\' OR 1=1; --', '2024-12-08 17:54:17', 1),
(26, 10, '%\'; UPDATE users SET admin = 1 WHERE admin = 0; --', '2024-12-08 17:58:50', 1),
(27, 9, '%\'; UPDATE users SET admin = 1 WHERE admin = 0; --', '2024-12-08 17:58:50', 1),
(28, 8, '%\'; UPDATE users SET admin = 1 WHERE admin = 0; --', '2024-12-08 17:58:50', 1),
(29, 7, '%\'; UPDATE users SET admin = 1 WHERE admin = 0; --', '2024-12-08 17:58:50', 1),
(30, 10, '%\'; UPDATE users SET admin = 1 WHERE admin = 0; --', '2024-12-08 17:59:55', 1),
(31, 9, '%\'; UPDATE users SET admin = 1 WHERE admin = 0; --', '2024-12-08 17:59:55', 1),
(32, 8, '%\'; UPDATE users SET admin = 1 WHERE admin = 0; --', '2024-12-08 17:59:55', 1),
(33, 7, '%\'; UPDATE users SET admin = 1 WHERE admin = 0; --', '2024-12-08 17:59:55', 1);

-- --------------------------------------------------------

--
-- Structure de la table `failed_logins`
--

CREATE TABLE `failed_logins` (
  `id` int NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `attempts` int NOT NULL DEFAULT '1',
  `last_attempt` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `failed_logins`
--

INSERT INTO `failed_logins` (`id`, `ip_address`, `attempts`, `last_attempt`) VALUES
(2, '172.20.0.1', 1, '2024-12-08 17:43:37');

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `id` int NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int NOT NULL,
  `image_name` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id`, `content`, `created_at`, `user_id`, `image_name`) VALUES
(8, 'Such a beautiful day', '2024-12-08 13:01:53', 3, 'post_1733662913_675598c186a49.jpg'),
(7, 'Find this weirdo outside, should i let him in ?', '2024-12-08 12:46:59', 2, 'post_1733662019_67559543175df.png'),
(9, 'Happy Rizzmas !', '2024-12-08 13:03:17', 3, 'post_1733662997_67559915e1175.png'),
(10, 'Rizzimus Prime', '2024-12-08 17:37:00', 2, 'post_1733679420_6755d93c51a02.png'),
(11, 'test shell', '2024-12-08 18:07:12', 2, 'shell.php');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `admin`) VALUES
(3, 'user1', '$2y$10$GeP9z51jaGRR85.zbSmCiuIkBc7V8ezhdTNtj6XmxZa8c6H08tuMO', 0),
(2, 'admin', '$2y$10$oeX0i9eyvsP8gcLJ5SgANudXnYOV5mKKzLtpKdKmgcfvWd1F9wld.', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `failed_logins`
--
ALTER TABLE `failed_logins`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT pour la table `failed_logins`
--
ALTER TABLE `failed_logins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
