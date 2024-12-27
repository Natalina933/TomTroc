-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 27 déc. 2024 à 10:05
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
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `book`
--

INSERT INTO `book` (`id`, `title`, `author`, `img`, `description`, `createdAt`, `updatedAt`, `available`, `user_id`) VALUES
(1, 'Esther D.fff', 'Gabriel Garcia Marquez', '/assets/img/books/book_673deafc24a422.42607440.webp', 'A story that chronicles several generations of the Buendía family.', '2024-08-05 10:00:00', '2024-11-20 17:35:00', 1, 0),
(2, 'The Kinfolk Tablefffff', 'Jane Austen', '/assets/img/books/The Kinfolk Table.png', 'A romantic novel that charts the emotional development of the protagonist Elizabeth Bennet.', '2024-08-05 10:00:00', NULL, 1, 0),
(6, 'Wabi Sabiccc', 'Mark Twain', '/assets/img/books/Wabi Sabi.png', 'A novel about a young boy who travels down the Mississippi River on a raft with a runaway slave.gg', '2024-08-05 10:00:00', '2024-10-24 15:02:05', 1, 6),
(4, 'Milk & honey', 'Haruki Murakami', '/assets/img/books/Milk & honey.png', 'A metaphysical novel that blends reality and fantasy, featuring two distinct, yet interrelated plots.', '2024-08-05 10:00:00', NULL, 1, 4),
(5, 'Delight!', 'Chimamanda Ngozi Adichie', '/assets/img/books/Delight!.png', 'A story about a young Nigerian woman who moves to the United States for university.', '2024-08-05 10:00:00', NULL, 1, 5),
(7, 'Title Example', 'Author Example', '/assets/img/defaultBook.webp', 'Description Example', '2024-11-28 17:33:57', '2024-11-28 17:33:57', 1, 1),
(8, 'fffff', 'ffff', '/assets/img/defaultBook.webp', 'fffff', '2024-12-09 12:17:34', '2024-12-09 12:17:34', 1, 6),
(9, 'adddd', 'dddd', '/assets/img/defaultBook.webp', 'ddddddd', '2024-12-11 14:13:29', '2024-12-11 14:13:29', 1, 0),
(10, 'ffrrrr', 'jtn<bnnq', '/assets/img/defaultBook.webp', 's<bb', '2024-12-11 14:39:07', '2024-12-11 14:39:07', 1, 0),
(11, 'ggggg', 'ggggggg', '/assets/img/defaultBook.webp', 'gggggggggg', '2024-12-11 15:06:59', '2024-12-11 15:06:59', 1, 0),
(12, 'vvvvv', 'vvvvvvvvvvvv', '/assets/img/defaultBook.webp', 'vvvvvvvvv', '2024-12-11 15:27:38', '2024-12-11 15:27:38', 1, 0),
(13, 'Titre exemple', 'Auteur exemple', '/assets/img/defaultBook.webp', 'Description exemple', '2024-12-11 11:00:00', '2024-12-11 11:00:00', 1, 1),
(14, 'kkkk', 'mmmmm', '/assets/img/defaultBook.webp', 'jjjjjj', '2024-12-11 15:49:29', '2024-12-11 15:49:29', 1, 0),
(15, 'eeeeeeeeeee', 'eeeeeeeeeee', '/assets/img/defaultBook.webp', 'eeeeeeeeeeeeeee', '2024-12-11 15:56:53', '2024-12-11 15:56:53', 1, 0),
(16, 'ggggg', 'ggggg', '/assets/img/defaultBook.webp', 'ggggg', '2024-12-12 09:25:42', '2024-12-12 16:04:43', 1, 7),
(17, 'livre', 'MARTHE', '/assets/img/defaultBook.webp', 'KFMQKFPJFP', '2024-12-12 16:28:19', '2024-12-12 16:28:19', 1, 7),
(18, 'Curie', 'MARTHE', '/assets/img/defaultBook.webp', 'ohhh le curie', '2024-12-18 14:20:52', '2024-12-18 14:20:52', 1, 9),
(19, 'frigot', 'moi', '/assets/img/defaultBook.webp', 'fffffff', '2024-12-18 14:52:14', '2024-12-18 14:52:14', 1, 9),
(20, 'jhg', 'bdxwb', '/assets/img/defaultBook.webp', 'nsnsd', '2024-12-27 08:38:43', '2024-12-27 08:38:43', 1, 10);

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
) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `message`
--

INSERT INTO `message` (`id`, `sender_id`, `receiver_id`, `content`, `created_at`, `is_read`) VALUES
(21, 9, 6, 'Salut ! Comment vas-tu ?', '2024-10-04 06:30:00', 1),
(2, 6, 9, 'Je vais bien, merci ! Et toi ?', '2024-10-04 06:35:00', 1),
(3, 10, 6, 'Salut, tu as vu mon dernier livre ?', '2024-10-04 07:00:00', 1),
(10, 10, 9, 'Oui, il a l’air intéressant !', '2024-10-04 07:10:00', 1),
(9, 9, 6, 'Bonjour,\n\nJe voulais te remercier pour l\'échange de livres que nous avons effectué la semaine dernière. J\'ai commencé à lire celui que tu m\'as envoyé, et je dois dire qu\'il est vraiment captivant. L\'histoire est bien construite et les personnages sont très attachants. Je suis curieuse de savoir ce que tu as pensé du livre que je t\'ai envoyé, si tu as eu le temps de le lire. En tout cas, n\'hésite pas à me faire signe si tu souhaites échanger d\'autres livres à l\'avenir. Ce serait avec plaisir !\n\nÀ très bientôt !\n\nAmicalement,', '2024-10-04 08:00:00', 1),
(6, 6, 9, 'Oui, avec plaisir !', '2024-10-04 08:05:00', 1),
(22, 6, 9, 'ggggg', '2024-10-18 15:56:55', 0),
(23, 6, 9, 'bonjour', '2024-10-18 15:59:13', 0),
(24, 9, 6, 'bonjour', '2024-10-18 16:00:51', 1),
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
(39, 6, 9, 'ooo', '2024-10-23 11:46:15', 1),
(40, 6, 9, 'ooo', '2024-10-23 12:04:23', 1),
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
(66, 6, 9, 'bhgdf', '2024-10-30 17:37:58', 0),
(67, 10, 6, 'fff', '2024-10-30 18:06:15', 1),
(68, 6, 4, 'Début de la conversation', '2024-10-31 12:03:26', 0),
(69, 6, 6, 'Début de la conversation', '2024-10-31 12:11:14', 1),
(70, 6, 6, 'bonjouro', '2024-11-07 11:08:52', 1),
(71, 9, 6, 'hollo', '2024-11-07 16:24:57', 1),
(72, 9, 6, 'salut', '2024-11-07 16:33:15', 1),
(73, 9, 6, 'hello', '2024-11-20 12:17:53', 1),
(74, 9, 4, '', '2024-11-20 13:35:00', 0),
(75, 9, 10, 'hollllaaaa', '2024-11-20 14:52:29', 1),
(76, 10, 6, 'ggggggg', '2024-12-26 10:10:20', 1),
(77, 10, 9, 'saluurrkop', '2024-12-26 10:27:59', 1),
(78, 10, 6, 'gshw&lt;h&lt;w', '2024-12-26 10:33:35', 0),
(79, 10, 6, 'commen t vas tu ?', '2024-12-26 11:28:43', 0),
(82, 6, 1, 'hola', '2024-12-26 17:33:34', 0),
(80, 6, 10, 'gggggg', '2024-12-26 17:24:59', 1),
(81, 6, 10, 'gggggg', '2024-12-26 17:25:46', 1),
(83, 10, 6, ',,,,,wfb', '2024-12-27 09:51:28', 0);

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
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `profilePicture`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'johndoe', 'john.doe@example.com', '482c811da5d5b4bc6d497ffa98491e38', '/assets/img/users/johndoe.jpg', 'user', 1, '2024-08-13 16:01:14', '2024-10-11 11:43:47'),
(2, 'janedoe', 'jane.doe@example.com', '96b33694c4bb7dbd07391e0be54745fb', '/assets/img/users/janedoe.png', 'admin', 1, '2024-08-13 16:01:14', '2024-08-20 14:30:00'),
(3, 'marksmith', 'mark.smith@example.com', '7d347cf0ee68174a3588f6cba31b8a67', '/assets/img/users/marksmith.png', 'moderator', 1, '2024-08-13 16:01:14', '2024-08-20 14:30:00'),
(4, 'emilyjones', 'emily.jones@example.com', '34819d7beeabb9260a5c854bc85b3e44', '/assets/img/users/emilyjones.png', 'user', 1, '2024-08-13 16:01:14', '2024-08-20 14:30:00'),
(5, 'davidsmith', 'david.smith@example.com', 'b0439fae31f8cbba6294af86234d5a28', '/assets/img/users/davidsmith.png', 'user', 0, '2024-08-13 16:01:14', '2024-08-20 14:30:00'),
(6, 'Emilieea', 'Emiliee123@free.fr', '$2y$10$AJxoxMLeZWAPaSwc4PQr9uZS/uNvhpRDGkf.wcjBkRWE/htn1uL4C', '/assets/img/users/profile_67571469ceaf46.48123359.jpeg', 'user', 1, '2024-08-23 11:54:56', '2024-12-09 17:01:45'),
(7, 'nat123@free', 'nat123@free', '$2y$10$ZGbesW7apJ68YZ9Kd24zH.0hPXM8DXWppkn4Ngb.LO/7pBay5asQ6', '', 'user', 1, '2024-09-14 10:24:04', '2024-09-14 12:24:04'),
(22, 'natouxf', 'natouxf123@free.fr', '$2y$10$ijYCBueGVkrBB7.z6aBMPeoXtQfFSSeY6kPRqiFJMrmwNaM6i7qrO', '', 'user', 1, '2024-11-25 12:56:14', '2024-11-25 12:56:14'),
(9, 'aa', '1230@free.fr', '$2y$10$DbCjgo/agVyD0Wrk1Mx85.SeLZJS36H7wNF/21H1HwAuN5reYUK1K', '/assets/img/users/profile_673dd2975ae3a2.26109803.png', 'user', 1, '2024-09-14 10:28:05', '2024-11-20 13:14:15'),
(10, 'nath', '020202@gh.fr', '$2y$10$DAMZC/jTEvvcyp.ckdWE4OXgC2kYOjkwN2eUmpiTmOc8tNSNkT.Ia', '/assets/img/users/profile_676552f2ace592.67002472.jpeg', 'user', 1, '2024-09-21 14:34:54', '2024-12-20 12:20:18'),
(11, 'popo', '0212345@free.fr', '$2y$10$YfP5ClzmeWsu/COEO0vPGexg4NCuUS8ia8xbrvzC83XSQVe/pQLRe', '', 'user', 1, '2024-09-21 14:38:39', '2024-10-18 11:05:59'),
(15, 'oioi', '741@fre.fr', '$2y$10$sLBJz3dRYib.st260IXwLukeICw2lM7U/xe89sCraqR719WIywT/.', '', 'user', 1, '2024-09-21 15:26:12', '2024-09-21 15:26:12'),
(16, 'totot', '123456789@gm.fr', '$2y$10$b87ZUspDMkq7NIaSqmTGR.kph4SkhG.3rBW2SubnEiDZ28Gc.djj2', '', 'user', 1, '2024-09-21 16:00:38', '2024-09-21 16:00:38'),
(23, '1234567@gmail.fr', '1234567@gmail.fr', '$2y$10$ImpxGSUCmKSz5I89ie9wAO82k4dEnGyOD7zlJv6AlVWepfDAdO0RK', '', 'user', 1, '2024-11-27 16:14:02', '2024-11-27 16:14:02'),
(19, 'ththt', '00000@fr.fr', '$2y$10$6RmhatlQIhuVQhKnTsNxcedFdrn27fwDPLnPgY.0ZwiR4QwmF80mS', '', 'user', 1, '2024-09-22 14:27:20', '2024-09-22 14:27:20'),
(21, 'titkttk', '2222@fr.fr', '$2y$10$159n5rpHKQGqt1K529c1BeH/GMTP9eOBSFaziNZIYfrfQweHJk2j.', '', 'user', 1, '2024-09-22 14:38:00', '2024-09-22 14:38:00');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
