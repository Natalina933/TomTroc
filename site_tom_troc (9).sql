-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 30 oct. 2024 à 17:46
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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `book`
--

INSERT INTO `book` (`id`, `title`, `author`, `img`, `description`, `createdAt`, `updatedAt`, `available`, `user_id`) VALUES
(1, 'Esther', 'Gabriel Garcia Marquez', '/assets/img/books/Esther.png', 'A story that chronicles several generations of the Buendía family.', '2024-08-05 10:00:00', NULL, 0, 9),
(2, 'The Kinfolk Table', 'Jane Austen', '/assets/img/books/The Kinfolk Table.png', 'A romantic novel that charts the emotional development of the protagonist Elizabeth Bennet.', '2024-08-05 10:00:00', NULL, 1, 9),
(6, 'Wabi Sabi', 'Mark Twain', '/assets/img/books/Wabi Sabi.png', 'A novel about a young boy who travels down the Mississippi River on a raft with a runaway slave.gg', '2024-08-05 10:00:00', '2024-10-24 15:02:05', 1, 6),
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
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_read` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`)
) ENGINE=MyISAM AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `message`
--

INSERT INTO `message` (`id`, `sender_id`, `receiver_id`, `content`, `created_at`, `is_read`) VALUES
(21, 9, 6, 'Salut ! Comment vas-tu ?', '2024-10-04 06:30:00', 0),
(2, 10, 11, 'Je vais bien, merci ! Et toi ?', '2024-10-04 06:35:00', 1),
(3, 6, 10, 'Salut, tu as vu mon dernier livre ?', '2024-10-04 07:00:00', 1),
(10, 6, 9, 'Oui, il a l’air intéressant !', '2024-10-04 07:10:00', 1),
(9, 9, 6, 'Bonjour,\n\nJe voulais te remercier pour l\'échange de livres que nous avons effectué la semaine dernière. J\'ai commencé à lire celui que tu m\'as envoyé, et je dois dire qu\'il est vraiment captivant. L\'histoire est bien construite et les personnages sont très attachants. Je suis curieuse de savoir ce que tu as pensé du livre que je t\'ai envoyé, si tu as eu le temps de le lire. En tout cas, n\'hésite pas à me faire signe si tu souhaites échanger d\'autres livres à l\'avenir. Ce serait avec plaisir !\n\nÀ très bientôt !\n\nAmicalement,', '2024-10-04 08:00:00', 0),
(6, 6, 9, 'Oui, avec plaisir !', '2024-10-04 08:05:00', 1),
(22, 6, 9, 'ggggg', '2024-10-18 15:56:55', 0),
(23, 6, 9, 'bonjour', '2024-10-18 15:59:13', 0),
(24, 9, 6, 'bonjour', '2024-10-18 16:00:51', 0),
(25, 6, 9, 'hola', '2024-10-18 16:01:30', 0),
(26, 6, 9, 'coucou', '2024-10-23 10:17:20', 0),
(27, 6, 9, 'gsd', '2024-10-23 10:18:03', 0),
(28, 6, 9, 'gsd', '2024-10-23 10:18:16', 0),
(29, 6, 9, 'pourquoi', '2024-10-23 10:20:28', 0),
(30, 6, 9, 'why', '2024-10-23 10:24:35', 0),
(31, 6, 9, 'ohh', '2024-10-23 11:01:51', 0),
(32, 6, 9, 'bien', '2024-10-23 11:15:49', 0),
(33, 6, 9, 'eyhh', '2024-10-23 11:23:26', 0),
(34, 6, 9, 'no', '2024-10-23 11:24:38', 0),
(35, 6, 9, 'iuu', '2024-10-23 11:29:31', 0),
(36, 6, 9, 'iuu', '2024-10-23 11:42:48', 0),
(37, 6, 9, 'rrrr', '2024-10-23 11:42:56', 0),
(38, 6, 9, 'rrrr', '2024-10-23 11:46:05', 0),
(39, 6, 9, 'ooo', '2024-10-23 11:46:15', 0),
(40, 6, 9, 'ooo', '2024-10-23 12:04:23', 0),
(41, 6, 9, 'jgoJSvnlKNKPKPKb,;.%L¨P^,M?M?ML', '2024-10-23 12:04:58', 0),
(42, 6, 9, 'jgoJSvnlKNKPKPKb,;.%L¨P^,M?M?ML', '2024-10-23 12:05:22', 0),
(43, 6, 9, 'GGG', '2024-10-23 12:05:27', 0),
(44, 6, 9, 'GGG', '2024-10-23 14:58:36', 0),
(45, 6, 9, 'GGG', '2024-10-23 14:58:52', 0),
(46, 6, 9, 'gg', '2024-10-23 15:08:12', 0),
(47, 6, 9, 'gg', '2024-10-23 15:45:43', 0),
(48, 6, 9, 'ii', '2024-10-23 15:45:59', 0),
(49, 6, 9, 'ii', '2024-10-23 15:57:29', 0),
(50, 6, 9, 'ii', '2024-10-23 15:58:23', 0),
(51, 6, 9, 'eee', '2024-10-24 14:34:36', 0),
(52, 6, 9, 'dddd', '2024-10-30 15:06:07', 0),
(53, 6, 9, 'dddd', '2024-10-30 15:09:10', 0),
(54, 6, 9, 'dddd', '2024-10-30 15:10:53', 0),
(55, 6, 9, 'dddd', '2024-10-30 15:11:09', 0),
(56, 6, 9, 'dddd', '2024-10-30 15:11:47', 0),
(57, 6, 9, 'dddd', '2024-10-30 15:12:07', 0),
(58, 6, 9, 'dddd', '2024-10-30 15:15:58', 0),
(59, 6, 9, 'dddd', '2024-10-30 15:18:12', 0),
(60, 6, 9, 'dddd', '2024-10-30 15:20:03', 0),
(61, 6, 9, 'dddd', '2024-10-30 15:20:28', 0),
(62, 6, 9, 'dddd', '2024-10-30 15:20:42', 0),
(63, 6, 9, 'dddd', '2024-10-30 15:20:56', 0),
(64, 6, 9, 'dddd', '2024-10-30 15:26:24', 0),
(65, 6, 9, 'ff', '2024-10-30 15:26:35', 0),
(66, 6, 9, 'bhgdf', '2024-10-30 17:37:58', 0);

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
(1, 'johndoe', 'john.doe@example.com', '482c811da5d5b4bc6d497ffa98491e38', '/assets/img/users/johndoe.jpg', 'user', 1, '2024-08-13 16:01:14', '2024-10-11 11:43:47'),
(2, 'janedoe', 'jane.doe@example.com', '96b33694c4bb7dbd07391e0be54745fb', '/assets/img/users/janedoe.png', 'admin', 1, '2024-08-13 16:01:14', '2024-08-20 14:30:00'),
(3, 'marksmith', 'mark.smith@example.com', '7d347cf0ee68174a3588f6cba31b8a67', '/assets/img/users/marksmith.png', 'moderator', 1, '2024-08-13 16:01:14', '2024-08-20 14:30:00'),
(4, 'emilyjones', 'emily.jones@example.com', '34819d7beeabb9260a5c854bc85b3e44', '/assets/img/users/emilyjones.png', 'user', 1, '2024-08-13 16:01:14', '2024-08-20 14:30:00'),
(5, 'davidsmith', 'david.smith@example.com', 'b0439fae31f8cbba6294af86234d5a28', '/assets/img/users/davidsmith.png', 'user', 0, '2024-08-13 16:01:14', '2024-08-20 14:30:00'),
(6, 'Emilieea', 'Emiliee123@free.fr', '$2y$10$AJxoxMLeZWAPaSwc4PQr9uZS/uNvhpRDGkf.wcjBkRWE/htn1uL4C', '/assets/img/users/profile_6708da1bccc423.20035616.jpeg', 'user', 1, '2024-08-23 11:54:56', '2024-10-24 16:33:58'),
(7, 'nat123@free', 'nat123@free', '$2y$10$ZGbesW7apJ68YZ9Kd24zH.0hPXM8DXWppkn4Ngb.LO/7pBay5asQ6', '', 'user', 1, '2024-09-14 10:24:04', '2024-09-14 12:24:04'),
(9, 'aa', '1230@free.fr', '$2y$10$DbCjgo/agVyD0Wrk1Mx85.SeLZJS36H7wNF/21H1HwAuN5reYUK1K', '/assets/img/users/profile_66f7d4c4831c74.39756367.jpeg', 'user', 1, '2024-09-14 10:28:05', '2024-09-28 12:04:52'),
(10, 'nath', '020202@gh.fr', '$2y$10$DAMZC/jTEvvcyp.ckdWE4OXgC2kYOjkwN2eUmpiTmOc8tNSNkT.Ia', '', 'user', 1, '2024-09-21 14:34:54', '2024-10-11 11:41:02'),
(11, 'popo', '0212345@free.fr', '$2y$10$YfP5ClzmeWsu/COEO0vPGexg4NCuUS8ia8xbrvzC83XSQVe/pQLRe', '', 'user', 1, '2024-09-21 14:38:39', '2024-10-18 11:05:59'),
(15, 'oioi', '741@fre.fr', '$2y$10$sLBJz3dRYib.st260IXwLukeICw2lM7U/xe89sCraqR719WIywT/.', '', 'user', 1, '2024-09-21 15:26:12', '2024-09-21 15:26:12'),
(16, 'totot', '123456789@gm.fr', '$2y$10$b87ZUspDMkq7NIaSqmTGR.kph4SkhG.3rBW2SubnEiDZ28Gc.djj2', '', 'user', 1, '2024-09-21 16:00:38', '2024-09-21 16:00:38'),
(19, 'ththt', '00000@fr.fr', '$2y$10$6RmhatlQIhuVQhKnTsNxcedFdrn27fwDPLnPgY.0ZwiR4QwmF80mS', '', 'user', 1, '2024-09-22 14:27:20', '2024-09-22 14:27:20'),
(21, 'titkttk', '2222@fr.fr', '$2y$10$159n5rpHKQGqt1K529c1BeH/GMTP9eOBSFaziNZIYfrfQweHJk2j.', '', 'user', 1, '2024-09-22 14:38:00', '2024-09-22 14:38:00');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
