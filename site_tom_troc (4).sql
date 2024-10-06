-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 05 oct. 2024 à 12:52
-- Version du serveur : 8.3.0
-- Version de PHP : 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `site_tom_troc`
--

-- --------------------------------------------------------

--
-- Structure de la table `book`
--

DROP TABLE IF EXISTS `book`;
CREATE TABLE IF NOT EXISTS `book` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `available` tinyint(1) NOT NULL,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `book`
--

INSERT INTO `book` (`id`, `title`, `author`, `img`, `description`, `createdAt`, `updatedAt`, `available`, `user_id`) VALUES
(1, 'Esther', 'Gabriel Garcia Marquez', '/assets/img/books/Esther.png', 'A story that chronicles several generations of the Buendía family.', '2024-08-05 10:00:00', NULL, 0, 9),
(2, 'The Kinfolk Table', 'Jane Austen', '/assets/img/books/The Kinfolk Table.png', 'A romantic novel that charts the emotional development of the protagonist Elizabeth Bennet.', '2024-08-05 10:00:00', NULL, 1, 9),
(3, 'Wabi Sabi', 'Mark Twain', '/assets/img/books/Wabi Sabi.png', 'A novel about a young boy who travels down the Mississippi River on a raft with a runaway slave.', '2024-08-05 10:00:00', NULL, 1, 3),
(4, 'Milk & honey', 'Haruki Murakami', '/assets/img/books/Milk & honey.png', 'A metaphysical novel that blends reality and fantasy, featuring two distinct, yet interrelated plots.', '2024-08-05 10:00:00', NULL, 1, 4),
(5, 'Delight!', 'Chimamanda Ngozi Adichie', '/assets/img/books/Delight!.png', 'A story about a young Nigerian woman who moves to the United States for university.', '2024-08-05 10:00:00', NULL, 1, 5);

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `content` text NOT NULL,
  `is_read` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `message`
--

INSERT INTO `message` (`id`, `sender_id`, `receiver_id`, `content`, `is_read`, `created_at`) VALUES
(11, 11, 2, 'Salut ! Comment vas-tu ?', 0, '2024-10-04 08:30:00'),
(9, 9, 11, 'Je vais bien, merci ! Et toi ?', 1, '2024-10-04 08:35:00'),
(3, 11, 3, 'Salut, tu as vu mon dernier livre ?', 0, '2024-10-04 09:00:00'),
(4, 3, 1, 'Oui, il a l’air intéressant !', 1, '2024-10-04 09:10:00'),
(5, 9, 1, 'On se voit ce week-end ?', 0, '2024-10-04 10:00:00'),
(6, 1, 2, 'Oui, avec plaisir !', 1, '2024-10-04 10:05:00');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) NOT NULL,
  `profilePicture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `role` enum('user','admin','moderator') DEFAULT 'user',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `profilePicture`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'johndoe', 'john.doe@example.com', '482c811da5d5b4bc6d497ffa98491e38', '/assets/img/users/johndoe.jpg', 'user', 1, '2024-08-13 16:01:14', '2024-08-21 11:16:00'),
(2, 'janedoe', 'jane.doe@example.com', '96b33694c4bb7dbd07391e0be54745fb', '/assets/img/users/janedoe.png', 'admin', 1, '2024-08-13 16:01:14', '2024-08-20 14:30:00'),
(3, 'marksmith', 'mark.smith@example.com', '7d347cf0ee68174a3588f6cba31b8a67', '/assets/img/users/marksmith.png', 'moderator', 1, '2024-08-13 16:01:14', '2024-08-20 14:30:00'),
(4, 'emilyjones', 'emily.jones@example.com', '34819d7beeabb9260a5c854bc85b3e44', '/assets/img/users/emilyjones.png', 'user', 1, '2024-08-13 16:01:14', '2024-08-20 14:30:00'),
(5, 'davidsmith', 'david.smith@example.com', 'b0439fae31f8cbba6294af86234d5a28', '/assets/img/users/davidsmith.png', 'user', 0, '2024-08-13 16:01:14', '2024-08-20 14:30:00'),
(6, 'Emilie', 'Emilie@free.fr', '$2y$10$F7Hpb2Jvhuey4waGsAgUbeoSY3TXTO.Pne2RP9oovkAQwMbJqPYbO', '', 'user', 1, '2024-08-23 11:54:56', '2024-08-23 13:54:56'),
(7, 'nat123@free', 'nat123@free', '$2y$10$ZGbesW7apJ68YZ9Kd24zH.0hPXM8DXWppkn4Ngb.LO/7pBay5asQ6', '', 'user', 1, '2024-09-14 10:24:04', '2024-09-14 12:24:04'),
(8, 'bb', 'nat0123@free', '$2y$10$TdbI0FPsxYRNe5UJzR/D2Oj60cKmr0i90Rm5nBIwzX3IjklpQUBFu', '', 'user', 1, '2024-09-14 10:25:20', '2024-09-14 12:25:20'),
(9, 'aa', '1230@free.fr', '$2y$10$DbCjgo/agVyD0Wrk1Mx85.SeLZJS36H7wNF/21H1HwAuN5reYUK1K', '/assets/img/users/profile_66f7d4c4831c74.39756367.jpeg', 'user', 1, '2024-09-14 10:28:05', '2024-09-28 12:04:52'),
(10, 'toto', '020202@gh.fr', '$2y$10$DAMZC/jTEvvcyp.ckdWE4OXgC2kYOjkwN2eUmpiTmOc8tNSNkT.Ia', '/assets/img/users/profile_670127e43f3307.46378257.jpeg', 'user', 1, '2024-09-21 14:34:54', '2024-10-05 14:50:50'),
(11, 'Administrateur', '020202@gh.fr', '$2y$10$DAMZC/jTEvvcyp.ckdWE4OXgC2kYOjkwN2eUmpiTmOc8tNSNkT.Ia', '', 'user', 1, '2024-09-21 14:34:54', '2024-09-21 14:34:54'),
(12, 'popo', '0212345@free.fr', '$2y$10$YfP5ClzmeWsu/COEO0vPGexg4NCuUS8ia8xbrvzC83XSQVe/pQLRe', '', 'user', 1, '2024-09-21 14:38:39', '2024-09-21 14:38:39'),
(13, 'popo', '0212345@free.fr', '$2y$10$YfP5ClzmeWsu/COEO0vPGexg4NCuUS8ia8xbrvzC83XSQVe/pQLRe', '', 'user', 1, '2024-09-21 14:38:39', '2024-09-21 14:38:39'),
(14, 'oioi', '741@fre.fr', '$2y$10$sLBJz3dRYib.st260IXwLukeICw2lM7U/xe89sCraqR719WIywT/.', '', 'user', 1, '2024-09-21 15:26:12', '2024-09-21 15:26:12'),
(15, 'oioi', '741@fre.fr', '$2y$10$sLBJz3dRYib.st260IXwLukeICw2lM7U/xe89sCraqR719WIywT/.', '', 'user', 1, '2024-09-21 15:26:12', '2024-09-21 15:26:12'),
(16, 'totot', '123456789@gm.fr', '$2y$10$b87ZUspDMkq7NIaSqmTGR.kph4SkhG.3rBW2SubnEiDZ28Gc.djj2', '', 'user', 1, '2024-09-21 16:00:38', '2024-09-21 16:00:38'),
(17, 'totot', '123456789@gm.fr', '$2y$10$b87ZUspDMkq7NIaSqmTGR.kph4SkhG.3rBW2SubnEiDZ28Gc.djj2', '', 'user', 1, '2024-09-21 16:00:38', '2024-09-21 16:00:38'),
(18, 'ththt', '00000@fr.fr', '$2y$10$6RmhatlQIhuVQhKnTsNxcedFdrn27fwDPLnPgY.0ZwiR4QwmF80mS', '', 'user', 1, '2024-09-22 14:27:20', '2024-09-22 14:27:20'),
(19, 'ththt', '00000@fr.fr', '$2y$10$6RmhatlQIhuVQhKnTsNxcedFdrn27fwDPLnPgY.0ZwiR4QwmF80mS', '', 'user', 1, '2024-09-22 14:27:20', '2024-09-22 14:27:20'),
(20, 'titkttk', '2222@fr.fr', '$2y$10$159n5rpHKQGqt1K529c1BeH/GMTP9eOBSFaziNZIYfrfQweHJk2j.', '', 'user', 1, '2024-09-22 14:38:00', '2024-09-22 14:38:00'),
(21, 'titkttk', '2222@fr.fr', '$2y$10$159n5rpHKQGqt1K529c1BeH/GMTP9eOBSFaziNZIYfrfQweHJk2j.', '', 'user', 1, '2024-09-22 14:38:00', '2024-09-22 14:38:00');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
